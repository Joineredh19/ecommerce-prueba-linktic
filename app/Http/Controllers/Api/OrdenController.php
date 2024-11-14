<?php

namespace App\Http\Controllers\Api;

use App\Exports\OrdenesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrdenRequest;
use App\Models\Orden;
use App\Models\OrdenItem;
use App\Models\Producto;
use App\Traits\ApiRespuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class OrdenController extends Controller
{
    use ApiRespuesta;

 /**
     * @OA\Get(
     *     path="/api/ordenes",
     *     summary="Listar todas las ordenes",
     *     tags={"Ordenes"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de Ordenes"
     *     )
     * )
     */

    public function index()
    {
        $ordenes = Orden::with('items.producto')->paginate(10);
        return $this->successResponse($ordenes);
    }

/**
     * @OA\Post(
     *     path="/api/ordenes",
     *     summary="Crear una nueva orden",
     *     tags={"Ordenes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/OrdenRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Orden creada Exitosamente"
     *     )
     * )
     */

    public function store(OrdenRequest $request)
    {
        DB::beginTransaction();
        try {
            $orden = new Orden();
            $orden->numero_orden = 'ORD-' . time();
            $orden->estado = 'pendiente';
            $orden->monto_total = 0;
            $orden->save();

            $montoTotal = 0;
            foreach ($request->items as $item) {
                $producto = Producto::findOrFail($item['producto_id']);

                if ($producto->stock < $item['cantidad']) {
                    throw new \Exception("Insuficiente stock para el producto: {$producto->nombre}");
                }

                $ordenItem = new OrdenItem([
                    'producto_id' => $producto->id,
                    'cantidad' => $item['cantidad'],
                    'precio' => $producto->precio
                ]);

                $orden->items()->save($ordenItem);
                $montoTotal += ($producto->precio * $item['cantidad']);

                // Actualizar stock
                $producto->stock -= $item['cantidad'];
                $producto->save();
            }

            $orden->monto_total = $montoTotal;
            $orden->save();

            DB::commit();
            return $this->successResponse($orden->load('items.producto'), 'Order creada exitosamente', 201);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(), 422);
        }
    }


        /**
     * @OA\Get(
     *     path="/api/ordenes/{id}",
     *     summary="Obtener Orden por ID",
     *     tags={"Ordenes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles ordenes"
     *     )
     * )
     */

    public function show(Orden $orden)
    {
        return $this->successResponse($orden->load('items.producto'));
    }


        /**
     * @OA\Put(
     *     path="/api/ordenes/{id}",
     *     summary="Actualizar estado orden",
     *     tags={"Ordenes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="estado", type="string", enum={"pendiente", "procesando", "completado", "cancelado"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Orden actualizada exitosamente"
     *     )
     * )
     */

    public function update(Request $request, Orden $orden)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,procesando,completado,cancelado'
        ]);

        $orden->estado = $request->estado;
        $orden->save();

        return $this->successResponse($orden, 'Estado de Orden actualizado exitosamente');
    }


    public function destroy(Orden $orden)
    {
        //
    }

/**
 * @OA\Get(
 *     path="/api/ordenes/exportar",
 *     summary="Exportar órdenes a Excel",
 *     tags={"Ordenes"},
 *     @OA\Response(
 *         response=200,
 *         description="Archivo Excel con listado de órdenes",
 *         @OA\MediaType(
 *             mediaType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
 *             @OA\Schema(type="string", format="binary")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error en la exportación",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Error al exportar órdenes"),
 *             @OA\Property(property="error", type="string", example="Error detallado del sistema")
 *         )
 *     ),
 * )
 */
    public function export()
    {
        try {
            return Excel::download(new OrdenesExport, 'ordenes.xlsx');
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al exportar órdenes',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
