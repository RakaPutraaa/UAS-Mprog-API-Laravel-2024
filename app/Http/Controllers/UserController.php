<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    // Register
    public function register(Request $request) {
        try {
            // validasi inputan user
            $validated = $request->validate([
                'nama_user' => 'required|max:100',
                'email_user' => 'required|email|unique:users,email_user',
                'password_user' => 'required'
            ]);

            // hasing terhadap password
            $hashed = Hash::make($request->password_user);

            // insert data ke db
            $user = User::create([
                'nama_user' => $request->nama_user,
                'email_user' => $request->email_user,
                'password_user' => $hashed
            ]);

            // kembalikan data sesuai di UserResource
            return response()->json([
                'message' => 'success',
                // 'status' => 200,
                'user' => new UserResource($user),
            ], 200);

        }catch(QueryException $e) {
            // kembalikan pesan error
            return response()->json([
                'message' => 'Terjadi Kesalahan, Silahkan coba lagi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Login
    public function login(Request $request) {
        try {
            // validasi inputan user
            $validated = $request->validate([
                'email_user' => 'required|email',
                'password_user' => 'required'
            ]);

            // cek email di db
            $user = User::where('email_user', $request->email_user)->first();

            // cek hash password apakah sama ?
            if(!$user || !Hash::check($request->password_user, $user->password_user)) {
                return response()->json([
                    'message' => "Email atau Password salah"
                ], 401);
            }

            // Token untuk user
            $token = $user->createToken('UserLoginToken')->plainTextToken;

            // respon json
            return response()->json([
                'message' => 'success',
                'user' => new UserResource($user),
                'token' => $token
            ],200);

        }catch(QueryException $e) {
            // respon json
            return response()->json([
                'message' => 'Terjadi Kesalahan, Silahkan coba lagi.',
                'error' => $e->getMessage()
            ],500);
        }

    }
}
