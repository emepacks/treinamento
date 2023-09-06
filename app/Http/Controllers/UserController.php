<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public  function  __construct(
        private User $user,
        private AddressController $address_controller,
        private Company $companies,
        private Address $addresses
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {
        $credentials = $request->only([
            'email',
            'password',
        ]);

        $user = $this->user->newQuery()->where('email', $credentials['email'])->firstOrFail();
        if(!$user) throw ValidationException::withMessages(
            ['email' => ['Email is incorrect.'],]
        );

        $password = Hash::check($credentials['password'], $user->password);
        if(!$password) throw ValidationException::withMessages(
            ['password' => ['Password is incorrect'],]
        );

        //Gerar o token de acesso
        $user->tokens()->delete();
        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'user'=>$user,
            'token'=>$token

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function signup(Request $request)
    {
        $credentials = $request->only([
            'cpf',
            'email',
            'password',
            'name'
        ]);
        $credentials['password'] = Hash::make($credentials['password']);
        $address = $request->only([
            'cep',
            'street',
            'neighborhood',
            'city',
            'state'
        ]);

        $addressStored = $this->address_controller->store($address);
        $credentials['address_id'] = $addressStored['id'];
        $user = $this->user->newQuery()->create($credentials);
        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'user'=>$user,
            'token'=>$token
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function showCompanies(int $id)
    {
        $user = $this->user->newQuery()->findOrFail($id);
        dd($user->companies());
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $credentials = $request->only([
            'cpf',
            'email',
            'password',
            'name'
        ]);
        isset($credentials['password']) ? $credentials['password'] = Hash::make($credentials['password']) : null;

        $address_data = $request->only([
            'cep',
            'street',
            'neighborhood',
            'city',
            'state'
        ]);

        $user = $this->user->newQuery()->findOrFail($id);
        $user->update($credentials);
        $this->address_controller->update($address_data,$user['address_id'] );

        return response()->json([
            'Client updated success'
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = $this->user->newQuery()->findOrFail($id);
        $this->address_controller->destroy($user['id']);
        return response()->json([
            'Client is removed'
        ]);

    }
}
