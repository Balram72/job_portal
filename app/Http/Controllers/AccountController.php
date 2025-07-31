<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    // This method will show  user registration page
    public function registration()
    {
        return view('front.account.registration');
    }
    // this method will process user registration
    public function processRegistration(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5',
            'confirm_password' => 'required|same:password'
        ]);

        if ($validator->passes()) {

            $user = new User();
            $user->name = $req->name;
            $user->email = $req->email;
            $user->password = Hash::make($req->password);
            $user->save();

            session()->flash('success', 'You have Registerd successfully.');

            // $this->success('You have Registerd successfully.');

            return response()->json([
                'status' => true,
                'errors' => [],
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    // This method will show user login page
    public function login()
    {
        return view('front.account.login');
    }
}
