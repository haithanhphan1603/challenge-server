<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'age' => 'required|integer',
            'image' => 'required|string',
        ]);

  

        $user = User::create($request->all());
        $user->save();

        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        return $user;
    }

    public function update(Request $request, User $user)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required',
                'age' => 'required|integer',
                'image' => 'required|string',
            ]);

       
            $user->update($request->all());
            $user->save();

            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }
}