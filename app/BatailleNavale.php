<?php

namespace App;

class BatailleNavale
{
    public $tour;
    public $tableau;
    public $tableau_2;
    public $winner;
    public $type_partie;
    public $couleur;
    public $bateaux;
    public $bateaux_2;
    public $bombs;
    public $bombs_2;

    public function __construct($tour, $tableau, $tableau_2, $type_partie){
        $this->tour = $tour;
        $this->tableau = $tableau;
        $this->tableau_2 = $tableau_2;
        $this->winner = null;
        $this->type_partie = $type_partie;
        $this->couleur = $tour;
        $this->bateaux = [1,2,3,4,5];
        $this->bateaux_2 = [1,2,3,4,5];
        $this->bombs = [999,3,1];
        $this->bombs_2 = [999,3,1];
    }
}