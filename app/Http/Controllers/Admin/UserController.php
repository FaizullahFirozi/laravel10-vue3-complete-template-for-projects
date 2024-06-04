<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $users = User::latest()
        ->orderBy('id', 'desc')
        ->paginate($perPage);

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

        $dataValide = $request->validate([
            'name' => 'required',
            'last_name' => 'required',
            'father_name' => 'required',
            'nic' => 'unique:users,nic',
            'hire_date' => 'date',
            // 'dob' => 'date',
            'phone' => 'unique:users,phone|min:10',
            'email' => 'unique:users,email|max:128',
        ]);


        if ($dataValide) {

            $users = User::create([
                'name' => $request->name,
                'last_name' => request('last_name'),
                'father_name' => request('father_name'),
                'dob' => request('dob'),
                'nic' => request('nic'),
                'hire_date' => request('hire_date'),
                'gross_salary' => request('gross_salary'),
                'phone' => request('phone'),
                // 'avatar' => request('avatar'),
                'account_status' => 0,
                'email' => request('email'),
                'password' => bcrypt(request('password')),
            ]);

            return response()->json($users);
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
    public function update(Request $request, $id)
    {

        $dataValide = request()->validate([
            'name' => 'required',
            'last_name' => 'required',
            'father_name' => 'required',
            'nic' => 'required',
            'phone' => 'required|unique:users,phone,' . $id, '|min:10',
            'email' => 'required|unique:users,email,' . $id, '|max:128',
        ]);


        if ($dataValide) {
         
            $employee = User::findOrFail($id);
            $employee->name = $request->name;
            $employee->last_name = $request->last_name;
            $employee->father_name = $request->father_name;
            $employee->dob = $request->dob;
            $employee->nic = $request->nic;
            $employee->hire_date = $request->hire_date;
            $employee->gross_salary = $request->gross_salary;
            $employee->phone = $request->phone;
            // $employee->avatar = $request->avatar;
            $employee->account_status = 0;
            // $employee->account_status = (Auth::user()->id == $id) ? User::find($id)->account_status : $request->account_status;
            $employee->email = $request->email;
            $employee->password = $request->password ? bcrypt($request->password) : $employee->password;
           
            $employee->update();


            return response()->json($employee);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // return 'ruojsdlkf';
        $this->authorize('user-delete');

        $user->delete();
        return response()->noContent();
    }
}
