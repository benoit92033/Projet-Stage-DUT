<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\GameEvent;
use App\BatailleNavale;

class BatailleNavaleController extends Controller
{
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
            
            foreach($bateaux as $index => $longueurBateau){
                $gen = false;
                while($gen == false){
                    $gen = $this->genereBoat($longueurBateau ,$colonnes_2, $index + 1);
                }
                $colonnes_2 = $gen;
            }

            foreach($bateaux as $index => $longueurBateau){
                $gen = false;
                while($gen == false){
                    $gen = $this->genereBoat($longueurBateau ,$colonnes, $index + 1);
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
            
            $partie = new BatailleNavale($id, $colonnes, $colonnes_2,'batailleNavale');
        }

        else {
            $partie = $request->partie;
            $partie = json_decode($partie);
            $partie->sound = "/sounds/plouf.mp3";

            //Différentes bombes
            if($partie->couleur == $id){
                if ($request->typeBomb == 2 && $partie->bombs_2[1] > 0){
                    $bombs = [
                        [0, 0],
                        [1, 0],
                        [0, 1],
                        [-1, 0],
                        [0, -1]
                    ];
                    $partie->bombs_2[1] -= 1;
                }
                else if ($request->typeBomb == 3 && $partie->bombs_2[2] > 0){
                    $bombs = [
                        [0, 0],
                        [1, 1],
                        [1, -1],
                        [-1, 1],
                        [-1, -1],
                        [0, 2],
                        [2, 0],
                        [-2, 0],
                        [0, -2]
                    ];
                    $partie->bombs_2[2] -= 1;
                }

                else {
                    $bombs = [
                        [0, 0]
                    ];
                }
            }
            
            else {
                if ($request->typeBomb == 2 && $partie->bombs[1] > 0){
                    $bombs = [
                        [0, 0],
                        [1, 0],
                        [0, 1],
                        [-1, 0],
                        [0, -1]
                    ];
                    $partie->bombs[1] -= 1;
                }
                else if ($request->typeBomb == 3 && $partie->bombs[2] > 0){
                    $bombs = [
                        [0, 0],
                        [1, 1],
                        [1, -1],
                        [-1, 1],
                        [-1, -1],
                        [0, 2],
                        [2, 0],
                        [-2, 0],
                        [0, -2]
                    ];
                    $partie->bombs[2] -= 1;
                }

                else {
                    $bombs = [
                        [0, 0]
                    ];
                }
            }

            $partie->tour = $id_ami;

            foreach($bombs as $bomb){
                try{
                    if($partie->couleur == $id){
                        if($partie->tableau_2[$request->indexColonne + $bomb[0]][$request->indexLigne + $bomb[1]] < 0
                            && $partie->tableau_2[$request->indexColonne + $bomb[0]][$request->indexLigne + $bomb[1]] > -10){
                            $partie->tableau_2[$request->indexColonne + $bomb[0]][$request->indexLigne + $bomb[1]] -= 10;
                            $partie->tour = $id;
                            $partie->sound = "/sounds/boom.mp3";
                        }
                        else if($partie->tableau_2[$request->indexColonne + $bomb[0]][$request->indexLigne + $bomb[1]] == null)
                            $partie->tableau_2[$request->indexColonne + $bomb[0]][$request->indexLigne + $bomb[1]] = $id;
                    }

                    else{
                        if($partie->tableau[$request->indexColonne + $bomb[0]][$request->indexLigne + $bomb[1]] < 0
                            && $partie->tableau[$request->indexColonne + $bomb[0]][$request->indexLigne + $bomb[1]] > -10){
                            $partie->tableau[$request->indexColonne + $bomb[0]][$request->indexLigne + $bomb[1]] -= 10;
                            $partie->tour = $id;
                            $partie->sound = "/sounds/boom.mp3";
                        }
                        else if($partie->tableau[$request->indexColonne + $bomb[0]][$request->indexLigne + $bomb[1]] == null)
                            $partie->tableau[$request->indexColonne + $bomb[0]][$request->indexLigne + $bomb[1]] = $id;
                    }
                } catch(\Exception $e){}
            }
            if($partie->couleur == $id){
                foreach($partie->bateaux_2 as $key => $bat){
                    $compteur = 0;
                    if ($bat != null){
                        foreach($partie->tableau_2 as $colonne){
                            if(!in_array(-$bat, $colonne)){
                                $compteur += 1;
                            }
                        }
                        if ($compteur == 10){
                            $partie->tour = $id_ami;
                            foreach($partie->tableau_2 as $indexCol => $colonne)
                                foreach($colonne as $indexLigne => $elm){
                                    $test = -$bat -10;
                                    if ($elm == $test)
                                        $partie->tableau_2[$indexCol][$indexLigne] = 'coulé';
                                }
                            $partie->bateaux_2[$key] = null;
                        }
                    }
                }
            }
            else {
                foreach($partie->bateaux as $key => $bat){
                    $compteur = 0;
                    if ($bat != null){
                        foreach($partie->tableau as $colonne){
                            if(!in_array(-$bat, $colonne)){
                                $compteur += 1;
                            }
                        }
                        if ($compteur == 10){
                            $partie->tour = $id_ami;
                            foreach($partie->tableau as $indexCol => $colonne)
                                foreach($colonne as $indexLigne => $elm){
                                    $test = -$bat -10;
                                    if ($elm == $test)
                                        $partie->tableau[$indexCol][$indexLigne] = 'coulé';
                                }
                            $partie->bateaux[$key] = null;
                        }
                    }
                }
            }

            /* Détection WINNER*/
            $compteur = 0;
            foreach($partie->bateaux_2 as $bat){
                if(!$bat)
                    $compteur += 1;
            }
            if ($compteur == 5)
                $partie->winner = $partie->couleur;
            
            $compteur = 0;
            foreach($partie->bateaux as $bat){
                if(!$bat)
                    $compteur += 1;
            } 
            if ($compteur == 5){
                if($partie->couleur == $id)
                    $partie->winner = $id_ami;
                else
                    $partie->winner = $id;
            }      
        }

        broadcast(new GameEvent($partie, $id_ami));

        return view('partie', [
            "game" => $partie,
            "id_join" => $id_ami,
            "id" => $id
        ]);
    }

    public function genereBoat($longueur, $colonnesBateaux, $index){
        $dir = rand(0,1); 
        $vertical = rand(0,1);
        
        if($vertical && $dir){
            $colonne = rand(1, 10);
            $ligne = rand(1, 10-$longueur-1); 
            try {
                if ($colonnesBateaux[$colonne][$ligne - 1] == null)
                    for($i=0; $i<$longueur; $i++){
                        if($colonnesBateaux[$colonne][$ligne + $i] == null
                        && $colonnesBateaux[$colonne + 1][$ligne + $i] == null 
                        && $colonnesBateaux[$colonne - 1][$ligne + $i] == null
                        && $colonnesBateaux[$colonne][$ligne + $i + 1] == null)
                            $colonnesBateaux[$colonne][$ligne + $i] = -$index;
                        else return false;
                    }
                else return false;
            } catch (\Exception $e){ return false;}
        }

        else if($vertical && !$dir){
            $colonne = rand(1, 10);
            $ligne = rand(1+$longueur-1, 10);  
            try {
                if ($colonnesBateaux[$colonne][$ligne + 1] == null)
                    for($i=0; $i<$longueur; $i++){
                        if($colonnesBateaux[$colonne][$ligne - $i] == null
                        && $colonnesBateaux[$colonne + 1][$ligne - $i] == null
                        && $colonnesBateaux[$colonne - 1][$ligne - $i] == null
                        && $colonnesBateaux[$colonne][$ligne - $i - 1] == null)
                            $colonnesBateaux[$colonne][$ligne - $i] = -$index;
                        else return false;
                    }
                else return false;
            } catch (\Exception $e){ return false;}
        }

        else if(!$vertical && $dir){
            $ligne = rand(1, 10);
            $colonne = rand(1, 10-$longueur-1); 
            try {
                if ($colonnesBateaux[$colonne - 1][$ligne] == null)
                    for($i=0; $i<$longueur; $i++){
                        if($colonnesBateaux[$colonne + $i][$ligne] == null
                        && $colonnesBateaux[$colonne + $i + 1][$ligne] == null
                        && $colonnesBateaux[$colonne + $i][$ligne + 1] == null
                        && $colonnesBateaux[$colonne + $i][$ligne - 1] == null)
                            $colonnesBateaux[$colonne + $i][$ligne] = -$index;
                        else return false;
                    }
                else return false;
            } catch (\Exception $e){ return false;}
        }

        else {
            $ligne = rand(1, 10);
            $colonne = rand(1+$longueur-1, 10);
            try {
                if ($colonnesBateaux[$colonne + 1][$ligne] == null)
                    for($i=0; $i<$longueur; $i++){
                        if($colonnesBateaux[$colonne - $i][$ligne] == null
                        && $colonnesBateaux[$colonne - $i - 1][$ligne] == null
                        && $colonnesBateaux[$colonne - $i][$ligne + 1] == null
                        && $colonnesBateaux[$colonne - $i][$ligne - 1] == null)
                            $colonnesBateaux[$colonne - $i][$ligne] = -$index;
                        else return false;
                    }
                else return false;
            } catch (\Exception $e){ return false;}
        }
        return $colonnesBateaux;
    }
}
