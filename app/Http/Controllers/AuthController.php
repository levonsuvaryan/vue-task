<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Resources\User\AuthUserResource;
use App\Services\UserService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, UserService $userService)
    {
        $userService->register($request);

        return response()->json(['message' => trans('auth.register')], 201);
    }

    public function login(LoginRequest $request, Client $client)
    {
        try {
            $response = $client->post(config('spa.token_request_uri'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('spa.client_id'),
                    'client_secret' => config('spa.client_secret'),
                    'scope' => '',
                    'username' => $request->input('email'),
                    'password' => $request->input('password'),
                ]
            ]);

        } catch (RequestException $e) {
            if ($e->getCode() === 400 || $e->getCode() === 401) {
                return response()->json(['message' => trans('auth.failed')], $e->getCode());
            }

            return response()->json(['message' => trans('error.any')], $e->getCode());
        }

        $contents = json_decode($response->getBody()->getContents(), true);

        return response()->json(
            array_merge(['message' => trans('auth.login')], $contents),
            $response->getStatusCode()
        );
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();

        DB::table('oauth_access_tokens')
            ->where('id', $token->id)->delete();

        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $token->id)->delete();

        return response()->json(['message' => trans('auth.logout')]);
    }

    public function user(Request $request)
    {
        return new AuthUserResource($request->user());
    }

}
