<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

     /**
     * @OA\Get(
     *     path="/api/panel/inventory/categories",
     *     tags={"Categorias"},
     *     summary="Obtener todas las categorias",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function index()
    {
        $categories = Category::orderBy('created_at', 'desc')->get();
        return $this->successResponse($categories);
    }


    public function tools()
    {
        $categories = Category::where('type', 'tool')->orderBy('created_at', 'desc')->get();
        return $this->successResponse($categories);
    }
}
