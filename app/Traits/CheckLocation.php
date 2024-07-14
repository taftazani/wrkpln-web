<?php

namespace App\Traits;

trait CheckLocation
{
    private function checkLocation($lat1, $lon1, $user)
    {
        $inRadiusAreaIds = []; // Array to hold the IDs of areas that are within the radius

        foreach ($user->area as $data_user) {
            $R = 6371; // Radius of the earth in km
            $dLat = deg2rad($data_user->latitude - $lat1);
            $dLon = deg2rad($data_user->longitude - $lon1);

            $a = sin($dLat / 2) * sin($dLat / 2) +
                cos(deg2rad($lat1)) * cos(deg2rad($data_user->latitude)) *
                sin($dLon / 2) * sin($dLon / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            $d = $R * $c; // Distance in km
            $e = $d * 1000; // Distance in meters

            if ($e <= $data_user->radius) {
                $inRadiusAreaIds[] = $data_user->id;
            }
        }

        return $inRadiusAreaIds;
    }
}
