<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SensorData;
use Illuminate\Support\Facades\Log;

class AdaFruitController extends Controller
{
    public function consumirdistancia(Request $request)
    {
        $response = Http::withHeaders([
            'X-AIO-Key' => 'aio_bkjN186RBevWP1BvvoBzu4lvpESg',
        ])->get('https://io.adafruit.com/api/v2/Teemo_abejita/groups/manhattan');

        if ($response->ok()) {
            $data = $response->json();

           
            $this->guardarDatosEnDB($data);

            return response()->json([
                "msg"  => "Success",
                "data" => $data
            ], 200);
        } else {
            return response()->json([
                "msg"  => "Error",
                "data" => $response->json()
            ], $response->status());
        }
    }

    private function guardarDatosEnDB($data)
    {
        
        foreach ($data['feeds'] as $feed) {
            SensorData::updateOrCreate(
                ['key' => $feed['key']], 
                [
                    'name'       => $feed['name'],
                    'last_value' => $feed['last_value'],
                    
                ]
            );
        }
    }
}
