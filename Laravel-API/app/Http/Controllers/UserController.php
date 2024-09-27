<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            "message"   =>  "Index function called"
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator  =   Validator::make($request->all(), [
            "name"  =>  "required|string|max:128|min:3",
            "email" =>  "required|email|unique:users",
            "password"  =>  "required|min:8"
        ]);

        if($validator->passes())
        {
            $user   =   new User();
            $user->name =   $request->name;
            $user->email    =   $request->email;
            $user->password     =   Hash::make($request->password);

            if($user->save())
            {
                return response()->json([
                    "message"   =>  "Account Created Successfully", 
                    "success"   =>  true
                ], 200);
            }
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
