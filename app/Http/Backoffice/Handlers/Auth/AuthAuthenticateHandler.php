<?php

namespace App\Http\Backoffice\Handlers\Auth;

use App\Http\Backoffice\Handlers\Dashboard\DashboardIndexHandler;
use App\Http\Backoffice\Handlers\Handler;
use App\Http\Backoffice\Requests\Auth\LoginRequest;
use App\Http\Kernel;
use App\Http\Util\RouteDefiner;
use Cake\Chronos\Chronos;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Digbang\Security\Contracts\SecurityApi;
use Digbang\Security\Users\UserRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\Router;
use Illuminate\Support\MessageBag;

class AuthAuthenticateHandler extends Handler implements RouteDefiner
{
    public function __invoke(
        LoginRequest $request,
        SecurityApi $securityApi,
        UserRepository $users,
        Redirector $redirector,
        Factory $view
    ) {
        $errors = new MessageBag();

        try {
            $credentials = $request->all(['email', 'username', 'login', 'password']);

            $authenticated = $securityApi->authenticate(
                $credentials,
                $request->input('remember') ?? false);

            if ($authenticated) {
                return $redirector->intended(
                    $securityApi->url()->to(DashboardIndexHandler::route())
                );
            }

            $errors->add('password', trans('backoffice::auth.validation.password.wrong'));

            return $redirector->to(AuthLoginHandler::route())->withInput()->withErrors($errors);
        } catch (ThrottlingException $e) {
            return $view->make('backoffice::auth.throttling', [
                'message' => trans('backoffice::auth.throttling.' . $e->getType(), ['remaining' => (new Chronos())->diffInSeconds($e->getFree())]),
            ]);
        } catch (NotActivatedException $e) {
            return $view->make('backoffice::auth.not-activated');
        }
    }

    /** @param Router $router */
    public static function defineRoute(Router $router)
    {
        $router
            ->post(config('backoffice.global_url_prefix') . '/auth/login', static::class)
            ->name(static::class)
            ->middleware([
                Kernel::WEB,
                Kernel::BACKOFFICE_PUBLIC,
            ]);
    }

    public static function route()
    {
        return route(static::class);
    }
}