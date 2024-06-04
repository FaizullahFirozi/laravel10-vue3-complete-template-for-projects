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
        // $this->authorize('test-read');
        $perPage = request('per_page', 2);
        $crud_data = CrudTest::latest()->paginate($perPage);

        return response()->json($crud_data);
    }


    public function search()
    {

        $searchQuery = request('query', '');
        $perPage = request('per_page', 10);


        $crud_data = CrudTest::query()
            ->where('name', 'like', "%{$searchQuery}%")
            ->orWhere('email', 'like', "%{$searchQuery}%")
            ->orWhere('phone', 'like', "%{$searchQuery}%")
            ->orderBy('id', 'desc')
            ->paginate($perPage);

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

        $dataValide = $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:crud_tests,phone|min:10|max:10',
            'email' => 'required|unique:crud_tests,email|max:128',
            'description' => 'required|min:2',
        ]);


        if ($dataValide) {

            $data = $request->all();
            $record = CrudTest::create($data);

            return response()->json($record);


            // $crudTest = CrudTest::create([ // if you like this mode
            //     'name' => request('name'),
            //     'email' => request('email'),
            //     'phone' => request('phone'),
            //     'description' => request('description'),
            // ]);
            // return response()->json($crudTest);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $dataValide = $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:crud_tests,phone,' . $id, '|min:10|max:10',
            'email' => 'required|unique:crud_tests,email,' . $id, '|max:128',
            'description' => 'required|min:2',
        ]);


        if ($dataValide) {
            $data = $request->all();
            $record = CrudTest::findOrFail($id);
            $record->update($data);

            return response()->json($record);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $record = CrudTest::findOrFail($id);
        $record->delete();

        return response()->noContent();
    }
}
