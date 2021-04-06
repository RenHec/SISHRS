<?php

namespace App\Http\Controllers\V1\Catalogo\Category;

use Illuminate\Http\Request;
use App\Models\V1\Catalogo\Category;
use App\Http\Controllers\ApiController;

class CategoryController extends ApiController
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
    public function index()
    {
        $data = Category::withoutTrashed()->get();
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
            $data = $request->all();

            Category::create($data);
            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\V1\Catalogo\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $this->validate($request, $this->rules($category->id), $this->messages());

        try {
            $category->name = $request->name;

            if (!$category->isDirty())
                $this->errorResponse('No hay datos para actualizar', 423);

            $category->save();

            return $this->successResponse('Registro actualizado.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\V1\Catalogo\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($category)
    {
        $category = Category::withTrashed()->find($category);

        if (is_null($category->deleted_at)) {
            $category->delete();
            $message = 'descativado';
        } else {
            $category->restore();
            $message = 'activado';
        }

        return $this->successResponse("Registro {$message}");
    }

    //Reglas de validaciones
    public function rules($id = null)
    {
        $validar = [
            'name' => is_null($id) ? 'required|max:50|unique:categories,name' : "required|max:50|unique:categories,name,{$id}"
        ];

        return $validar;
    }

    //Mensajes para las reglas de validaciones
    public function messages()
    {
        return [
            'name.required' => 'El nombre la categoría es obligatorio.',
            'name.max'  => 'El nombre la categoría debe tener menos de :max carácteres.',
            'name.unique'  => 'El nombre la categoría ingresado ya existe en el sistema.',
        ];
    }
}
