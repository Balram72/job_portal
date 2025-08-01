<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function authenticate(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->passes()) {
            if (Auth::attempt(['email' => $req->email, 'password' => $req->password])) {

                return redirect()->route('account.profile');
            } else {

                return redirect()->route('account.login')
                    ->with('error', 'Either Email/Password is incorrect');
            }
        } else {
            return redirect()->route('account.login')
                ->withErrors($validator)
                ->withInput($req->only('email'));
        }
    }

    public function profile()
    {
        $id = Auth::user()->id;
        $user = User::where('id', $id)->first();

        return view('front.account.profile', compact('user'));
    }

    public function updateProfile(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|min:5|max:20',
            'email' => 'required|email|unique:users,email,' . Auth::user()->id,
            'designation' => 'required|min:5|max:30',
            'mobile' => 'required|numeric|digits:10',
        ]);
        if ($validator->passes()) {

            $user = User::find(Auth::user()->id);
            $user->name = $req->name;
            $user->email = $req->email;
            $user->designation = $req->designation;
            $user->mobile = $req->mobile;

            $user->save();

            session()->flash('success', 'Profile Updated successfully.');

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

    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login');
    }
}
