<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

use App\Models\AgendaItem;
use App\Models\EducationalContent;
use App\Models\Post;
use App\Models\Trabalho;
use App\Models\EsgInitiative;
use App\Models\CompanyProject;
use App\Models\Conversation;
use App\Models\User;

use App\Policies\AgendaItemPolicy;
use App\Policies\EducationalContentPolicy;
use App\Policies\PostPolicy;
use App\Policies\TrabalhoPolicy;
use App\Policies\EsgInitiativePolicy;
use App\Policies\CompanyProjectPolicy;
use App\Policies\ConversationPolicy;
use App\Policies\UserPolicy;
use App\Services\ChatService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Policies (Laravel 11+ não cria AuthServiceProvider por padrão)
        Gate::policy(AgendaItem::class, AgendaItemPolicy::class);
        Gate::policy(EducationalContent::class, EducationalContentPolicy::class);
        Gate::policy(Post::class, PostPolicy::class);
        Gate::policy(Trabalho::class, TrabalhoPolicy::class);
        Gate::policy(EsgInitiative::class, EsgInitiativePolicy::class);
        Gate::policy(CompanyProject::class, CompanyProjectPolicy::class);
        Gate::policy(Conversation::class, ConversationPolicy::class);
        Gate::policy(User::class, UserPolicy::class);

        // Rate limiting (produção) para chat
        RateLimiter::for('chat-send', function (Request $request) {
            $key = optional($request->user())->id ?: $request->ip();
            return Limit::perMinute(20)->by('chat-send:'.$key);
        });

        RateLimiter::for('chat-start', function (Request $request) {
            $key = optional($request->user())->id ?: $request->ip();
            return Limit::perMinute(6)->by('chat-start:'.$key);
        });


        // Share unread chat count for badges in layouts (produção)
        View::composer(['layouts.app', 'layouts.partner', 'layouts.admin'], function ($view) {
            $count = 0;
            if (auth()->check()) {
                try {
                    $count = app(ChatService::class)->unreadConversationsCount(auth()->user());
                } catch (\Throwable $e) {
                    $count = 0;
                }
            }
            $view->with('chatUnreadCount', $count);
        });

        // Admin tem acesso total
        Gate::before(function (?User $user, string $ability) {
            if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) {
                return true;
            }
            return null;
        });
    }
}
