<?php

namespace App\Http\Controllers\Api;

use App\Exports\ProductosExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductoRequest;
use App\Models\Producto;
use App\Traits\ApiRespuesta;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

/**
 * @OA\Tag(
 *     name="Productos",
 *     description="API Endpoints de Productos"
 * )
 */
class ProductoController extends Controller
{
    use ApiRespuesta;

    /**
     * @OA\Get(
     *     path="/api/productos",
     *     summary="Lista todos los productos",
     *     tags={"Productos"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de productos"
     *     )
     * )
     */

    public function index()
    {
        $producto = Producto::paginate(10);
        return $this->successResponse($producto);
    }

    /**
     * @OA\Post(
     *     path="/api/productos",
     *     summary="Crear un nuevo producto",
     *     tags={"Productos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProductoRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Producto creado exitosamente"
     *     )
     * )
     */

    public function store(ProductoRequest $request)
    {
        $producto = Producto::create($request->validated());
        return $this->successResponse($producto, 'Producto creado exitosamente', 201);
    }

    /**
     * @OA\Get(
     *     path="/api/productos/{id}",
     *     summary="Obtener un producto por ID",
     *     tags={"Productos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalle Producto"
     *     )
     * )
     */

    public function show(Producto $producto)
    {
        return $this->successResponse($producto);
    }

    /**
     * @OA\Put(
     *     path="/api/productos/{id}",
     *     summary="Actualizar un producto",
     *     tags={"Productos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProductoRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto actualizado exitosamente"
     *     )
     * )
     */
    public function update(ProductoRequest $request, Producto $producto)
    {
        $producto->update($request->validated());
        return $this->successResponse($producto, 'Producto actualizado exitosamente');
    }

     /**
     * @OA\Delete(
     *     path="/api/productos/{id}",
     *     summary="Eliminar un producto",
     *     tags={"Productos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto eliminado exitosamente"
     *     )
     * )
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();
        return $this->successResponse(null, 'Producto eliminado exitosamente');
    }


    /**
 * @OA\Get(
 *     path="/api/productos/exportar",
 *     summary="Exportar productos a Excel",
 *     tags={"Productos"},
 *     @OA\Response(
 *         response=200,
 *         description="Archivo Excel con listado de productos",
 *         @OA\MediaType(
 *             mediaType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
 *             @OA\Schema(type="string", format="binary")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error en la exportación",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Error al exportar productos"),
 *             @OA\Property(property="error", type="string", example="Error detallado del sistema")
 *         )
 *     ),
 * )
 */
    public function export()
    {
        try {
            return Excel::download(new ProductosExport, 'productos.xlsx');
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al exportar productos',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
