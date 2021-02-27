<?php

namespace App\Http\Controllers\V1\Seguridad\Usuario;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\V1\Seguridad\Usuario;
use App\Models\V1\Catalogo\Municipio;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\ApiController;
use App\Models\V1\Seguridad\UsuarioRol;
use Illuminate\Support\Facades\Storage;

class UsuarioController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/security/user",
     *      operationId="getUsers",
     *      tags={"Usuario"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Muestra todos los usuarios registrados en la base de datos.",
     *      description="Retorna un array de usuarios.",
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
    public function index()
    {
        $users = Usuario::with('departament', 'municipality', 'rols.rol')->withTrashed()->orderByDesc('id')->get();
        return $this->showAll($users);
    }

    /**
     * @OA\Post(
     *      path="/service/rest/v1/security/user",
     *      operationId="postUsers",
     *      tags={"Usuario"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Crear un nuevo usuario en el sistema.",
     *      description="Retorna el objeto del usuario creado.",
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
    public function store(Request $request)
    {
        $this->validate($request, $this->rules(), $this->messages());

        try {
            $data = $request->all();
            $data['admin'] = Usuario::USUARIO_REGULAR;
            $data['departament_id'] = Municipio::find($request->municipality_id['id'])->departament_id;
            $data['municipality_id'] = $request->municipality_id['id'];
            $data['password'] = base64_decode($request->password);

            if (!is_null($request->photo)) {
                $img = $this->getB64Image($request->photo);
                $image = Image::make($img);
                $image->fit(870, 620, function ($constraint) {
                    $constraint->upsize();
                });
                $image->encode('jpg', 70);
                $path = "{$request->cui}.jpg";
                Storage::disk('user')->put($path, $image);
                $data['photo'] = $path;
            }

            DB::beginTransaction();

            $user = Usuario::create($data);

            foreach ($request->roles as $value) {
                UsuarioRol::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'rol_id' => $value['id']
                    ]
                );
            }

            DB::commit();

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 423);
        }
    }

    /**
     * @OA\Post(
     *      path="/service/rest/v1/security/user_password",
     *      operationId="postUserPassword",
     *      tags={"Usuario"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Cambiar la contraseña del usuario seleccionado.",
     *      description="Retorna el objeto del usuario que cambio la contraseña.",
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
    public function cambiar_password(Request $request)
    {
        try {
            $user = Usuario::find($request->id);
            $user->password = base64_decode($request->password);
            $user->save();

            return $this->successResponse('Contraseña actualizada.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * @OA\Put(
     *      path="/service/rest/v1/security/user/{user}",
     *      operationId="updateUser",
     *      tags={"Usuario"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Actualizar el usuario seleccionado.",
     *      description="Retorna el objeto del usuario actualizado.",
     *      @OA\Parameter(
     *          description="ID del usuario para actualizar",
     *          in="path",
     *          name="user",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Respuesta correcta",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *          )
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
    public function update(Request $request, Usuario $user)
    {
        $this->validate($request, $this->rules($user->id), $this->messages());

        try {
            $user->cui = $request->cui;
            $user->first_name = $request->first_name;
            $user->second_name = $request->second_name;
            $user->surname = $request->surname;
            $user->second_surname = $request->second_surname;
            $user->married_name = $request->married_name;
            $user->email = $request->email;
            $user->observation = $request->observation;
            $user->ubication = $request->ubication;
            $user->phone = $request->phone;
            $user->departament_id = Municipio::find($request->municipality_id['id'])->departament_id;
            $user->municipality_id = $request->municipality_id['id'];

            if (!is_null($request->photo)) {
                Storage::disk('user')->exists($user->photo) ? Storage::disk('user')->delete($user->photo) : null;

                $img = $this->getB64Image($request->photo);
                $image = Image::make($img);
                $image->fit(870, 620, function ($constraint) {
                    $constraint->upsize();
                });
                $image->encode('jpg', 70);
                $path = "{$request->cui}.jpg";
                Storage::disk('user')->put($path, $image);
                $user->photo = $path;
            }

            if(!$user->isDirty())
                $this->errorResponse('No hay datos para actualizar', 423);

            $user->save();
            
            return $this->successResponse('Registro actualizado.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * @OA\Delete(
     *      path="/service/rest/v1/security/user/{user}",
     *      operationId="deleteUser",
     *      tags={"Usuario"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Eliminar o cambiar el estado del usuario seleccionado.",
     *      description="Retorna el objeto del usuario eliminado.",
     *      @OA\Parameter(
     *          description="ID del usuario para eliminar",
     *          in="path",
     *          name="user",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *          )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Respuesta correcta",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *          )
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
    public function destroy($user)
    {
        $user = Usuario::withTrashed()->find($user);
        if (is_null($user->deleted_at)) {
            $user->delete();
            $message = 'descativado';
        } else {
            $user->restore();
            $message = 'activado';
        }

        return $this->successResponse("Registro {$message}");
    }

    //Reglas de validaciones
    public function rules($id = null)
    {
        $validar = [
            'cui' => is_null($id) ? 'required|numeric|digits_between:13,15|unique:users,cui' : "required|numeric|digits_between:13,15|unique:users,cui,{$id}",
            'first_name' => 'required|max:50',
            'second_name' => 'nullable|max:50',
            'surname' => 'required|max:50',
            'second_surname' => 'nullable|max:50',
            'married_name' => 'nullable|max:50',
            'email' => is_null($id) ? 'required|email|max:75|unique:users,email' : "required|email|max:75|unique:users,email,{$id}",
            'password' => is_null($id) ? 'required|min:6' : '',
            'observation' => 'nullable|max:500',
            'ubication' => 'nullable|max:100',
            'phone' => 'required|max:25',
            'municipality_id.id' => 'required|integer|exists:municipalities,id',
            'photo' => 'nullable',
            'roles.*.id' => is_null($id) ? 'required|integer|exists:rols,id' : ''
        ];

        return $validar;
    }

    //Mensajes para las reglas de validaciones
    public function messages()
    {
        return [
            'cui.required' => 'El número de CUI es obligatorio.',
            'cui.numeric' => 'El número de CUI tiene formato incorrecto.',
            'cui.digits_between'  => 'El número de CUI ingresado no tiene un mínimo de :min y un máximo de :max dígitos.',
            'cui.unique'  => 'El número de CUI ingreado ya existe en el sistema.',

            'first_name.required' => 'El primer nombre es obligatorio.',
            'first_name.max'  => 'El primer nombre debe tener menos de :max carácteres.',

            'second_name.max'  => 'El segundo nombre debe tener menos de :max carácteres.',

            'surname.required' => 'El primer apellido es obligatorio.',
            'surname.max'  => 'El primer apellido debe tener menos de :max carácteres.',

            'second_surname.max'  => 'El segundo apellido debe tener menos de :max carácteres.',

            'married_name.max'  => 'El apellido de casad@ debe tener menos de :max carácteres.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El dato ingresado no es un correo electrónico.',
            'email.max'  => 'El correo electrónico debe tener menos de :max caracteres.',
            'email.unique'  => 'El correo electrónico ingresado ya existe en el sistema.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.min'  => 'La contraseña debe tener más de :min carácteres.',

            'observation.max'  => 'La observación de de tener menos de :max carácteres.',

            'ubication.max'  => 'La ubicación de de tener menos de :max carácteres.',

            'phone.required' => 'El número de teléfono es obligatorio.',
            'phone.numeric' => 'El número de teléfono tiene formato incorrecto.',
            'phone.digits_between'  => 'El número de teléfono ingresado no tiene un 8 dígitos.',
            'phone.max'  => 'El número de teléfono debe tener menos de :max carácteres.',

            'municipality_id.id.required' => 'El departamento y muicipalidad es obligatorio',
            'municipality_id.id.integer' => 'El departamento y muicipalidad no es un número',
            'municipality_id.id.exists' => 'El departamento y muicipalidad no existe en la base de datos',

            'roles.*.id.required' => 'El rol es obligatorio',
            'roles.*.id.integer' => 'El rol no es un número',
            'roles.*.id.exists' => 'El rol no existe en la base de datos'
        ];
    }
}
