<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\View;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use App\Models\TshirtImage;
use App\Policies\CustomTshirtImagePolicy;
use App\Policies\TshirtImagePolicy;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        Gate::define('admin', function (User $user) {
            // Only "administrator" users can "admin"
            return $user->admin;
        });

        Gate::define('access-profile', function (User $user) {
            return $user->isCustomer() || $user->isAdmin();
        });
        Gate::policy(TshirtImage::class, TshirtImagePolicy::class);

// Gates  para imagens privadas (cliente)
        Gate::define('viewAny-own-image', [\App\Policies\CustomTshirtImagePolicy::class, 'viewAny']);
        Gate::define('view-own-image',   [\App\Policies\CustomTshirtImagePolicy::class, 'view']);
        Gate::define('update-own-image', [\App\Policies\CustomTshirtImagePolicy::class, 'update']);
        Gate::define('delete-own-image', [\App\Policies\CustomTshirtImagePolicy::class, 'delete']);
        Gate::define('create-own-image', [\App\Policies\CustomTshirtImagePolicy::class, 'create']);
        Gate::define('file-own-image', [\App\Policies\CustomTshirtImagePolicy::class, 'file']);

        // REQUISITO G1: Intercetar o Login para validar se o utilizador está Bloqueado
        Fortify::authenticateUsing(function (Request $request) {
            // Procurar o utilizador pelo e-mail inserido no formulário
            $user = User::where('email', $request->email)->first();

            // Validar se o utilizador existe e se a password coincide
            if ($user && Hash::check($request->password, $user->password)) {

                // Se as credenciais estiverem certas, verifica se a conta está bloqueada (blocked == 1)
                if ($user->blocked == 1) {
                    throw ValidationException::withMessages([
                        'email' => 'A sua conta encontra-se bloqueada. Contacte o administrador.',
                    ]);
                }

                // Se não estiver bloqueado, faz o login normalmente
                return $user;
            }

            // Se falhar o email ou a password, retorna null para o Laravel dar o erro padrão de credenciais
            return null;
        });

        //try {
            // View::share adds data (variables) that are shared through all views
        //    View::share('sharedCourses', Course::orderBy('type')->orderBy('abbreviation')->get());
        //} catch (\Exception $e) {
            // No need to do anything – this just ensures that no exception is
            // thrown if "courses" table does not exist when running
            // "php artisan migrate" for the first time
        //}
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn(): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
