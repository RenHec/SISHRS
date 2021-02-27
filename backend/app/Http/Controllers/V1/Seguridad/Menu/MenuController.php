<?php

namespace App\Http\Controllers\V1\Seguridad\Menu;

use App\Models\V1\Seguridad\Menu;
use App\Http\Controllers\ApiController;

class MenuController extends ApiController
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
        $dato = Menu::where('father', '!=', 0)->get();
        return $this->showAll($dato);
    }
}
