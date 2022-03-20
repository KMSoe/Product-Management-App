<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required| email | unique:users',
            'password' => 'required',
            'image' => 'image | mimes:png,jpg,jpeg',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errors" => $validator->errors(),
            ], 400);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $success = $user->save();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imagePath = $request->file('image');
            $imageName = ucwords(explode(" ", $user->name)[0]) . $user->id . time() . "." . $imagePath->extension();

            $path = $request->file('image')->storeAs('profiles', $imageName, 'public');

            $user->image = $imageName;
            $success = $user->save();
        }

        $token = $user->createToken('LaravelAuth')->accessToken;

        if ($success) {
            return response()->json([
                "success" => true,
                "id" => $user->id,
                "data" => ["user" => $user, "token" => $token],
            ], 201);
        } else {
            return response()->json([
                "success" => false,
                "error" => [
                    "message" => ["Error!!!"],
                ],
            ], 500);
        }
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errors" => $validator->errors(),
            ], 400);
        }
       ;
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = $request->user();
            $token = $user->createToken('LaravelAuth')->accessToken;

            return response()->json([
                "success" => true,
                "id" => $user->id,
                "data" => ["user" => $user, "token" => $token],
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "errors" => [
                    "message" => ["Incorrect Email or Password"]
                ],
            ], 400);
        }
    }
    public function logout(Request $request) {
        $token = $request->user()->token();
        $tokens = $request->user()->tokens->pluck('id');
        Token::whereIn('id', $tokens)->update(['revoked' => true]);
        RefreshToken::whereIn('access_token_id', $tokens)->update(['revoked' => true]);
        $token->revoke();

        return response()->json([
            "success" => true,
            "error" => [
                "message" => 'Successfully logout'
            ],
        ], 204);
    }
    public function getUser(Request $request) {
        return response()->json([
            "success" => true,
            "data" => [
                "user" => $request->user()
            ],
        ], 200);;
    }
}