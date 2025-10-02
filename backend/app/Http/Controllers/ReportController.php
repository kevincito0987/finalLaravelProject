<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function report(){
        try {
            $usuarios = User::count();
            $cards = Card::count();
    
            return response()->json(["usuarios"=>$usuarios,
            "cards"=>$cards
                            ]);
        } catch (\Exception $e) {
            Log::error("Error al traer los usuarios y tarjetas " . $e->getMessage());
            
            // Retornar una respuesta de error legible para el usuario de la API
            return response()->json([
                'message' => 'Error al traer la cantidad de usuarios y tarjetas.',
                'error' => $e->getMessage()
            ], 500);
        }

    }
}
