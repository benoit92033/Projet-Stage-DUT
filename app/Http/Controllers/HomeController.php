<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Amis;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $friend_code = Auth::user()->friend_code;

        $amis = Amis::query()->select('name', 'id_ami')
            ->join('users', 'users.id', '=', 'id_ami')
            ->where('amis.id', '=', Auth::user()->id)->get()->toArray();

        return view('home', [
            "id" => Auth::user()->id,
            "friend_code" => $friend_code,
            "amis" => json_encode($amis)
        ]);
    }
}
