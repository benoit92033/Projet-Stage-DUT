<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Partie;
use App\GameEvent;

class GameController extends Controller
{
    public function morpion(Request $request)
    {
        $id = Auth::user()->id;
        $id_ami = $request->id_ami;

        if (!$request->partie){
            $tableau = array_fill(0, 9, null);
            $partie = new Partie($id, $tableau, null, 'morpion');
        }

        else {
            $partie = $request->partie;
            $partie = json_decode($partie);
            $partie->tableau[$request->index] = $id;
            $partie->tour = $id_ami;
        }

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

        broadcast(new GameEvent($partie, $id_ami));

        return view('partie', [
            "game" => $partie,
            "id_join" => $id_ami,
            "id" => $id
        ]);
    }

    public function puissance4(Request $request)
    {
        $id = Auth::user()->id;
        $id_ami = $request->id_ami;

        if (!$request->partie){
            $colonnes = array_fill(0, 7, array());
            foreach($colonnes as $key => $colonne){
                $colonnes[$key] = array_fill(0, 6, null);
            }
            $partie = new Partie($id, $colonnes, null, 'puissance4');
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

    public function batailleNavale(Request $request)
    {
        $id = Auth::user()->id;
        $id_ami = $request->id_ami;

        if (!$request->partie){
            $colonnes = array_fill(0, 12, array());
            foreach($colonnes as $key => $colonne){
                $colonnes[$key] = array_fill(0, 12, null);
            }

            $colonnes_2 = $colonnes;
            
            $bateaux = [5,4,3,3,2];
            
            foreach($bateaux as $longueurBateau){
                $gen = false;
                while($gen == false){
                    $gen = $this->genereBoat($longueurBateau ,$colonnes_2);
                }
                $colonnes_2 = $gen;
            }

            foreach($bateaux as $longueurBateau){
                $gen = false;
                while($gen == false){
                    $gen = $this->genereBoat($longueurBateau ,$colonnes);
                }
                $colonnes = $gen;
            }

            unset($colonnes[11]);
            unset($colonnes[0]);
            $colonnes = array_values($colonnes);

            unset($colonnes_2[11]);
            unset($colonnes_2[0]);
            $colonnes_2 = array_values($colonnes_2);

            foreach($colonnes as $key => $colonne){
                unset($colonne[11]);
                unset($colonne[0]);
                $colonne = array_values($colonne);
                $colonnes[$key] = $colonne;
            }

            foreach($colonnes_2 as $key => $colonne){
                unset($colonne[11]);
                unset($colonne[0]);
                $colonne = array_values($colonne);
                $colonnes_2[$key] = $colonne;
            }

            $partie = new Partie($id, $colonnes, $colonnes_2,'batailleNavale');
        }

        else {
            $partie = $request->partie;
            $partie = json_decode($partie);

            if($partie->couleur == $id){
                if($partie->tableau_2[$request->indexColonne][$request->indexLigne] == 'bateau'){
                    $partie->tableau_2[$request->indexColonne][$request->indexLigne] = 'boom';
                    $partie->tour = $id;
                }
                else{ 
                    $partie->tableau_2[$request->indexColonne][$request->indexLigne] = $id;
                    $partie->tour = $id_ami;
                }
            }

            else{
                if($partie->tableau[$request->indexColonne][$request->indexLigne] == 'bateau'){
                    $partie->tableau[$request->indexColonne][$request->indexLigne] = 'boom';
                    $partie->tour = $id;
                }
                else {
                    $partie->tableau[$request->indexColonne][$request->indexLigne] = $id;
                    $partie->tour = $id_ami;
                }
            }

            /* Détection WINNER*/
        }

        broadcast(new GameEvent($partie, $id_ami));

        return view('partie', [
            "game" => $partie,
            "id_join" => $id_ami,
            "id" => $id
        ]);
    }

    public function genereBoat($longueur, $colonnesBateaux){
        $dir = rand(0,1); 
        $vertical = rand(0,1);
        
        if($vertical && $dir){
            $colonne = rand(1, 10);
            $ligne = rand(1, 10-$longueur-1); 
            try {
                if ($colonnesBateaux[$colonne][$ligne - 1] != 'bateau')
                    for($i=0; $i<$longueur; $i++){
                        if($colonnesBateaux[$colonne][$ligne + $i] != 'bateau' 
                        && $colonnesBateaux[$colonne + 1][$ligne + $i] != 'bateau'
                        && $colonnesBateaux[$colonne - 1][$ligne + $i] != 'bateau'
                        && $colonnesBateaux[$colonne][$ligne + $i + 1] != 'bateau')
                            $colonnesBateaux[$colonne][$ligne + $i] = 'bateau';
                        else return false;
                    }
                else return false;
            } catch (\Exception $e){ return false;}
        }

        else if($vertical && !$dir){
            $colonne = rand(1, 10);
            $ligne = rand(1+$longueur-1, 10);  
            try {
                if ($colonnesBateaux[$colonne][$ligne + 1] != 'bateau')
                    for($i=0; $i<$longueur; $i++){
                        if($colonnesBateaux[$colonne][$ligne - $i] != 'bateau' 
                        && $colonnesBateaux[$colonne + 1][$ligne - $i] != 'bateau'
                        && $colonnesBateaux[$colonne - 1][$ligne - $i] != 'bateau'
                        && $colonnesBateaux[$colonne][$ligne - $i - 1] != 'bateau')
                            $colonnesBateaux[$colonne][$ligne - $i] = 'bateau';
                        else return false;
                    }
                else return false;
            } catch (\Exception $e){ return false;}
        }

        else if(!$vertical && $dir){
            $ligne = rand(1, 10);
            $colonne = rand(1, 10-$longueur-1); 
            try {
                if ($colonnesBateaux[$colonne - 1][$ligne] != 'bateau')
                    for($i=0; $i<$longueur; $i++){
                        if($colonnesBateaux[$colonne + $i][$ligne] != 'bateau' 
                        && $colonnesBateaux[$colonne + $i + 1][$ligne] != 'bateau'
                        && $colonnesBateaux[$colonne + $i][$ligne + 1] != 'bateau'
                        && $colonnesBateaux[$colonne + $i][$ligne - 1] != 'bateau')
                            $colonnesBateaux[$colonne + $i][$ligne] = 'bateau';
                        else return false;
                    }
                else return false;
            } catch (\Exception $e){ return false;}
        }

        else {
            $ligne = rand(1, 10);
            $colonne = rand(1+$longueur-1, 10);
            try {
                if ($colonnesBateaux[$colonne + 1][$ligne] != 'bateau')
                    for($i=0; $i<$longueur; $i++){
                        if($colonnesBateaux[$colonne - $i][$ligne] != 'bateau' 
                        && $colonnesBateaux[$colonne - $i - 1][$ligne] != 'bateau'
                        && $colonnesBateaux[$colonne - $i][$ligne + 1] != 'bateau'
                        && $colonnesBateaux[$colonne - $i][$ligne - 1] != 'bateau')
                            $colonnesBateaux[$colonne - $i][$ligne] = 'bateau';
                        else return false;
                    }
                else return false;
            } catch (\Exception $e){ return false;}
        }
        return $colonnesBateaux;
    }
}
