<?php

namespace App\Http\Controllers;

use App\Models\CrudTest;
use Illuminate\Http\Request;

class CrudTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $crud_data = CrudTest::all();
        return response()->json($crud_data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CrudTest $crudTest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CrudTest $crudTest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CrudTest $crudTest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CrudTest $crudTest)
    {
        //
    }
}
