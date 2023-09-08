<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CompanyController extends Controller
{
    public function __construct(
        private Company $companies,
        private AddressController $address_controller
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    // TODO: Editar para trazer os enderecos das empresas
    public function index()
    {
        $companies = $this->companies->all();
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
        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }
        return response()->json($company);
    }

    /**
     * Update the specified resource in storage.
     */

     // TODO: descobrir pq a senha esta sendo enviada junto com os dados
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

        $this->address_controller->update($address,$company['address_id']);
        $company->update($credentials);

        $company['address'] = $address;
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
        $this->address_controller->destroy($company['address_id']);
        $company->delete();
        return response()->json(['message' => 'Company deleted successfully']);

    }
}
