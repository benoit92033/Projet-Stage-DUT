@extends('layouts.app')

@section('content')
    <div id="home" class="container-sm">
        <div class="row">
            <div class="col-8">
                <div class="card mb-4">
                    <div class="card-header">Puissance 4</div>

                    <div class="card-body">
                        <img style="height:200px;" src="{{asset('storage/images/Puissance4.png')}}" alt="Puissance 4">
                        <p>Puissance 4 est un jeu de stratégie très connu qui convient à tout le monde.</p>
                        <p><b>Comment jouer :</b> Déposez vos pions dans les colonnes de la grille du jeu en appuyant sur la colonne choisie. Faites une ligne d'au moins quatre jetons soit verticalement, horizontalement ou en diagonale avant votre adversaire.</p>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">Morpion</div>

                    <div class="card-body">
                        <img style="height:200px;" src="{{asset('storage/images/Morpion.png')}}" alt="Morpion">
                        <p>Le morpion est un jeu de réflexion se pratiquant à deux joueurs au tour par tour et dont le but est de créer le premier un alignement sur une grille.</p>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">Bataille navale</div>

                    <div class="card-body">
                        <img style="height:200px;" src="{{asset('storage/images/BatailleNavale.png')}}" alt="Bataille Navale">
                        <p>La bataille navale, appelée aussi touché-coulé, est un jeu dans lequel deux joueurs doivent tenter de « toucher » les navires adverses.</p>
                        <p>Le gagnant est celui qui parvient à couler tous les navires de l'adversaire avant que tous les siens ne le soient.</p>
                        <p>On dit qu'un navire est coulé si chacune de ses cases a été touchées par un coup de l'adversaire.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card mb-4">
                    <div class="card-header">Ajouter un ami</div>

                    <div class="card-body">
                        <p>Mon code ami : @{{user.friend_code}} </p>
                        <form action="/addFriend">
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="idFriend">Ajouter ami : </label>
                                <div class="col-md-5">
                                    <input class="form-control" id="idFriend" type="text" name="idFriend" placeholder="Code ami" required>
                                </div>
                                <div class="form-group row mb-0 ml-1">
                                        <input type="submit" value="Ajouter" class="btn btn-primary">
                                </div>
                            </div>
                        </form>
                        <p class="text-danger">{{$messageErreur ?? ''}}</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Mes amis</div>

                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item" v-for="ami in amis" :key="ami">
                                <div class="form-group row">
                                    <label class="col col-form-label">@{{ami.name}}</label>
                                    <div class="col-md-0">
                                        <form action="/joinFriend">
                                            <input type="hidden" name="id_join" :value="ami.id_ami" />
                                            <input type="hidden" name="ami_name" :value="ami.name" />
                                            <input class="btn btn-primary" type="submit" value="Rejoindre">
                                        </form>
                                    </div>
                                    <form action="/delFriend">
                                        <div class="col-md-1">
                                            <input type="hidden" name="id_ami" :value="ami.id_ami" />
                                            <button class="btn btn-danger" type="submit"><i class='fas fa-times'></i></button>
                                        </div>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.user = @json($user);
        window.amis = @json($amis);
        window.game = null;
        window.typeBomb = null;
        window.id_ami = null;
        window.component_key = null;
        window.idSession = null;
        window.messages = null;
        window.caller = null;
        window.localUserMedia = null;
        window.subscribe = null;
    </script>
@endsection