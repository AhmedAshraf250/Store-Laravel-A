<?php

namespace App\Concerns;

use Illuminate\Support\Facades\DB;

trait HasPointLocation
{
    public function setLocation(float $latitude, float $longitude): void
    {
        DB::statement(
            'UPDATE ' . $this->getTable() . '
             SET current_location = POINT(?, ?)
             WHERE id = ?',
            [$longitude, $latitude, $this->id]
        );

        $this->refresh();
    }

    public function getLocationAttribute(): ?array
    {
        if (!$this->current_location) {
            return null;
        }

        $point = DB::selectOne(
            'SELECT 
                ST_X(current_location) AS longitude,
                ST_Y(current_location) AS latitude
             FROM ' . $this->getTable() . '
             WHERE id = ?',
            [$this->id]
        );

        if (!$point) {
            return null;
        }

        return [
            'latitude'  => (float) $point->latitude ?? 0,
            'longitude' => (float) $point->longitude ?? 0,
        ];

        // $lat = (float) $point->latitude ?? 0;
        // $lng = (float) $point->longitude ?? 0;
        // return "{$lat},{$lng}";
    }
}
