<?php

namespace App;

class Partie
{
    public $tour;
    public $pions;
    public $winner;
    public $type_partie;
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

    public function __construct($tour, $pions, $type_partie){
        $this->tour = $tour;
        $this->pions = $pions;
        $this->winner = null;
        $this->type_partie = $type_partie;
    }

    /*public function calculateWinner() {
        foreach($this->lines as $line) {
            if ($this->pions[0] && $this->pions[0] === $this->pions[1] && $this->pions[0] === $this->pions[2])
                $this->winner = $this->pions[0];
        }
        return;
    }*/
}