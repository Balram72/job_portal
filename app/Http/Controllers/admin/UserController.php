<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.users.list', compact('users'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update($id, Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|min:5|max:20',
            'email' => 'required|email|unique:users,email,' . $id . 'id',
            'designation' => 'required|min:5|max:30',
            'mobile' => 'required|numeric|digits:10',
        ]);
        if ($validator->passes()) {

            $user = User::find($id);
            $user->name = $req->name;
            $user->email = $req->email;
            $user->designation = $req->designation;
            $user->mobile = $req->mobile;

            $user->save();

            session()->flash('success', 'User Information Updated successfully.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
}
