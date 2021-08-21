<?php

namespace App\Http\Controllers\V1\Seguridad\Usuario;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\V1\Seguridad\Usuario;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use App\Models\V1\Seguridad\Rol;
use App\Models\V1\Seguridad\UsuarioRol;

class AuthController extends ApiController
{
    use Authenticatable;

    public function __construct()
    {
        $this->middleware('auth:passport')->except(['login']);
    }

    /**
     * @OA\Post(
     *      path="/service/rest/v1/security/auth/login",
     *      operationId="postLogin",
     *      tags={"Autenticación"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Verificación de credenciales de acceso del usuario.",
     *      description="Retorna el objeto del usuario y el token.",
     *      @OA\Response(
     *          response=200,
     *          description="Respuesta correcta",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="No autenticado",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Permisos denegados"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Solicitud incorrecta"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Servicio no encontrado"
     *      ),
     *  )
     */
    public function login(Request $request)
    {

        $request->validate([
            'cui'       => 'required|string|exists:users,cui',
            'password'    => 'required|string',
        ]);

        if (!Auth::attempt(['cui' => $request->cui, 'password' => $request->password, 'deleted_at' => null])) {
            return response()->json([
                'error' => 'Las credenciales de acceso no son correctas, vuelva a intentar lo.', 'code' => '401'
            ], 401);
        }

        $http = new Client(
            [
                'verify' => false
            ]
        );

        $response = $http->post(config('services.passport.base_url') . 'service/passport/generar/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => config('services.passport.client_id'),
                'client_secret' => config('services.passport.client_secret'),
                'username' => $request->cui,
                'password' => $request->password,
                'scope' => '*',
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/security/auth/me",
     *      operationId="getUserSesión",
     *      tags={"Autenticación"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Muestra la información del usuario que se encuentra en sesión.",
     *      description="Retorna un objeto del usuario en sesión.",
     *      @OA\Response(
     *          response=200,
     *          description="Respuesta correcta",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="No autenticado",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Permisos denegados"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Solicitud incorrecta"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Servicio no encontrado"
     *      ),
     *  )
     */
    public function me(Request $request)
    {
        $user = $request->user();
        $ids = UsuarioRol::where('user_id', $user->id)->pluck('rol_id');
        $roles = Rol::select('name')->whereIn('id', $ids)->get();

        return $this->successResponse(['user' => $user, 'roles' => $roles]);
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/security/auth/logout",
     *      operationId="getLogout",
     *      tags={"Autenticación"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Muestra la información del usuario que finalizará la sesión.",
     *      description="Retorna un objeto del usuario que finalizó la sesión.",
     *      @OA\Response(
     *          response=200,
     *          description="Respuesta correcta",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="No autenticado",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Permisos denegados"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Solicitud incorrecta"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Servicio no encontrado"
     *      ),
     *  )
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return $this->showMessage("saliendo...", 200);
    }
}
