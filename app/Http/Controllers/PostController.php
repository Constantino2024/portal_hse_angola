<?php

namespace App\Http\Controllers;

use App\Mail\NewPostPublished;
use App\Models\Category;
use App\Models\Post;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Http\Requests\Posts\StorePostRequest;
use App\Http\Requests\Posts\UpdatePostRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Tag;
use App\Services\PostService;

use App\Support\HandlesControllerErrors;
class PostController extends Controller
{
    use HandlesControllerErrors;

    public function __construct(private PostService $service)
    {
        // Não aplica authorizeResource porque este controller também serve rotas públicas.
    }
    /**
     * Sanitização defensiva (sem dependências externas).
     * - Campos simples: remove tags, normaliza espaços.
     * - Campos rich text: remove tags perigosas (script/style/iframe/embed/object) e handlers on*.
     */
    private function sanitizePlain(?string $value, int $maxLen = 5000): ?string
    {
        if ($value === null) return null;
        $v = trim($value);
        // remove tags e normaliza espaços
        $v = strip_tags($v);
        $v = preg_replace('/\s+/u', ' ', $v);
        if ($maxLen > 0) $v = mb_substr($v, 0, $maxLen);
        return $v;
    }

    private function sanitizeRich(?string $html, int $maxLen = 500000): ?string
    {
        if ($html === null) return null;
        $v = trim($html);

        // remove blocos perigosos
        $v = preg_replace('/<\s*(script|style|iframe|object|embed)\b[^>]*>.*?<\s*\/\s*\1\s*>/is', '', $v);
        // remove handlers on*="..." e on*='...'
        $v = preg_replace('/\son\w+\s*=\s*("[^"]*"|\'[^\']*\')/i', '', $v);
        // remove javascript: em href/src
        $v = preg_replace('/\s(href|src)\s*=\s*("|\')\s*javascript:[^"\']*(\2)/i', ' $1=$2#$2', $v);

        if ($maxLen > 0) $v = mb_substr($v, 0, $maxLen);
        return $v;
    }

    // Lista pública de notícias
    // No PostController.php, método publicIndex
    public function publicIndex(Request $request)
    {
        $query = Post::whereNotNull('published_at');

        $search = $request->input('q');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('excerpt', 'like', "%{$search}%")
                ->orWhere('body', 'like', "%{$search}%")
                ->orWhere('author_name', 'like', "%{$search}%");
            });
        }

        // Adicione filtro por categoria se existir
        if ($request->has('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Adicione ordenações
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'oldest':
                    $query->oldest('published_at');
                    break;
                case 'popular':
                    $query->orderBy('views', 'desc');
                    break;
                case 'views':
                    $query->orderBy('views', 'desc');
                    break;
                case 'latest':
                default:
                    $query->latest('published_at');
                    break;
            }
        } else {
            $query->latest('published_at');
        }

        // Adicione filtro por período se existir
        if ($request->has('period')) {
            $now = now();
            switch ($request->period) {
                case 'today':
                    $query->whereDate('published_at', $now->toDateString());
                    break;
                case 'week':
                    $query->whereBetween('published_at', [
                        $now->startOfWeek(),
                        $now->endOfWeek()
                    ]);
                    break;
                case 'month':
                    $query->whereMonth('published_at', $now->month)
                        ->whereYear('published_at', $now->year);
                    break;
                case 'year':
                    $query->whereYear('published_at', $now->year);
                    break;
            }
        }

        $posts = $query->paginate(9)->withQueryString();

        // Carregue as categorias com contagem de posts
        $categories = Category::withCount(['posts' => function($q) {
            $q->whereNotNull('published_at');
        }])->orderBy('posts_count', 'desc')->get();

        // Para compatibilidade com o filtro ativo de categoria
        $selectedCategory = null;
        if ($request->has('category')) {
            $selectedCategory = $request->category;
        }

        return view('posts.index', compact('posts', 'search', 'categories', 'selectedCategory'));
    }


    // Página da notícia
    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        return view('posts.show', compact('post'));
    }

    // --- ROTAS ADMIN (simples, sem autenticação por enquanto) ---

    public function index(Request $request)
    {
        return $this->runWithErrors(function () use ($request) {
        $this->authorize('viewAny', Post::class);
        $query = Post::query()->latest('created_at');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($x) use ($q) {
                $x->where('title', 'like', "%$q%")
                ->orWhere('excerpt', 'like', "%$q%")
                ->orWhere('body', 'like', "%$q%")
                ->orWhere('author_name', 'like', "%$q%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', (bool) $request->featured);
        }

        if ($request->filled('popular')) {
            $query->where('is_popular', (bool) $request->popular);
        }

        $posts = $query->paginate(15)->withQueryString();
        $categories = Category::all();

        return view('admin.posts.index', compact('posts', 'categories'));
    
        });
}


    public function create()
    {
        return $this->runWithErrors(function () {
        $this->authorize('create', Post::class);
        $categories = Category::all();
        return view('admin.posts.create', compact('categories'));
    
        });
}

    public function store(StorePostRequest $request)
    {
        return $this->runWithErrors(function () use ($request) {
        $this->authorize('create', Post::class);
        $data = $request->validated();
        /*
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'author_name'  => 'required|string|max:255',
            'subtitle'     => 'nullable|string|max:255',
            'excerpt'      => 'nullable|string',
            'body'         => 'required|string',
            // Uploads (validação rígida: mime real + extensões + tamanho + dimensões)
            'image'        => 'nullable|file|mimes:jpg,jpeg,png,webp|mimetypes:image/jpeg,image/png,image/webp|max:4096|dimensions:min_width=300,min_height=300,max_width=8000,max_height=8000',
            'image_2'      => 'nullable|file|mimes:jpg,jpeg,png,webp|mimetypes:image/jpeg,image/png,image/webp|max:4096|dimensions:min_width=300,min_height=300,max_width=8000,max_height=8000',
            'image_3'      => 'nullable|file|mimes:jpg,jpeg,png,webp|mimetypes:image/jpeg,image/png,image/webp|max:4096|dimensions:min_width=300,min_height=300,max_width=8000,max_height=8000',
            'image_4'      => 'nullable|file|mimes:jpg,jpeg,png,webp|mimetypes:image/jpeg,image/png,image/webp|max:4096|dimensions:min_width=300,min_height=300,max_width=8000,max_height=8000',
            'video_url'    => 'nullable|url',
            'category_id'  => 'nullable|exists:categories,id',
            'is_featured'  => 'sometimes|boolean',
            'is_popular'   => 'sometimes|boolean',
            'published_at' => 'nullable|date',
        ]);
        */

        $this->service->create($data, $request);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Notícia criada com sucesso!');
    
        });
}

    public function edit(Post $post)
    {
        return $this->runWithErrors(function () use ($post) {
        $this->authorize('update', $post);
        $categories = Category::all();
        return view('admin.posts.edit', compact('post', 'categories'));
    
        });
}

    public function update(UpdatePostRequest $request, Post $post)
    {
        return $this->runWithErrors(function () use ($request, $post) {
        $this->authorize('update', $post);
        $data = $request->validated();
        /*
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'author_name'  => 'required|string|max:255',
            'subtitle'     => 'nullable|string|max:255',
            'excerpt'      => 'nullable|string',
            'body'         => 'required|string',
            'image'        => 'nullable|file|mimes:jpg,jpeg,png,webp|mimetypes:image/jpeg,image/png,image/webp|max:4096|dimensions:min_width=300,min_height=300,max_width=8000,max_height=8000',
            'image_2'      => 'nullable|file|mimes:jpg,jpeg,png,webp|mimetypes:image/jpeg,image/png,image/webp|max:4096|dimensions:min_width=300,min_height=300,max_width=8000,max_height=8000',
            'image_3'      => 'nullable|file|mimes:jpg,jpeg,png,webp|mimetypes:image/jpeg,image/png,image/webp|max:4096|dimensions:min_width=300,min_height=300,max_width=8000,max_height=8000',
            'image_4'      => 'nullable|file|mimes:jpg,jpeg,png,webp|mimetypes:image/jpeg,image/png,image/webp|max:4096|dimensions:min_width=300,min_height=300,max_width=8000,max_height=8000',
            'video_url'    => 'nullable|url',
            'category_id'  => 'nullable|exists:categories,id',
            'is_featured'  => 'sometimes|boolean',
            'is_popular'   => 'sometimes|boolean',
            'published_at' => 'nullable|date',
        ]);
        */

        $this->service->update($post, $data, $request);

        return redirect()->route('admin.posts.index')->with('success', 'Notícia atualizada com sucesso!');

    
        });
}

    public function destroy(Post $post)
    {
        return $this->runWithErrors(function () use ($post) {
        $this->authorize('delete', $post);
        $this->service->delete($post);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Notícia apagada com sucesso!');
    
        });
}
}
