<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;


class UserController extends Controller
{


    // public function showIpAddress(Request $request)
    // {
    //     //ONLY FOR SHOWING IP AND MAC ADDRESS
    //     $ipAddress = $request->ip();
    //     $macAddress = exec('getmac');
    //     return "IP Address: " . $ipAddress . "Mac Address: " . $macAddress;
    // }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $this->authorize('test-read');

        // $users = User::latest()->get();

        $perPage = request('per_page', 10);
        $users = User::latest()->paginate($perPage);
   
        return response()->json($users);
    }



    public function search()
    {

        $searchQuery = request('query', '');
        $perPage = request('per_page', 10);


        $users = User::query()
            ->where('name', 'like', "%{$searchQuery}%")
            ->orWhere('email', 'like', "%{$searchQuery}%")
            ->orWhere('phone', 'like', "%{$searchQuery}%")
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        return response()->json($users);



        // $perPage = request('per_page', 10);
        // $search = request('search', '');
        // $sortField = request('sort_field', 'updated_at');
        // $sortDirection = request('sort_direction', 'desc');

        // $users = User::query()
        // ->where('name', 'like', "%{$search}%")
        // ->orderBy($sortField, $sortDirection)
        // ->paginate($perPage);

        // return $users;


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

        $dataValide = request()->validate([
            'name' => 'required',
            'last_name' => 'required',
            'father_name' => 'required',
            'nic' => 'required|unique:users,nic',
            'hire_date' => 'required',
            'phone' => 'required|unique:users,phone|min:10',
            'email' => 'required|unique:users,email|max:128',
        ]);


        if ($dataValide) {

            return User::create([
                'name' => request('name'),
                'last_name' => request('last_name'),
                'father_name' => request('father_name'),
                'dob' => 2011,
                'nic' => request('nic'),
                'hire_date' => request('hire_date'),
                'gross_salary' => 12000,
                'phone' => request('phone'),
                'photo' => 'photo',
                'account_status' => 0,
                'email' => request('email'),
                'password' => bcrypt(request('password')),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(User $user)
    {

        $dataValide = request()->validate([
            'name' => 'required',
            'last_name' => 'required',
            'father_name' => 'required',
            'nic' => 'required',
            'hire_date' => 'required',
            'phone' => 'required|unique:users,phone, ' . $user->id, '|min:10',
            'email' => 'required|unique:users,email, ' . $user->id, '|max:128',
        ]);


        if ($dataValide) {

            $user->update([
                'name' => request('name'),
                'last_name' => request('last_name'),
                'father_name' => request('father_name'),
                'dob' => 2011,
                'nic' => request('nic'),
                'hire_date' => request('hire_date'),
                'gross_salary' => 12000,
                'phone' => request('phone'),
                'photo' => 'photo',
                'account_status' => 0,
                'email' => request('email'),
                'password' => request('password') ? bcrypt(request('password')) : $user->password,
            ]);

            return $user;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('user-delete');

        $user->delete();
        return response()->noContent();
    }
}
