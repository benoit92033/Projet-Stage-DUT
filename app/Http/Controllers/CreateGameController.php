<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class CreateGameController extends Controller
{
    public function index(Request $request)
    {
        $id = Auth::user()->id;
        $id_ami = $request->id_ami;

        $partie = Partie::create([
            'id'=> $id,
            'id_ami'=> $id_ami,
            'tour'=> true
        ]);

        return view('partie', [
            "partie" => json_encode($partie),
        ]);
    }
}
