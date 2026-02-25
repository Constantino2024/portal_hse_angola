<?php

namespace App\Http\Controllers;

use App\Http\Requests\Chat\SendMessageRequest;
use App\Http\Requests\Chat\StartConversationRequest;
use App\Models\Conversation;
use App\Models\User;
use App\Services\ChatService;
use App\Support\HandlesControllerErrors;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    use HandlesControllerErrors;

    public function __construct(private ChatService $chatService)
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return $this->runWithErrors(function () use ($request) {
            $user = $request->user();

                        $conversations = Conversation::query()
                ->forUser($user->id)
                ->with([
                    'participants' => function ($q) use ($user) {
                        $q->where('users.id', '!=', $user->id);
                    },
                    'lastMessage.sender'
                ])
                ->select('conversations.*')
                ->addSelect([
                    'my_last_read_message_id' => \Illuminate\Support\Facades\DB::table('conversation_user')
                        ->select('last_read_message_id')
                        ->whereColumn('conversation_user.conversation_id', 'conversations.id')
                        ->where('conversation_user.user_id', $user->id)
                        ->limit(1),
                ])
                ->orderByDesc('last_message_at')
                ->paginate(20);

            $layout = (method_exists($user, 'isCompany') && $user->isCompany()) ? 'layouts.partner' : 'layouts.app';
            return view('chat.index', compact('conversations', 'layout'));
        });
    }

    
    public function users(Request $request)
    {
        return $this->runWithErrors(function () use ($request) {
            $user = $request->user();
            $q = trim((string) $request->get('q', ''));

            // Requisito: ao abrir "Nova conversa", listar utilizadores (profissionais e empresas) sem exigir pesquisa.
            // Para não pesar em produção, limitamos o "listar tudo" a um número razoável.
            $baseQuery = User::query()
                ->where('id', '!=', $user->id)
                ->whereIn('role', ['profissional', 'empresa'])
                ->with(['talentProfile:id,user_id,profile_image'])
                ->orderByRaw("CASE WHEN role='empresa' THEN 0 ELSE 1 END") // empresas primeiro (ajuda no UX)
                ->orderBy('name');

            if ($q !== '') {
                // Se o utilizador está a pesquisar, exigimos pelo menos 2 caracteres para evitar queries vazias.
                if (mb_strlen($q) < 2) {
                    return response()->json([]);
                }

                $baseQuery->where(function ($query) use ($q) {
                    $query->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            }

            $results = $baseQuery
                ->limit(60)
                ->get(['id', 'name', 'email', 'role', 'company_logo']);

            return response()->json($results->map(function ($u) {
                $role = $u->role ?? 'profissional';

                // Avatar real (quando existir):
                // - profissional: usa a imagem do perfil do Banco de Talentos (HseTalentProfile.profile_image)
                // - empresa: usa o logo no user (company_logo)
                $avatarUrl = null;
                if ($role === 'profissional') {
                    $img = $u->talentProfile?->profile_image;
                    if (!empty($img)) {
                        $avatarUrl = asset('storage/' . ltrim($img, '/'));
                    }
                } elseif ($role === 'empresa') {
                    $logo = $u->company_logo;
                    if (!empty($logo)) {
                        $avatarUrl = asset('storage/' . ltrim($logo, '/'));
                    }
                }

                $name = trim((string) $u->name);
                $initials = $name !== '' ? strtoupper(mb_substr($name, 0, 1)) : '?';

                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'role' => $role,
                    'badge' => $role === 'empresa' ? 'Empresa' : 'Profissional',
                    'avatar_url' => $avatarUrl,
                    'initials' => $initials,
                ];
            })->values());
        });
    }

    public function start(StartConversationRequest $request)
    {
        return $this->runWithErrors(function () use ($request) {
            $recipient = User::findOrFail((int) $request->validated('recipient_id'));
            $conversation = $this->chatService->getOrCreatePrivateConversation($request->user(), $recipient);

            if ($request->expectsJson()) {
                return response()->json([
                    'conversation_id' => $conversation->id,
                    'url' => route('chat.show', $conversation),
                ]);
            }

            return redirect()->route('chat.show', $conversation);
        });
    }

    public function show(Conversation $conversation, Request $request)
    {
        return $this->runWithErrors(function () use ($conversation, $request) {
            $this->authorize('view', $conversation);

            $user = $request->user();
            $other = $conversation->participants()->where('users.id', '!=', $user->id)->first();

            // Mark as read when user opens the conversation
            $this->chatService->markAsRead($conversation, $user);

            $otherLastReadId = null;
            if ($other) {
                $pivot = $conversation->participants()->where('users.id', $other->id)->first()?->pivot;
                $otherLastReadId = $pivot?->last_read_message_id;
            }

            $paginator = $conversation->messages()
                ->with('sender')
                ->orderByDesc('id')
                ->paginate(30)->withQueryString();

            // Mantém ordem crescente para renderização, mas com paginação (produção)
            $messages = $paginator->getCollection()->reverse()->values();

            $layout = (method_exists($user, 'isCompany') && $user->isCompany()) ? 'layouts.partner' : 'layouts.app';
            return view('chat.show', compact('conversation', 'other', 'messages', 'paginator', 'layout', 'otherLastReadId'));
        });
    }

    public function send(Conversation $conversation, SendMessageRequest $request)
    {
        return $this->runWithErrors(function () use ($conversation, $request) {
            $this->authorize('sendMessage', $conversation);
            $msg = $this->chatService->sendMessage($conversation, $request->user(), $request->validated('body'));

            if ($request->expectsJson()) {
                return response()->json(['ok' => true, 'message' => [
                    'id' => $msg->id,
                    'body' => $msg->body,
                    'created_at' => optional($msg->created_at)->toIso8601String(),
                    'sender_id' => $msg->sender_id,
                ]]);
            }

            return redirect()->route('chat.show', $conversation);
        });
    }

    public function enviar(Request $request)
    {
        $request->validate([
            'sender_id' => 'required|exists:users,id',
            'body' => 'required|string',
        ]);

        $message = Message::create([
            'conversation_id' => Auth::id(),
            'sender_id' => $request->sender_id,
            'body' => $request->body,
        ]);

        $user = Auth::user();

        // Disparar o evento
        broadcast(new MessageSent($message, $user, $request->receiver_id))->toOthers();

        return response()->json(['status' => 'Message sent!', 'message' => $message]);
    }

    public function getMessages($receiverId)
    {
        $messages = Message::where(function ($query) use ($receiverId) {
            $query->where('user_id', Auth::id())
                ->where('receiver_id', $receiverId);
        })->orWhere(function ($query) use ($receiverId) {
            $query->where('user_id', $receiverId)
                ->where('receiver_id', Auth::id());
        })->orderBy('created_at', 'asc')->get();

        return response()->json($messages);
    }



    public function read(Conversation $conversation, Request $request)
    {
        return $this->runWithErrors(function () use ($conversation, $request) {
            $this->authorize('view', $conversation);
            $this->chatService->markAsRead($conversation, $request->user());
            return response()->json(['ok' => true]);
        });
    }

}
