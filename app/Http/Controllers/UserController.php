<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class UserController extends BaseController
{
    public function index(Request $request)
    {
        try {
            $sortColumn = $request->query("sort","name");
            $sortDirection = $request->query("order","asc");
            $users = User::orderBy($sortColumn, $sortDirection)->get();

            return $this->sendResponse($users->toArray(), 'Users fetched successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Error fetching users: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'age' => 'required|integer',
                'image' => 'required|string',
                'group_id' => 'required|exists:groups,id', // ensure the group exists
            ]);

            $user = User::create($request->all());

            return $this->sendResponse($user->toArray(), 'User created successfully.');
        } catch (ValidationException $e) {
            return $this->sendError('Validation Error.', $e->errors());
        } catch (\Exception $e) {
            return $this->sendError('Error creating user: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return $this->sendResponse($user->toArray(), 'User fetched successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('User not found', [], 404);
        } catch (\Exception $e) {
            return $this->sendError('Error fetching user: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email,'.$id,
                'age' => 'required|integer',
                'image' => 'required|string'
            ]);

            $user = User::findOrFail($id);
            $user->update($request->all());

            return $this->sendResponse($user->toArray(), 'User updated successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('User not found', [], 404);
        } catch (ValidationException $e) {
            return $this->sendError('Validation Error.', $e->errors());
        } catch (\Exception $e) {
            return $this->sendError('Error updating user: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return $this->sendResponse([], 'User deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('User not found', [], 404);
        } catch (\Exception $e) {
            return $this->sendError('Error deleting user: ' . $e->getMessage());
        }
    }
    
    public function search(Request $request) {
     try {
        $search = $request->query('search');
        $users = User::where('name', 'LIKE', "%{$search}%")->get();
        return $this->sendResponse($users->toArray(), 'Users fetched successfully.');
     } catch (\Exception $e) {
        return $this->sendError('Error fetching users: ' . $e->getMessage());
    }
}
}