<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CompanyController extends Controller
{
    public function __construct(
        private Company $companies,
        private AddressController $address_controller,
        private User $users
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = $this->companies->all();
        $companies->map(function ($company) {
            $company['address'] = $company->address()->get();
            $company['users'] = $company->user()->get();
            $company['user']->map(function ($user) {
                $user['address'] = $user->address()->get();
            });
        });
        return response()->json($companies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request)
    {
        $data = $request->validated();
        $company = [
            'cnpj' => $data['cnpj'],
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
        $company['password'] = Hash::make($company['password']);
        $addressStored = $this->address_controller->store($address);
        $company['address_id'] = $addressStored['id'];
        $company = $this->companies->newQuery()->create($company);
        $token = $company->createToken('token')->plainTextToken;
        return response()->json([
            'message'=>'Company created successfully',
            'token' => $token
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $company = $this->companies->newQuery()->find($id);
        $company['address'] = $company->address()->get();
        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }
        return response()->json($company);
    }

    /**
     * Display the specified resource.
     */
    public function showClients(int $id)
    {
        $company = $this->companies->newQuery()->find($id);
        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }
        $company['users'] = $company->user()->get();
        $company['address'] = $company->address()->get();
        return response()->json($company);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(UpdateCompanyRequest $request, int $id)
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
        $company = $this->companies->newQuery()->find($id);

        if(!$company) throw ValidationException::withMessages(
            ['company' => ['Company not found'],]
        );

        $company->address()->update($address);
        $company->update($credentials);

        $company['address'] = $company->address()->get();
        $company['users'] = $this->companies->user()->get();

        return response()->json([
            'company' => $company
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $company = $this->companies->newQuery()->find($id);
        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }
        $company->address()->delete();
        $company->delete();
        return response()->json(['message' => 'Company deleted successfully']);

    }
    public function addClient (Request $request, int $id){
        $data = $request->validate([
            'cpf' => 'required|string|size:11',
        ]);
        $company = $this->companies->newQuery()->find($id);
        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }
        $user = $this->users->newQuery()->where('cpf', $data['cpf'])->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $company->user()->attach($user);
        return response()->json(['message' => 'User added successfully']);
    }
}
