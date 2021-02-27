<?php

namespace App\Http\Controllers\V1\Principal\Room;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\V1\Principal\Room;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;
use App\Models\V1\Principal\PictureRoom;

class RoomController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @OA\Get(
     *      path="/service/rest/v1/principal/room",
     *      operationId="getRooms",
     *      tags={"Room"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Muestra todos las habitaciones registradas en la base de datos.",
     *      description="Retorna un array de habitaciones.",
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
        $data = Room::with('type_bed', 'type_room', 'coin')->get();;
        return $this->showAll($data);
    }

    /**
     * @OA\Post(
     *      path="/service/rest/v1/principal/room",
     *      operationId="postRoom",
     *      tags={"Room"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Crear una nueva habitación en el sistema.",
     *      description="Retorna el objeto de la habitación creada.",
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
            DB::beginTransaction();

            $data = $request->all();
            $data['type_bed_id'] = $request->type_bed_id['id'];
            $data['type_room_id'] = $request->type_room_id['id'];
            $data['coin_id'] = $request->coin_id['id'];

            $room = Room::create($data);

            foreach ($request->pictures as $value) {
                if(isset($value['photo']) && !is_null($value['photo']) && !empty($value['photo'])) {
                    $picture_name = Str::random(10);

                    $img = $this->getB64Image($value['photo']);
                    $image = Image::make($img);
                    $image->fit(870, 620, function ($constraint) {
                        $constraint->upsize();
                    });
                    $image->encode('jpg', 70);
                    $path = "{$room->id}/{$picture_name}.jpg";
                    Storage::disk('room')->put($path, $image);

                    PictureRoom::create(
                        [
                            'photo' => $path,
                            'position' => 0,
                            'view' => true,
                            'room_id' => $room->id
                        ]
                    );
                }
            }

            DB::commit();

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * @OA\Put(
     *      path="/service/rest/v1/principal/room/{room}",
     *      operationId="updateRoom",
     *      tags={"Room"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Actualizar la habitación seleccionado.",
     *      description="Retorna el objeto de la habitación actualizada.",
     *      @OA\Parameter(
     *          description="ID de la habitación para actualizar",
     *          in="path",
     *          name="room",
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
    public function update(Request $request, Room $room)
    {
        $this->validate($request, $this->rules(), $this->messages());

        try {
            $room->number = $request->number;
            $room->name = $request->name;
            $room->amount_people = $request->amount_people;
            $room->amount_bed = $request->amount_bed;
            $room->price = $request->price;
            $room->description = $request->description;
            $room->type_bed_id = $request->type_bed_id['id'];
            $room->type_room_id = $request->type_room_id['id'];
            $room->coin_id = $request->coin_id['id'];

            if(!$room->isDirty())
                $this->errorResponse('No hay datos para actualizar', 423);
            
            $room->save();

            return $this->successResponse('Registro actualizado.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * @OA\Delete(
     *      path="/service/rest/v1/principal/room/{room}",
     *      operationId="deleteRoom",
     *      tags={"Room"},
     *      security={
     *          {"passport": {}},
     *      },
     *      summary="Cambiar estado a la habitación seleccionada.",
     *      description="Retorna el objeto de la habitación con nuevo estado.",
     *      @OA\Parameter(
     *          description="ID de la habitación para cambiar de estado.",
     *          in="path",
     *          name="room",
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
    public function destroy($room)
    {
        $room = Room::withTrashed()->find($room);
        if (is_null($room->deleted_at)) {
            $room->delete();
            $message = 'descativado';
        } else {
            $room->restore();
            $message = 'activado';
        }

        return $this->successResponse("Registro {$message}");
    }

    //Reglas de validaciones
    public function rules()
    {
        $validar = [
            'number' => 'required|between:1,999',
            'name' => 'required|max:100',
            'amount_people' => 'required|between:1,99',
            'amount_bed' => 'required|between:1,99',
            'price' => 'required|between:1,9999999',
            'description' => 'required',
            'type_bed_id.id' => 'required|integer|exists:type_beds,id',
            'type_room_id.id' => 'required|integer|exists:type_rooms,id',
            'coin_id.id' => 'required|integer|exists:coins,id'
        ];

        return $validar;
    }

    //Mensajes para las reglas de validaciones
    public function messages()
    {
        return [
            'number.required' => 'El número de habitación es obligatorio.',
            'number.between' => 'El número de habitación tiene que estar en un rango entre :min y :max.',

            'name.required' => 'El nombre de la habitación es obligatorio.',
            'name.max'  => 'El nombre de la habitación debe tener menos de :max carácteres.',

            'amount_people.required' => 'El número de personas por habitación es obligatorio.',
            'amount_people.between' => 'El número de personas por habitación tiene que estar en un rango entre :min y :max.',

            'amount_bed.required' => 'El número de personas por cama es obligatorio.',
            'amount_bed.between' => 'El número de personas por cama tiene que estar en un rango entre :min y :max.',

            'price.required' => 'El precio de la habitación es obligatorio.',
            'price.between' => 'El precio de la habitación tiene que estar en un rango entre :min y :max.',

            'description.required' => 'La descripción de la habitación es obligatoria.',

            'type_bed_id.id.required' => 'El tipo de cama es obligatorio.',
            'type_bed_id.id.integer' => 'El tipo de cama no es un número.',
            'type_bed_id.id.exists' => 'El tipo de cama no existe en la base de datos.',

            'type_room_id.id.required' => 'El tipo de habitación es obligatorio.',
            'type_room_id.id.integer' => 'El tipo de habitación no es un número.',
            'type_room_id.id.exists' => 'El tipo de habitación no existe en la base de datos.',

            'coin_id.id.required' => 'El tipo de moneda es obligatorio.',
            'coin_id.id.integer' => 'El tipo de moneda no es un número.',
            'coin_id.id.exists' => 'El tipo de moneda no existe en la base de datos.'
        ];
    }
}
