<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // List all users
    public function index(Request $request)
    {
        $search = $request->input('search');
        $users = \App\Models\User::with(['country', 'role'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%")
                      ->orWhereHas('country', function ($cq) use ($search) {
                          $cq->where('name', 'like', "%$search%") ;
                      });
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->appends(['search' => $search]);
        return view('admin.users.index', compact('users', 'search'));
    }

    // Show create form
    public function create()
    {
        $countries = \App\Models\Country::all();
        $roles = \App\Models\Role::all();
        return view('admin.users.create', compact('countries', 'roles'));
    }

    // Store new user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'country_id' => 'required|exists:countries,id',
            'role_id' => 'required|exists:roles,id',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $validated['password'] = bcrypt($validated['password']);
        \App\Models\User::create($validated);
        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $countries = \App\Models\Country::all();
        $roles = \App\Models\Role::all();
        return view('admin.users.edit', compact('user', 'countries', 'roles'));
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'country_id' => 'required|exists:countries,id',
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        if ($validated['password']) {
            $user->password = bcrypt($validated['password']);
        }
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->country_id = $validated['country_id'];
        $user->role_id = $validated['role_id'];
        $user->save();
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    // Delete user
    public function destroy($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
