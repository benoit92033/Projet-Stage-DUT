<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Partie extends Model
{
    protected $table = "partie";
    protected $primaryKey = ['id', 'id_ami'];
    protected $fillable = ['id', 'id_ami', 'tour'];
    public $timestamps = false;
}