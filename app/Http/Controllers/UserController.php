<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // create user
    public function create(Request $request)
    {
        try {
            // create a instance of User
            $users = new User();
            // validate field 
            $validator = Validator::make($request->all(), [
                'name' => 'required | string | max:25 | min:7',
                'email' => 'required| max:30| email | min:10 | unique:users',
                'password' => 'required | max:12 | min:5'
            ]);
            // check validation
            if ($validator->fails()) {
                // return response in json
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                // get values
                $users->name = $request->get('name');
                $users->email = $request->get('email');
                $users->password = Hash::make($request->get('password'));
                // save user
                $result = $users->save();
                // check data is done  or not
                if ($result) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Account Create Successfully'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Some problem'
                    ]);
                }
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    // user login

    public function login(Request $request)
    {
        try {
            // create a instance of User 
            $users = new User();
            // check the validation
            $validator = Validator::make($request->all(), [
                'email' => 'required| max:30| email | min:10',
                'password' => 'required | max:12 | min:5'
            ]);
            // check validation fails
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->all(),
                ]);
            } else {
                // get user by email
                $user = $users->where('email', $request->email)->first();
                if (!$user) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid Email and Password',
                    ]);
                } else {
                    // check password
                    if (!Hash::check($request->password, $user->password)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid Email and Password',
                        ]);
                    } else {
                        $token = $user->createToken("token")->plainTextToken;
                        return response()->json([
                            "success" => true,
                            "token" => $token,
                            "message" => "Login successfully"
                        ]);
                    }
                }
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    // get User

    public function getUser(Request $request)
    {
        try {
            $user = $request->user();
            return response()->json([
                'success' => true,
                'user' => $user,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    // logout user
    public function logout(Request $request)
    {
        $id = $request->user()->id;
        auth()->user()->tokens()->where('tokenable_id', $id)->delete();
    }
}
