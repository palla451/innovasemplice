<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\TokenManagement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    use TokenManagement;


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $login = $request->input('username');
        $user = User::where('username', $login)->first();

        if (!$user) {
            return response()->json(['error' => 'username o email invalid'],401);
        }

        if (Auth::attempt(['username' => $user->username, 'password' => $request->password])) {

            $osClient = DB::table('oauth_clients')->where('id',2)->first();
            $response = $this->getTokenRefreshToken($user->email, $request->password, $osClient);
            $response['user'] = $user;

            return response()->json($response);
        } else {
            return response()->json(['error' => 'invalid credentials'], 401);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function refresh_token(Request $request): array
    {
        $refresh_token = $request->refresh_token;
        $osClient = DB::table('oauth_clients')->where('id',2)->first();
        return $this->getRefreshToken($refresh_token, $osClient);
    }

    /**
     * @return JsonResponse
     */
    public function logged(): JsonResponse
    {
        return response()->json(Auth::user());
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $user = Auth::user();
        $user->tokens()->delete();
        return response()->json([
            'message' => 'Logout successfully'
        ]);
    }
}
