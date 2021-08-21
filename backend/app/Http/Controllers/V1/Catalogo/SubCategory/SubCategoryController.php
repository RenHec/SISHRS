<?php

namespace App\Http\Controllers\V1\Catalogo\SubCategory;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\V1\Catalogo\Category;
use App\Models\V1\Catalogo\SubCategory;

class SubCategoryController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Category $sub_category)
    {
        $data = SubCategory::where('category_id', $sub_category->id)->get();
        return $this->showAll($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules(), $this->messages());

        try {

            $sub = SubCategory::where('name', $request->name)->where('category_id', $request->category_id)->first();

            if (is_null($sub)) {
                $sub = new SubCategory();
            }

            $sub->name = $request->name;
            $sub->category_id = $request->category_id;
            $sub->deleted_at = null;
            $sub->save();

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\V1\Catalogo\SubCategory  $sub_category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubCategory $sub_category)
    {
        $this->validate($request, $this->rules($sub_category->id), $this->messages());

        try {
            $sub_category->name = $request->name;
            $sub_category->category_id = $request->category_id;

            if (!$sub_category->isDirty())
                $this->errorResponse('No hay datos para actualizar', 423);

            $sub_category->save();

            return $this->successResponse('Registro actualizado.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\V1\Catalogo\SubCategory  $sub_category
     * @return \Illuminate\Http\Response
     */
    public function destroy($sub_category)
    {
        $sub_category = SubCategory::withTrashed()->find($sub_category);
        if (is_null($sub_category->deleted_at)) {
            $sub_category->delete();
            $message = 'descativado';
        } else {
            $sub_category->restore();
            $message = 'activado';
        }

        return $this->successResponse("Registro {$message}");
    }

    //Reglas de validaciones
    public function rules($id = null)
    {
        $validar = [
            'name' => 'required|max:20',
            'category_id' => 'required|integer|exists:categories,id'
        ];

        return $validar;
    }

    //Mensajes para las reglas de validaciones
    public function messages()
    {
        return [
            'name.required' => 'El nombre de la sub categoría es obligatorio.',
            'name.max'  => 'El nombre de la sub categoría debe tener menos de :max carácteres.',
            'name.unique'  => 'El nombre de la sub categoría ingresado ya existe en el sistema.',

            'category_id.required' => 'La categoría es obligatorio',
            'category_id.integer' => 'La categoría no es un número',
            'category_id.exists' => 'La categoría no existe en la base de datos'
        ];
    }
}
