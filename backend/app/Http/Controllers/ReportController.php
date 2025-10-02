<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function report(){
        $usuarios = User::count();
        $cards = Card::count();

        return response()->json(["usuarios"=>$usuarios,
        "cards"=>$cards
                        ]);
    }
}
