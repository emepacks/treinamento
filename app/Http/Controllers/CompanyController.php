<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

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
    public function index()
    {
        $companies = $this->companies->all();
        return response()->json($companies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $company = $this->companies->newQuery()->findOrFail($id);
        return response()->json($company);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $company = $this->companies->newQuery()->findOrFail($id);
        $this->address_controller->destroy($company['address_id']);
        return $company->delete();

    }
}
