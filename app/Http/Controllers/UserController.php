<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class User extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function signup(Request $request)
    {
        $credentials = $request->only([
            'email',
            'password',
            'cpf'
        ]);
        $user =
    }

    /**
     * Display the specified resource.
     */
    public function showCompanies(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}