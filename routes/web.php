<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SubscriberController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LinksController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\HseChatController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\EducationalContentController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\TalentBankController;
use App\Http\Controllers\Admin\AdminJobController;
use App\Http\Controllers\Admin\AdminEducationalContentController;
use App\Http\Controllers\Admin\AdminAgendaController;
use App\Http\Controllers\Partner\CompanyProjectController;
use App\Http\Controllers\Partner\EsgInitiativeController;
use App\Http\Controllers\Partner\PartnerJobController;
use App\Http\Controllers\Partner\DashboardController;
use App\Http\Controllers\Partner\CompanyNeedController;
use App\Http\Controllers\Partner\TalentSearchController;
use App\Http\Controllers\Partner\CompanyProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\Admin\AdminCompanyController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Broadcast;

// Fallback para servir ficheiros do storage/public quando o storage:link não existe
// (mantém compatibilidade com URLs geradas por asset('storage/...'))
Route::get('/storage/{path}', [StorageController::class, 'show'])
    ->where('path', '.*')
    ->name('storage.fallback');

// Broadcasting auth endpoint + channel authorization
Broadcast::routes(['middleware' => ['web', 'auth']]);
require __DIR__.'/channels.php';

// Broadcasting auth endpoint + channel authorization


// Chat (mensagens privadas em tempo real)
Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');

    // Pesquisa de utilizadores para iniciar nova conversa (AJAX)
    Route::get('/chat/users', [ChatController::class, 'users'])->name('chat.users');

    Route::post('/chat/start', [ChatController::class, 'start'])
        ->middleware('throttle:chat-start')
        ->name('chat.start');

    Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');

    
    Route::post('/send-message', [MessageController::class, 'enviar'])->name('chat.enviar');

    Route::post('/chat/{conversation}/send', [ChatController::class, 'send'])
        ->middleware('throttle:chat-send')
        ->name('chat.send');

    Route::post('/chat/{conversation}/read', [ChatController::class, 'read'])->name('chat.read');
});

// Autenticação (todos os tipos de utilizador)
Route::get("/login", [LoginController::class, "show"])->name("login");
Route::post("/login", [LoginController::class, "authenticate"])->name("login.attempt");
Route::post("/logout", [LoginController::class, "logout"])->name("logout");

Route::get("/registo", [RegisterController::class, "choose"])->name("register");
Route::get("/registo/profissional", [RegisterController::class, "showProfessional"])->name("register.professional");
Route::post("/registo/profissional", [RegisterController::class, "storeProfessional"])->name("register.professional.store");
Route::get("/registo/empresa", [RegisterController::class, "showCompany"])->name("register.company");
Route::post("/registo/empresa", [RegisterController::class, "storeCompany"])->name("register.company.store");

Route::post('/register/check-email', [RegisterController::class, 'checkEmail'])->name('register.check.email');
Route::post('/register/check-nif', [RegisterController::class, 'checkNif'])->name('register.check.nif');

// Público
Route::get('/vagas-hse', [JobController::class, 'index'])->name('jobs.index');
Route::get('/vagas-hse/{slug}', [JobController::class, 'show'])->name('jobs.show');

// Agenda Nacional de HSE & ESG (Público)
Route::get('/agenda-hse', [AgendaController::class, 'index'])->name('agenda.index');
Route::get('/agenda-hse/{slug}', [AgendaController::class, 'show'])->name('agenda.show');
Route::post('/agenda-hse/{slug}/inscrever', [AgendaController::class, 'register'])->name('agenda.register');

Route::get('/conteudos-educativos', [EducationalContentController::class, 'index'])->name('educational.index');
Route::get('/conteudos-educativos/{slug}', [EducationalContentController::class, 'show'])->name('educational.show');

// Banco de Talentos HSE (Público + Perfil do Profissional)
Route::get('/banco-talentos-hse', [TalentBankController::class, 'index'])->name('talent.index');
Route::get('/banco-talentos-hse/perfil', [TalentBankController::class, 'show'])->middleware(['auth','professional'])->name('talent.profile.show');
Route::get('/banco-talentos-hse/perfil/editar', [TalentBankController::class, 'edit'])->middleware(['auth','professional'])->name('talent.profile.edit');
Route::post('/banco-talentos-hse/perfil', [TalentBankController::class, 'update'])->middleware(['auth','professional'])->name('talent.profile.update');


// Links úteis
Route::get('/links-uteis', [LinksController::class, 'index'])->name('links.index');

// Chatbot HSE
#Route::get('/chatbot-hse', [ChatbotController::class, 'index'])->name('chatbot.index');
Route::post('/chatbot-hse/message', [ChatbotController::class, 'message'])->name('chatbot.message');
Route::post('/chatbot-hse/lead', [ChatbotController::class, 'lead'])->name('chatbot.lead');


Route::get('/chatbot-hse', [HseChatController::class, 'index'])->name('chatbot.index');

// Streaming (SSE)
Route::post('/chatbot-hse/stream', [HseChatController::class, 'stream'])->name('chatbot.stream');



// Área do Parceiro / Empresa
Route::prefix('empresa')->name('partner.')->middleware('company')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Publicar vagas
    Route::resource('vagas', PartnerJobController::class)->parameters([
        'vagas' => 'job'
    ])->names('jobs');

    // Publicar iniciativas ESG
    Route::resource('iniciativas-esg', EsgInitiativeController::class)->parameters([
        'iniciativas-esg' => 'initiative'
    ])->names('esg');

    // Divulgar projectos
    Route::resource('projectos', CompanyProjectController::class)->parameters([
        'projectos' => 'project'
    ])->names('projects');

    // Banco de Talentos (Empresa) - publicar necessidades + ver talentos + matches
    Route::resource('necessidades', CompanyNeedController::class)->parameters([
        'necessidades' => 'need'
    ])->names('needs');

    Route::get('banco-talentos', [TalentSearchController::class, 'index'])->name('talents.index');

    Route::get('profile', [CompanyProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [CompanyProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile/update', [CompanyProfileController::class, 'update'])->name('profile.update');
    Route::put('/account', [CompanyProfileController::class, 'updateAccount'])->name('profile.account.update');
    Route::delete('/logo', [CompanyProfileController::class, 'removeLogo'])->name('profile.logo.remove');
    Route::delete('/banner', [CompanyProfileController::class, 'removeBanner'])->name('profile.banner.remove');

    Route::prefix('profile')->name('profile.')->group(function () {
        
    });
});


// Admin (mantém teu /admin)
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('jobs', AdminJobController::class);
    Route::resource('educational', AdminEducationalContentController::class)->parameters([
        'educational' => 'educational'
    ]);

    // Agenda (Admin)
    Route::resource('agenda', AdminAgendaController::class)->parameters([
        'agenda' => 'agenda'
    ]);

    // Empresas (cadastro feito pelo Administrador)
    Route::resource('companies', AdminCompanyController::class)->parameters([
        'companies' => 'company'
    ])->only(['index','create','store','edit','update','destroy']);

    Route::get('users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
    Route::post('users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::get('users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    
    Route::resource('users', App\Http\Controllers\Admin\UserController::class)->except(['create', 'store']);
    Route::post('users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('users/bulk-action', [App\Http\Controllers\Admin\UserController::class, 'bulkAction'])->name('users.bulk-action');
});

// Página inicial
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/newsletter/confirm/{token}', [NewsletterController::class, 'confirm'])->name('newsletter.confirm');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
Route::post('/noticias/{post}/comments', [CommentController::class, 'store'])->name('comments.store');


// Listagem pública e detalhes
Route::get('/noticias', [PostController::class, 'publicIndex'])->name('posts.public');
Route::get('/noticias/{slug}', [PostController::class, 'show'])->name('posts.show');

//Comentarios
Route::post('/comments/{post}/ajax', [\App\Http\Controllers\CommentController::class, 'storeAjax'])
    ->name('comments.store.ajax');
    
Route::get('/posts/{post}/comments', [CommentController::class, 'indexAjax'])
    ->name('comments.ajax');

// Subscrição de emails
Route::post('/subscribers', [SubscriberController::class, 'store'])->name('subscribers.store');

// Rotas de "admin" simples (idealmente proteger com auth)
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
});