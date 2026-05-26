<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAccount;
use Illuminate\Support\Facades\Hash;

class UserAccountController extends Controller
{
    // ACTIVE USERS ONLY
    public function index()
    {
        return response()->json(
            UserAccount::whereNull('deleted_at')->get()
        );
    }

    // TRASH USERS
    public function trash()
    {
        return response()->json(
            UserAccount::onlyTrashed()->get()
        );
    }

    // CREATE USER
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:user_account,username',
            'email' => 'required|email|unique:user_account,email',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        $user = UserAccount::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,

            // HASH PASSWORD
            'password' => Hash::make($request->password),

            'role' => $request->role
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'data' => $user
        ]);
    }

    // SOFT DELETE
    public function destroy($id)
    {
        $user = UserAccount::findOrFail($id);

        $user->delete();

        return response()->json([
            'message' => 'Moved to trash'
        ]);
    }

    // RESTORE USER
    public function restore($id)
    {
        $user = UserAccount::onlyTrashed()->findOrFail($id);

        $user->restore();

        return response()->json([
            'message' => 'User restored'
        ]);
    }

    // FORCE DELETE
    public function forceDelete($id)
    {
        $user = UserAccount::onlyTrashed()->findOrFail($id);

        $user->forceDelete();

        return response()->json([
            'message' => 'Deleted permanently'
        ]);
    }
}