<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Partie;
use App\GameEvent;

class Puissance4Controller extends Controller
{
    public function puissance4(Request $request)
    {
        $id = Auth::user()->id;
        $id_ami = $request->id_ami;

        if (!$request->partie){
            $colonnes = array_fill(0, 7, array());
            foreach($colonnes as $key => $colonne){
                $colonnes[$key] = array_fill(0, 6, null);
            }
            $partie = new Partie($id, $colonnes, 'puissance4');
        }

        else {
            $partie = $request->partie;
            $partie = json_decode($partie);
            foreach(array_reverse($partie->tableau[$request->index]) as $key => $colonne){
                if (!$colonne){
                    $partie->tableau[$request->index][5-$key] = $id;
                    $position = 5-$key;
                    break;
                }
            }
            $partie->tour = $id_ami;
            /* Détection WINNER*/
            $lines = [
                [1, 2, 3, 0, 0, 0],
                [-1, -2, -3, 0, 0, 0],
                [0, 0, 0, 1, 2, 3],
                [0, 0, 0, -1, -2, -3],
                [1, 2, 3, 1, 2, 3],
                [-1, -2, -3, -1, -2, -3],
                [-1, -2, -3, 1, 2, 3],
                [1, 2, 3, -1, -2, -3]
            ];
            foreach($lines as $line) {
                $ai = $line[0]; $bi = $line[1]; $ci = $line[2]; $ap = $line[3]; $bp = $line[4]; $cp = $line[5];
                try {
                    if ($partie->tableau[$request->index][$position] === $partie->tableau[$request->index + $ai][$position + $ap] 
                    && $partie->tableau[$request->index][$position] === $partie->tableau[$request->index + $bi][$position + $bp] 
                    && $partie->tableau[$request->index][$position] === $partie->tableau[$request->index + $ci][$position + $cp]){
                        $partie->winner = $partie->tableau[$request->index][$position];
                    }
                } catch (\Exception $e) {}
            }

            /* Détection EGALITE*/
            $compteur = 0;
            foreach($partie->tableau as $colonne){
                if(!in_array(null, $colonne)){
                    $compteur += 1;
                }
            }
            if ($compteur == 7)
                $partie->winner = 'Egalité';
        }

        broadcast(new GameEvent($partie, $id_ami));

        return view('partie', [
            "game" => $partie,
            "id_join" => $id_ami,
            "id" => $id
        ]);
    }
}
