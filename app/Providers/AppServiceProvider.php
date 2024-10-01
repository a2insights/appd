<?php

namespace App\Providers;

use App\Models\Associado;
use App\Models\User;
use App\Policies\ActivityPolicy;
use Croustibat\FilamentJobsMonitor\Models\QueueMonitor;
use HusamTariq\FilamentDatabaseSchedule\Models\Schedule;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Marjose123\FilamentWebhookServer\Models\FilamentWebhookServer;
use SolutionForest\FilamentFirewall\Models\Ip;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Relation::morphMap([
            'associado' => Associado::class,
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Ip::class, \App\Policies\IpPolicy::class);
        Gate::policy(QueueMonitor::class, \App\Policies\QueueMonitorPolicy::class);
        Gate::policy(FilamentWebhookServer::class, \App\Policies\WebhookPolicy::class);
        Gate::policy(Role::class, \App\Policies\RolePolicy::class);
        Gate::policy(User::class, \App\Policies\UserPolicy::class);
        Gate::policy(Schedule::class, \App\Policies\SchedulePolicy::class);
        Gate::policy(Activity::class, ActivityPolicy::class);
    }
}
