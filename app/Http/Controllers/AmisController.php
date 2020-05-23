<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Amis;
use App\AmisEvent;
use App\JoinAmisEvent;

class AmisController extends Controller
{
    public function addFriend(Request $request)
    {
        $id = Auth::user()->id;
        $friend_code = Auth::user()->friend_code;

        if ($request->idFriend){
            $id_ami = User::query()->select('id')
                ->where('friend_code', '=', $request->idFriend)->get()->toArray();

            if ($id && $id_ami[0]["id"]){
                Amis::create([
                    'id' => $id,
                    'id_ami' => $id_ami[0]["id"]
                ]);

                Amis::create([
                    'id' => $id_ami[0]["id"],
                    'id_ami' => $id
                ]);

                $amis_amis = Amis::query()->select('name', 'users.id as id_ami')
                    ->join('users', 'users.id', '=', 'id_ami')
                    ->where('amis.id', '=', $id_ami[0]["id"])->get()->toArray();

                broadcast(new AmisEvent($amis_amis, $id_ami[0]["id"]));

                $amis = Amis::query()->select('name', 'users.id as id_ami')
                    ->join('users', 'users.id', '=', 'id_ami')
                    ->where('amis.id', '=', $id)->get()->toArray();

                return view('home', [
                    "id" => Auth::user()->id,
                    "friend_code" => $friend_code,
                    "messageNON" => "Ami ajouté",
                    "amis" => $amis
                ]);
            }
            else {
                return view('home', [
                    "id" => Auth::user()->id,
                    "friend_code" => $friend_code,
                    "messageNON" => "Code ami non valide"
                ]);
            }
        }

        else {
            return view('home', [
                "id" => Auth::user()->id,
                "friend_code" => $friend_code,
                "messageNON" => "error"
            ]);
        }
    }

    public function joinFriend(Request $request)
    {
        $id_join = $request->id_join;

        if(!isset($request->broadcast))
            broadcast(new JoinAmisEvent($id_join, Auth::user()->id));

        return view('partie',[
            "id_join" => $id_join,
            "id" => Auth::user()->id,
        ]);
    }
}
