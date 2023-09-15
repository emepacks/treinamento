<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Address;
use App\Models\Company;
use App\Notifications\UserNotification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public  function  __construct(
        private User $user,
        private AddressController $address_controller,
        private Company $companies,
        private Address $addresses,
        private Storage $disk
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $user = $this->user->newQuery()->where('email', $credentials['email'])->first();
        if (!$user) return response()->json('Email is incorrect.', Response::HTTP_NOT_FOUND);
        $password = Hash::check($credentials['password'], $user->password);
        if (!$password) return response()->json('Password is incorrect.', Response::HTTP_NOT_FOUND);

        $user['address'] = $user->address()->first();
        $user['companies'] =  $user->companies()->get();
        $user['companies']->map(function ($company) {
            $company['address'] = $company->address()->get();
        });
        //Gerar o token de acesso
        $user->tokens()->delete();
        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'token' => $token
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    // TODO: Ideia: implementar uma função para formatar os dados para serem entregues ao cliente.
    public function signup(StoreClientRequest $request): JsonResponse
    {
        $data = $request->validated();

        $credentials = [
            'cpf' => $data['cpf'],
            'email' => $data['email'],
            'password' => $data['password'],
            'name' => $data['name'],
            'photo_profile' => $request->file('photo_profile')
        ];
        $address = [
            'cep' => $data['cep'],
            'street' => $data['street'],
            'neighborhood' => $data['neighborhood'],
            'city' => $data['city'],
            'state' => $data['state']
        ];

        if ($credentials['photo_profile']) {
            $credentials['photo_profile'] = Storage::put('/public/profile', $credentials['photo_profile']);
        }
        $credentials['password'] = Hash::make($credentials['password']);
        $addressStored = $this->address_controller->store($address);
        $credentials['address_id'] = $addressStored['id'];

        $user = $this->user->newQuery()->create($credentials);

        $token = $user->createToken('token')->plainTextToken;

        $user->notify(new UserNotification());

        return response()->json([
            'message' => 'User created successfully',
            'token' => $token,
        ],);
    }

    public function getUser(): JsonResponse
    {
        $user = $this->user->newQuery()->find(Auth::user()->getAuthIdentifier());
        if (!$user) return response()->json('User not fund!', Response::HTTP_NOT_FOUND);
        $user['address'] = $user->address()->get();
        $user['companies'] = $user->companies()->get();
        $user['companies']->map(function ($company) {
            $company['address'] = $company->address()->get();
        });
        return response()->json(['user' => $user], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function showCompanies(): JsonResponse
    {
        $user = $this->user->newQuery()->find(Auth::user()->getAuthIdentifier());
        if (!$user) return response()->json('User not fund!', Response::HTTP_NOT_FOUND);
        $companies = $user->companies()->get();
        $companies->map(function ($company) {
            $company['address'] = $company->address()->get();
        });
        $data['user'] = $user['name'];
        $data['companies'] = $companies;
        return response()->json(['data' => $data], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request): JsonResponse
    {
        $data = $request->validated();

        $credentials = [
            'password' => $data['password'],
            'name' => $data['name'],
        ];
        $address = [
            'cep' => $data['cep'],
            'street' => $data['street'],
            'neighborhood' => $data['neighborhood'],
            'city' => $data['city'],
            'state' => $data['state']
        ];
        $user = $this->user->newQuery()->find(Auth::user()->getAuthIdentifier());
        if (!$user) return response()->json('User not fund!', Response::HTTP_NOT_FOUND);

        if ($credentials['password']) {
            $credentials['password'] = Hash::make($credentials['password']);
        }else{
            $credentials['password'] = $user->password;
        }

        $user->address()->update($address);
        $user->update($credentials);

        return response()->json([
            'message' => 'User updated successfully'
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(): JsonResponse
    {
        $user = $this->user->newQuery()->find(Auth::user()->getAuthIdentifier());
        if (!$user) return response()->json('User not fund!', Response::HTTP_NOT_FOUND);

        $user->address()->delete();
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully',
        ], Response::HTTP_NO_CONTENT);
    }
}
