<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductoRequest;
use App\Models\Producto;
use App\Traits\ApiRespuesta;
use Illuminate\Http\Request;


class ProductoController extends Controller
{
    use ApiRespuesta;


    public function index()
    {
        $producto = Producto::paginate(10);
        return $this->successResponse($producto);
    }



    public function store(ProductoRequest $request)
    {
        $producto = Producto::create($request->validated());
        return $this->successResponse($producto, 'Producto creado exitosamente', 201);
    }



    public function show(Producto $producto)
    {
        return $this->successResponse($producto);
    }


    public function update(ProductoRequest $request, Producto $producto)
    {
        $producto->update($request->validated());
        return $this->successResponse($producto, 'Producto actualizado exitosamente');
    }


    public function destroy(Producto $producto)
    {
        $producto->delete();
        return $this->successResponse(null, 'Producto eliminado exitosamente');
    }
}
