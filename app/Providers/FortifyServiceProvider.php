<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Fortify::loginView(function () {
        //     return Inertia::render("Auth/Login")->toResponse(request());
        // });
        // Fortify::registerView(function () {
        //     return Inertia::render("Auth/Register")->toResponse(request());
        // });
        // Fortify::requestPasswordResetLinkView(function () {
        //     return Inertia::render("Auth/PasswordResetLink")->toResponse(request());
        // });
        // Fortify::resetPasswordView(function () {
        //     return Inertia::render("Auth/ResetPassword")->toResponse(request());
        // });
        // Fortify::verifyEmailView(function () {
        //     return Inertia::render("Auth/VerifyEmail")->toResponse(request());
        // });
        
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
    }
}
