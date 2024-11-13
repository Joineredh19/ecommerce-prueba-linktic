<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Prueba Tecnica LinkTic ",
 *     description="API documentation Prueba Tecnica",
 *     @OA\Contact(
 *         email="joineredh@gmail.com"
 *     )
 * )
 *
 * @OA\Server(
 *      url="http://127.0.0.1:8000/",
 *     description="API Server"
 * )
 *
 * @OA\Components(
 *     @OA\Schema(
 *         schema="ProductoRequest",
 *         type="object",
 *         required={"nombre", "descripcion", "precio", "stock", "codigo"},
 *         @OA\Property(
 *             property="nombre",
 *             type="string"
 *         ),
 *         @OA\Property(
 *             property="descripcion",
 *             type="string"
 *         ),
 *         @OA\Property(
 *             property="precio",
 *             type="number"
 *         ),
 *         @OA\Property(
 *             property="stock",
 *             type="integer"
 *         ),
 *         @OA\Property(
 *             property="codigo",
 *             type="string"
 *         ),
 *         @OA\Property(
 *             property="activo",
 *             type="boolean"
 *         )
 *     ),
 *     @OA\Schema(
 *         schema="OrdenRequest",
 *         type="object",
 *         required={"items"},
 *         @OA\Property(
 *             property="items",
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 required={"producto_id", "cantidad"},
 *                 @OA\Property(
 *                     property="producto_id",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="cantidad",
 *                     type="integer"
 *                 )
 *             )
 *         ),
 *         @OA\Property(
 *             property="estado",
 *             type="string",
 *             enum={"pendiente", "procesando", "completado", "cancelado"}
 *         )
 *     )
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
