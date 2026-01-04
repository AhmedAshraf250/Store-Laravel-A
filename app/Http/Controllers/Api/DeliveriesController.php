<?php

namespace App\Http\Controllers\Api;

use App\Events\DeliveryLocationUpdated;
use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveriesController extends Controller
{
    public function show(Delivery $delivery)
    {
        // ======= [Deprecated] 1 =======
        // $delivery = Delivery::query()->select([
        //     'id',
        //     'order_id',
        //     'status',
        //     DB::raw('ST_X(current_location) as longitude'),
        //     DB::raw('ST_Y(current_location) as latitude'),
        // ])->where('id', $id)->firstOrFail ();


        return $delivery;
    }

    public function update(Request $request, Delivery $delivery)
    {
        $validated = $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        // ======= [Deprecated] 1 =======
        // $delivery->update([
        //     'current_location' => DB::raw(
        //         sprintf(
        //             'POINT(%F, %F)',
        //             $validated['longitude'],
        //             $validated['latitude']
        //         )
        //     ),
        // ]);

        // ======= [Deprecated] 2 =======
        // DB::statement(
        //     'UPDATE deliveries 
        //         SET 
        //             current_location = POINT(?, ?),
        //             updated_at = NOW()
        //         WHERE id = ?',
        //     [
        //         $validated['longitude'],
        //         $validated['latitude'],
        //         $delivery->id
        //     ]
        // );

        $delivery->setLocation(
            $validated['latitude'],
            $validated['longitude']
        );

        event(new DeliveryLocationUpdated($delivery));
        return $delivery;
    }
}
