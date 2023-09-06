<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
class AddressController extends Controller
{
    public function __construct(
        private readonly Address $address
    )
    {
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store($data)
    {
        return $this->address->newQuery()->create($data);
    }

    public function update($data, int $id){
        return $this->address->newQuery()->findOrFail($id)->update($data);
    }

    public function destroy(int $id){
        return $this->address->newQuery()->findOrFail($id)->onlyTrashed();
    }

}
