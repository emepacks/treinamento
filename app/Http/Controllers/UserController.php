<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
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
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $user = $this->user->newQuery()->where('email', $credentials['email'])->first();
        if(!$user) throw ValidationException::withMessages(
            ['email' => ['Email is incorrect.'],]
        );
        $password = Hash::check($credentials['password'], $user->password);
        if(!$password) throw ValidationException::withMessages(
            ['password' => ['Password is incorrect'],]
        );
        $address = $this->addresses->newQuery()->where('id', $user['address_id'])->first();
        $companies = $this->user->companies()->get();
        $user['address'] = $address;
        $user['companies'] = $companies;
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
    // TODO: Ideia: implementar uma função para formatar os dados para serem entregues ao cliente.
    public function signup(StoreClientRequest $request)
    {
        $data = $request->validated();
        $credentials = [
            'cpf' => $data['cpf'],
            'email' => $data['email'],
            'password' => $data['password'],
            'name' => $data['name']
        ];
        $address = [
            'cep' => $data['cep'],
            'street' => $data['street'],
            'neighborhood' => $data['neighborhood'],
            'city' => $data['city'],
            'state' => $data['state']
        ];
        $credentials['password'] = Hash::make($credentials['password']);
        $addressStored = $this->address_controller->store($address);
        $credentials['address_id'] = $addressStored['id'];
        $user = $this->user->newQuery()->create($credentials);
        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'user'=>$user,
            'token'=>$token
        ]);
    }

    public function getUser(int $id){
        $user = $this->user->newQuery()->find($id);
        if(!$user) throw ValidationException::withMessages(
            ['user' => ['User not found'],]
        );
        $address = $this->addresses->newQuery()->where('id', $user['address_id'])->first();
        $companies = $this->user->companies()->get();
        $user['address'] = $address;
        $user['companies'] = $companies;
        return response()->json(['user'=>$user]);
    }

    /**
     * Display the specified resource.
     */
    // TODO: Precisa validar no Postman
    public function showCompanies(int $id)
    {
        $user = $this->user->newQuery()->find($id);
        if(!$user) throw ValidationException::withMessages(
            ['user' => ['User not found'],]
        );
        $companies = $this->user->companies()->get();
        $data['user'] = $user['name'];
        $data['companies'] = $companies;
        return response()->json(['data'=>$data]);

    }

    /**
     * Update the specified resource in storage.
     */
     public function update(UpdateClientRequest $request, int $id)
    {
        $data = $request->validated();
        $credentials = [
            'password' => $data['password'],
            'name' => $data['name']
        ];
        $address = [
            'cep' => $data['cep'],
            'street' => $data['street'],
            'neighborhood' => $data['neighborhood'],
            'city' => $data['city'],
            'state' => $data['state']
        ];
        isset($credentials['password']) ? $credentials['password'] = Hash::make($credentials['password']) : null;
        $user = $this->user->newQuery()->find($id);

        if(!$user) throw ValidationException::withMessages(
            ['user' => ['User not found'],]
        );

        $this->address_controller->update($address,$user['address_id']);
        $user->update($credentials);

        $user['address'] = $address;
        $user['companies'] = $this->user->companies()->get();

        return response()->json([
            'user' => $user
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $user = $this->user->newQuery()->find($id);
        if(!$user) throw ValidationException::withMessages(
            ['user' => ['User not found'],]
        );
        $address = $this->address_controller->destroy($user['id']);
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully',
        ]);
    }
}
