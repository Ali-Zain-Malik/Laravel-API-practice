<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\error;

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
            "name"      =>  "required|string|max:128|min:3",
            "email"     =>  "required|email|unique:users",
            "password"  =>  "required|min:8"
        ]);

        if($validator->passes())
        {
            $user               =   new User();
            $user->name         =   $request->name;
            $user->email        =   $request->email;
            $user->password     =   Hash::make($request->password);

            if($user->save())
            {
                return response()->json([
                    "message"   =>  "Account Created Successfully", 
                    "success"   =>  true
                ], 200);
            }
            else
            {
                return response()->json([
                    "message"   =>  "Failed to create account. An unexpected error occurred",
                    "success"   =>  false
                ], 422);
            }
        }
        else
        {
            return response()->json([
                "success"   =>  false,
                "errors"    =>  $validator->errors()
            ], 422); // 422 is used for unprocessable enitity. 
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $validator  =   Validator::make($request->all(),
        [
            "id"    =>  "required|integer"
        ]);

        if($validator->passes())
        {
            $user_id    =   $request->id;
            if($user_id)
            {
                $user   =   User::find($user_id);
                if($user)
                {
                    return response()->json([
                        "success"   =>  true,
                        "user"      =>  $user
                    ], 200);
                }
                else
                {
                    return response()->json([
                        "success"   =>  false,
                        "message"   =>  "User not found"
                    ], 404);
                }
            }
        }
        else
        {
            return response()->json([
                "success"   =>  false,
                "errors"    =>  $validator->errors()
            ], 422);
        }
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
        $idValidator  =   Validator::make(["id" => $id], [
            "id"    =>  "required|integer"
        ]);

        // Sometimes rule only checks for the field if it is present in the request.
        $validator  =   Validator::make($request->all(), [
            "name"      =>  "sometimes|required|string|max:128|min:3",
            "email"     =>  "sometimes|required|email|unique:users,email," . $id, // Excluding the current user email
            "password"  =>  "sometimes|required|min:8"
        ]);
        
        if($idValidator->fails())
        {
            return response()->json([
                "success"   =>  false,
                "message"   =>  "Invalid ID",
                "error"     =>  $idValidator->errors()
            ], 422);
        }

        $user   =   User::find($id);
        if(!$user)
        {
            return response()->json([
                "success"   =>  false,
                "message"   =>  "User not found"
            ], 404);
        }

        if($validator->fails())
        {
            return response()->json([
                "success"   =>  false,
                "message"   =>  "Validation Error",
                "error"     =>  $validator->errors()
            ], 422);
        }


        // fill method only update the fields that are present.   
        $user->fill($request->only(["name", "email", "password"]));

        if($request->has("password"))
        {
            $user->password =   Hash::make($request->password);
        }

        // Checking if any changes have been made using isClean method. This method checks if the 
        // attributes has remained unchanged or not.
        if($user->isClean())
        {
            return response()->json([
                "success"   =>  true,
                "message"   =>  "No changes were made"
            ], 200);
        }


        if($user->save())
        {
            return response()->json([
                "success"   =>  true,
                "message"   =>  "User updated successfully",
            ], 200);
        }
    }   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $idValidator  =   Validator::make(["id" => $id], [
            "id"    =>  "required|integer"
        ]);

        if($idValidator->fails())
        {
            return response()->json([
                "success"   =>  false,
                "message"   =>  "Invalid ID",
                "error"     =>  $idValidator->errors()
            ], 422);
        }

        $user   =   User::find($id);
        if(!$user)
        {
            return response()->json([
                "success"   =>  false,
                "message"   =>  "User not found"
            ], 404);
        }

        if($user->delete())
        {
            return response()->json([
                "success"   =>  true,
                "message"   =>  "User deleted successfully"
            ], 200);
        }
    }
}
