<?php

namespace app\Http\Controllers;

use Backpack\CRUD\app\Library\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    protected ?string $redirectTo = null;

    protected $data = []; // the information we send to the view

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    use RegistersUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $guard = backpack_guard_name();

        $this->middleware("guest:$guard");

        // Where to redirect users after login / registration.
        $this->redirectTo = '/';
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $user_model_fqn = config('backpack.base.user_model_fqn');
        $user = new $user_model_fqn();
        $users_table = $user->getTable();
        $email_validation = backpack_authentication_column() == 'email' ? 'email|' : '';

        return Validator::make($data, [
            'fullname' => 'required|max:255',
            backpack_authentication_column() => 'required|'.$email_validation.'max:255|unique:'.$users_table,
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    protected function create(array $data)
    {
        $user_model_fqn = config('backpack.base.user_model_fqn');
        $user = new $user_model_fqn();

        return $user->create([
            'fullname' => $data['fullname'],
            backpack_authentication_column() => $data[backpack_authentication_column()],
            'password' => Hash::make($data['password']),
            'api_token' => Str::random(80),
            'photo' => $data['photo'],
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        // if registration is closed, deny access
        if (! config('backpack.base.registration_open')) {
            abort(403, trans('backpack::base.registration_closed'));
        }

        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        event(new Registered($user));
        if (config('backpack.base.setup_email_verification_routes')) {
            Cookie::queue('backpack_email_verification', $user->{config('backpack.base.email_column')}, 30);

            return new JsonResponse(['message' => 'Need email verification!']);
        }

        $this->guard()->login($user);

        return new JsonResponse(['message' => 'Successfully Registered!']);
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return backpack_auth();
    }
}
