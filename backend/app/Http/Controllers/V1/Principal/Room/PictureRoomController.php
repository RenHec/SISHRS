<?php

namespace App\Http\Controllers\V1\Principal\Room;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\V1\Principal\Room;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\ApiController;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use App\Models\V1\Principal\PictureRoom;

class PictureRoomController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @OA\Post(
     *      path="/service/rest/v1/principal/picture_room",
     *      operationId="postPictureRoom",
     *      tags={"Picture Room"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Actualización masiva de las posiciones de las fotografías de la habitación seleccionada.",
     *      description="Retorna un mensaje indicado el resultado del proceso.",
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
        $validar = [
            'pictures.*.id' => 'required',
            'pictures.*.position' => 'required'
        ];
        $message = [
            'pictures.*.id.required' => 'El ID de la fotografía es obligatorio',
            'pictures.*.position.required' => 'La posición de la fotográfica es obligatorio'
        ];
        $this->validate($request, $validar, $message);

        try {
            DB::beginTransaction();

            foreach ($request->pictures as $key => $value) {
                $image = PictureRoom::find($value['id']);
                $image->position = $value['position'];
                $image->save();
            }

            DB::commit();

            return $this->successResponse('Actualización de posiciones de las fotografías');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 423);
        }
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/principal/picture_room/{picture_room}",
     *      operationId="findPictureRoombyId",
     *      tags={"Picture Room"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Muestra todas fotografías de la habitación seleccionada.",
     *      description="Retorna un array de las fotografías de la habitación seleccionada.",
     *      @OA\Parameter(
     *          description="ID de la habitación a consultar",
     *          in="path",
     *          name="picture_room",
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
    public function show(Room $picture_room)
    {
        $picture_room  = PictureRoom::where('room_id', $picture_room->id)->orderBy('position', 'ASC')->get();
        return $this->showAll($picture_room);
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/principal/picture_room/view/{picture_room}",
     *      operationId="findPictureRoomViewbyId",
     *      tags={"Picture Room"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Actualización de estado de la vista de la fotografía seleccionada.",
     *      description="Retorna el objeto de la fotografía actualizada.",
     *      @OA\Parameter(
     *          description="ID de la fotografía a actualizar.",
     *          in="path",
     *          name="picture_room",
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
    public function view(PictureRoom $picture_room)
    {
        try {
            $picture_room->view = $picture_room->view == true ? false : true;
            $picture_room->save();

            return $this->successResponse('Actualización de estado de la fotografía');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 423);
        }
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/principal/picture_room/up/{picture_room}",
     *      operationId="findPictureRoomUpbyId",
     *      tags={"Picture Room"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Actualización de la posición de la fotografía seleccionada.",
     *      description="Retorna el objeto de la fotografía actualizada.",
     *      @OA\Parameter(
     *          description="ID de la fotografía a actualizar.",
     *          in="path",
     *          name="picture_room",
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
    public function up(PictureRoom $picture_room)
    {
        try {
            DB::beginTransaction();

            $arriba = $picture_room->position + 1;

            $posicion_baja = PictureRoom::where('room_id', $picture_room->room_id)->where('position', $arriba)->first();
            if(!is_null($posicion_baja)) {
                $posicion_baja->position = $picture_room->position;
                $posicion_baja->save();
            }

            $picture_room->position = $arriba;
            $picture_room->save();

            DB::commit();

            return $this->successResponse('Imagen actualizada');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 423);
        }
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/principal/picture_room/down/{picture_room}",
     *      operationId="findPictureRoomDownbyId",
     *      tags={"Picture Room"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Actualización de la posición de la fotografía seleccionada.",
     *      description="Retorna el objeto de la fotografía actualizada.",
     *      @OA\Parameter(
     *          description="ID de la fotografía a actualizar.",
     *          in="path",
     *          name="picture_room",
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
    public function down(PictureRoom $picture_room)
    {

        try {
            DB::beginTransaction();

            $abajo = $picture_room->position - 1;

            $posicion_sube = PictureRoom::where('room_id', $picture_room->room_id)->where('position', $abajo)->first();
            if (!is_null($posicion_sube)) {
                $posicion_sube->position = $picture_room->position;
                $posicion_sube->save();
            }

            $picture_room->position = $abajo;
            $picture_room->save();

            DB::commit();

            return $this->successResponse('Imagen actualizada');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 423);
        }
    }

    /**
     * @OA\Put(
     *      path="/service/rest/v1/principal/picture_room/{picture_room}",
     *      operationId="updatePictureRoom",
     *      tags={"Picture Room"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Agregar una nueva fotografía a la habitación seleccionada.",
     *      description="Retorna el objeto de la habitación actualizada.",
     *      @OA\Parameter(
     *          description="ID de la habitación para agregar nuevas fotografías.",
     *          in="path",
     *          name="picture_room",
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
    public function update(Request $request, Room $picture_room)
    {
        try {

            foreach ($request->pictures as $value) {
                if (isset($value['photo']) && !is_null($value['photo']) && !empty($value['photo'])) {
                    $picture_name = Str::random(10);

                    $img = $this->getB64Image($value['photo']);
                    $image = Image::make($img);
                    $image->fit(870, 620, function ($constraint) {
                        $constraint->upsize();
                    });
                    $image->encode('jpg', 70);
                    $path = "{$picture_room->id}/{$picture_name}.jpg";
                    Storage::disk('room')->put($path, $image);

                    PictureRoom::create(
                        [
                            'photo' => $path,
                            'position' => 0,
                            'view' => true,
                            'room_id' => $picture_room->id
                        ]
                    );
                }
            }

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * @OA\Delete(
     *      path="/service/rest/v1/principal/picture_room/{picture_room}",
     *      operationId="deletePictureRoom",
     *      tags={"Picture Room"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Eliminar la fotografía seleccionada.",
     *      description="Retorna el objeto de la fotografía eliminada.",
     *      @OA\Parameter(
     *          description="ID de la fotografía para eliminar",
     *          in="path",
     *          name="picture_room",
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
    public function destroy(PictureRoom $picture_room)
    {
        try {
            $picture_room->forceDelete();
            Storage::disk('room')->exists($picture_room->photo) ? Storage::disk('room')->delete($picture_room->photo) : null;
            return $this->successResponse('Registro eliminado.');
        } catch (\Exception $e) {
            if ($e instanceof QueryException) {
                return $this->errorResponse('El registro se encuentra en uso', 423);
            }
        }
    }
}
