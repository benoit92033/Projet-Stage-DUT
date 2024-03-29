<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Amis extends Model
{
    protected $table = "amis";
    protected $primaryKey = ['id', 'id_ami'];
    protected $fillable = ['id', 'id_ami'];
    public $timestamps = false;
    public $incrementing = false;
}