<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Partie;
use App\GameEvent;

class MorpionController extends Controller
{
    public function morpion(Request $request)
    {
        $id = Auth::user()->id;
        $id_ami = $request->id_ami;

        if (!$request->partie){
            $tableau = array_fill(0, 9, null);
            $partie = new Partie($id, $tableau, 'morpion');
        }

        else {
            $partie = $request->partie;
            $partie = json_decode($partie);
            $partie->tableau[$request->index] = $id;
            $partie->tour = $id_ami;
            $partie->sound = "/sounds/CraieMorpion.mp3";
        
            /* Détection WINNER*/ 
            $lines = [
                [0, 1, 2],
                [3, 4, 5],
                [6, 7, 8],
                [0, 3, 6],
                [1, 4, 7],
                [2, 5, 8],
                [0, 4, 8],
                [2, 4, 6]
            ];

            foreach($lines as $line) {
                $a = $line[0]; $b = $line[1]; $c = $line[2];
                if ($partie->tableau[$a] && $partie->tableau[$a] === $partie->tableau[$b] && $partie->tableau[$a] === $partie->tableau[$c])
                    $partie->winner = $partie->tableau[$a];
            }
            /* Détection EGALITE*/
            if(!in_array(null, $partie->tableau)){
                $partie->winner = 'Egalité';
            }
        }
        
        broadcast(new GameEvent($partie, $id_ami));

        return view('partie', [
            "game" => $partie,
            "id_join" => $id_ami,
            "id" => $id
        ]);
    }
}
