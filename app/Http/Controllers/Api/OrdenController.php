<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrdenRequest;
use App\Models\Orden;
use App\Models\OrdenItem;
use App\Models\Producto;
use App\Traits\ApiRespuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdenController extends Controller
{
    use ApiRespuesta;

    public function index()
    {
        $ordenes = Orden::with('items.producto')->paginate(10);
        return $this->successResponse($ordenes);
    }


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
                    'precio' => $producto->price
                ]);

                $orden->items()->save($ordenItem);
                $montoTotal += ($producto->price * $item['cantidad']);

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


    public function show(Orden $orden)
    {
        return $this->successResponse($orden->load('items.producto'));
    }


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
}
