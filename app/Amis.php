<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Amis extends Model
{
    protected $table = "amis";
    protected $primaryKey = ['id1', 'id2'];
    public $timestamps = false;
    public $incrementing = false;
}