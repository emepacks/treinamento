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
        $address = $this->addresses->newQuery()->where('id', $user['address_id'])->first();
        $companies = $this->user->companies()->get();
        $user['address'] = $address;
        $user['companies'] = $companies;
        return response()->json(['user'=>$user]);
    }

    /**
     * Display the specified resource.
     */
    // TODO: Terminar de implementar
    public function showCompanies(int $id)
    {
        $user = $this->user->newQuery()->findOrFail($id);
        dd($this->user->companies());
        return response()->json(compact('user', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
      // TODO: Adicionar validação de dados
     public function update(UpdateClientRequest $request, int $id)
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
    public function destroy(int $id)
    {
        $user = $this->user->newQuery()->findOrFail($id);
        $this->address_controller->destroy($user['id']);
        $user->delete();
        return response()->json([
            'Client is removed'
        ]);
    }
}
