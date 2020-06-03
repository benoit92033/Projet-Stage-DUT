<?php

namespace App;

class Partie
{
    public $tour;
    public $tableau;
    public $tableau_2;
    public $winner;
    public $type_partie;
    public $couleur;
    /*public $lines = [
        [0, 1, 2],
        [3, 4, 5],
        [6, 7, 8],
        [0, 3, 6],
        [1, 4, 7],
        [2, 5, 8],
        [0, 4, 8],
        [2, 4, 6]
    ];*/

    public function __construct($tour, $tableau, $tableau_2, $type_partie){
        $this->tour = $tour;
        $this->tableau = $tableau;
        $this->tableau_2 = $tableau_2;
        $this->winner = null;
        $this->type_partie = $type_partie;
        $this->couleur = $tour;
    }

    /*public function calculateWinner() {
        foreach($this->lines as $line) {
            if ($this->tableau[0] && $this->tableau[0] === $this->tableau[1] && $this->tableau[0] === $this->tableau[2])
                $this->winner = $this->tableau[0];
        }
        return;
    }*/
}