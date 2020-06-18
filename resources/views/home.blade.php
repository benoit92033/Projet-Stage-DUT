@extends('layouts.app')

@section('content')
    <div id="home" class="container">
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-header">Puissance 4</div>

                    <div class="card-body">
                        <p>Description du jeu + images</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Morpion</div>

                    <div class="card-body">
                        <p>Description du jeu + images</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Bataille navale</div>

                    <div class="card-body">
                        <p>Description du jeu + images</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-header">Ajouter un ami</div>

                    <div class="card-body">
                        <p>Mon code ami : @{{user.friend_code}} </p>
                        <form action="/addFriend">
                            <p>
                                <label for="idFriend">Ajouter ami : </label>
                                <input id="idFriend" type="text" name="idFriend" placeholder="Code ami" required>
                            </p>
                            <p>
                                <input type="submit" value="Ajouter">
                            </p>
                        </form>
                        <p class="text-danger">{{$messageErreur ?? ''}}</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Mes amis</div>

                    <div class="card-body">
                        <ul>
                            <li v-for="ami in amis" :key="ami">
                                @{{ami.name}}
                                <form action="/joinFriend">
                                    <p>
                                        <input type="hidden" name="id_join" :value="ami.id_ami" />
                                        <input type="submit" value="Rejoindre">
                                    </p>
                                </form>
                                <form action="/delFriend">
                                    <p>
                                        <input type="hidden" name="id_ami" :value="ami.id_ami" />
                                        <input type="submit" value="Supprimer">
                                    </p>
                                </form>
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
        window.idGame = null;
    </script>
@endsection