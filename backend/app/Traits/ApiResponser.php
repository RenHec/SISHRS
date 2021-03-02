<?php

namespace App\Traits;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

trait ApiResponser
{
	protected function successResponse($data, $code = 200)
	{
		return response()->json($data, $code);
	}

	protected function errorResponse($message, $code)
	{
		return response()->json(['error' => $message, 'code' => $code], $code);
	}

	protected function showAll(Collection $collection, $code = 200)
	{
		return response()->json(['data' => $collection], $code);
	}

	protected function showOne(Model $instance, $code = 200, $action = 'SELECT')
	{
		return response()->json(['data' => $instance], $code);
	}

	protected function showMessage($message, $code = 210)
	{
		return $this->successResponse($message, $code);
	}

	protected function getB64Image($base64_image)
	{
		// Obtener el String base-64 de los datos         
		$image_service_str = substr($base64_image, strpos($base64_image, ",") + 1);
		// Decodificar ese string y devolver los datos de la imagen        
		$image = base64_decode($image_service_str);
		// Retornamos el string decodificado
		return $image;
	}

	protected function verificar_excepcion($e)
	{
		$codigo = $e->errorInfo[1];

		$message = "";

		switch ($codigo) {
			case 4060:
				$message = "No se puede abrir la base de datos solicitada por el inicio de sesión";
				break;
			case 40197:
				$message = "Error en el servicio al procesar la solicitud. Vuelva a intentarlo";
				break;
			case 40501:
				$message = "El servicio está ocupado actualmente. Vuelva a intentar la solicitud después de 10 segundos";
				break;
			case 40613:
				$message = "Vuelva a intentar la conexión más tarde";
				break;
			case 49918:
				$message = "No se puede procesar la solicitud. No hay suficientes recursos para procesar la solicitud";
				break;
			case 49919:
				$message = "No se procesar, crear ni actualizar la solicitud. Hay demasiadas operaciones de creación o actualización en curso para la suscripción";
				break;
			case 1451:
				$message = "El registro se encuentra asociado, no podra realizar la eliminación";
				break;
			case 23503:
				$message = "El registro se encuentra asociado, no podra realizar la eliminación";
				break;
			case 104:
				$message = "Debe haber elementos ORDER BY en la lista de selección si la instrucción contiene el operador UNION, INTERSECT o EXCEPT";
				break;
			case 107:
				$message = "El prefijo de columna no coincide con un nombre de tabla o un nombre de alias utilizado en la consulta";
				break;
			case 109:
				$message = "Hay más columnas en la instrucción INSERT que valores en la cláusula VALUES. El número de valores de VALUES debe coincidir con el de columnas de INSERT";
				break;
			case 109:
				$message = "Hay más columnas en la instrucción INSERT que valores en la cláusula VALUES. El número de valores de VALUES debe coincidir con el de columnas de INSERT";
				break;
			case 1048:
				$message = "Los campos no pueden ser nulos, debe de especificar un valor";
				break;
			case 42703:
				$message = "No existe una o varias columnas en la BD y que están referenciadas en el modelo";
				break;
			case 23505:
				$message = "El registro ya existe";
				break;
			default:
				$message = $e;
				break;
		}

		return $message;
	}

	protected function generadorCodigo($id)
	{
		$año = date('Y');
		$codigo = str_pad(strval($id), 5, "0", STR_PAD_LEFT);
		return "{$codigo}-{$año}";
	}
}
