<?php

namespace SON\Http\Controllers\Api;

use Illuminate\Http\Request;
use SON\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /**
     * @SWG\Info(title="School of Net - SON Financeiro API", version="0.0.1")
     */


    /**
     * Requisitar token JWT
     *
     * @SWG\POST(
     *     path="/api/login",
     *     @SWG\Parameter(
     *          name="body", in="body", required=true,
     *          @SWG\Schema(
     *              @SWG\Property(property="email", type="string"),
     *              @SWG\Property(property="password", type="string"),
     *          )
     *     ),
     *     @SWG\Response(
     *      response="200", description="Token JWT"
     *     )
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            $token = \JWTAuth::attempt($credentials);
        } catch (JWTException $ex) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        if (!$token) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }

        return response()->json(compact('token'));
    }

    /**
     * Revogar token JWT
     * @SWG\POST(
     *     path="/api/logout",
     *     @SWG\Parameter(
     *          name="Authorization", in="header", type="string", description="Bearer __token__"
     *     ),
     *     @SWG\Response(response="204", description="No content")
     * )
     */
    public function logout()
    {
        try {
            \JWTAuth::invalidate();
        } catch (JWTException $ex) {
            return response()->json(['error' => 'could_not_invalidate_token'], 500);
        }
        return response()->json([], 204);
    }

    /**
     * Renovar token JWT
     * @SWG\POST(
     *     path="/api/refresh_token",
     *     @SWG\Parameter(
     *          name="Authorization", in="header", type="string", description="Bearer __token__"
     *     ),
     *     @SWG\Response(response="200", description="Token JWT")
     * )
     */
    public function refreshToken(Request $request)
    {
        try {
            $bearerToken = \JWTAuth::setRequest($request)->getToken();
            $token = \JWTAuth::refresh($bearerToken);
        } catch (JWTException $exception) {
            return response()->json(['error' => 'could_not_refresh_token'], 500);
        }
        return response()->json(compact('token'));
    }
}
