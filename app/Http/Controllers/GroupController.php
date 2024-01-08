<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GroupController extends BaseController
{
    public function index(Request $request)
    {
        try {
            $queryColumn = $request->query("sort", "name");
            $queryDirection = $request->query("order", "asc");
            $groups = Group::orderBy($queryColumn, $queryDirection)->get();

            return $this->sendResponse($groups->toArray(), 'Groups fetched successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Error fetching groups: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
            ]);

            $group = Group::create($request->all());

            return $this->sendResponse($group->toArray(), 'Group created successfully.');
        } catch (ValidationException $e) {
            return $this->sendError('Validation Error.', $e->errors());
        } catch (\Exception $e) {
            return $this->sendError('Error creating group: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $group = Group::with('users')->findOrFail($id);

            return $this->sendResponse($group->toArray(), 'Group fetched successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Group not found', [], 404);
        } catch (\Exception $e) {
            return $this->sendError('Error fetching group: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required',
            ]);

            $group = Group::findOrFail($id);
            $group->update($request->all());

            return $this->sendResponse($group->toArray(), 'Group updated successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Group not found', [], 404);
        } catch (ValidationException $e) {
            return $this->sendError('Validation Error.', $e->errors());
        } catch (\Exception $e) {
            return $this->sendError('Error updating group: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $group = Group::findOrFail($id);
            $group->delete();

            return $this->sendResponse([], 'Group deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Group not found', [], 404);
        } catch (\Exception $e) {
            return $this->sendError('Error deleting group: ' . $e->getMessage());
        }
    }
     public function search(Request $request) {
     try {
        $search = $request->query('search');
        $groups = Group::where('name', 'LIKE', "%{$search}%")->get();
        return $this->sendResponse($groups->toArray(), 'Groups fetched successfully.');
     } catch (\Exception $e) {
        return $this->sendError('Error fetching Groups: ' . $e->getMessage());
    }
}
}