<?php

namespace App\Http\Controllers\Auth;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{


    use RegistersUsers;

    protected string $redirectTo = '/';


    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @return JsonResponse|mixed
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            ...$request->validated(),
            'password' => Hash::make($request->get('password')),
        ]);

        $user->assignRole(RoleEnum::CUSTOMER->value);

        event(new Registered($user));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : $this->redirect($this->redirectPath());
    }
}
