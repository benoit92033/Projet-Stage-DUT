@extends('layouts.app')

@section('content')
    <div id="partie" class="container">
        <h1>Moi : {{$id}} - VS - Lui : {{$id_join}}</h1>
        <div class="row">
            <div class="col-8">



                <!-- Jeux -->
                <div v-if="!game.type_partie">
                    <div class="card">
                        <div class="card-header">Puissance 4</div>

                        <div class="card-body">
                            <p>Description du jeu + images</p>
                            <button v-on:click="play({{$id_join}}, 'puissance4')">Jouer</button>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">Morpion</div>

                        <div class="card-body">
                            <p>Description du jeu + images</p>
                            <button v-on:click="play({{$id_join}}, 'morpion')">Jouer</button>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">Bataille navale</div>

                        <div class="card-body">
                            <p>Description du jeu + images</p>
                            <button disabled v-on:click="play({{$id_join}}, 'batailleNavale')">Jouer</button>
                        </div>
                    </div>
                </div>
                <!-- Jeux -->

                <!-- Morpion -->
                <div class="card" v-if="game.type_partie == 'morpion'">
                    <div class="card-header">Morpion</div>

                    <div v-if="game.winner == null" class="card-body">
                        <h1 v-if="game.tour == id">C'est votre tour !</h1>
                        <h1 v-else>C'est pas ton tour !</h1>
                        <div  v-for="(n, index) in game.pions" :key="n" style="display: inline-block;">
                            <form v-if="game.tour != id">
                                <button v-if="n == id" disabled class="bg-success" style="height: 200px; width: 200px; border: solid black 1px;"></button>
                                <button v-if="n == {{$id_join}}" disabled class="bg-primary" style="height: 200px; width: 200px; border: solid black 1px;"></button>
                                <button v-if="!n" disabled class="bg-secondary" style="height: 200px; width: 200px; border: solid black 1px;"></button>
                            </form>
                            <form v-else action="/morpion" method="post">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                <input type="hidden" name="id_ami" value="{{$id_join}}">
                                <input type="hidden" name="index" :value="index">
                                <input type="hidden" name="partie" :value="JSON.stringify(game)">
                                <button v-if="n == id" disabled class="bg-success" style="height: 200px; width: 200px; border: solid black 1px;"></button>
                                <button v-if="n == {{$id_join}}" disabled class="bg-primary" style="height: 200px; width: 200px; border: solid black 1px;"></button>
                                <button v-if="!n"  type="submit" class="bg-secondary" style="height: 200px; width: 200px; border: solid black 1px;"></button>
                            </form>
                        </div>
                    </div>
                    <div v-else class="card-body">
                        <p>Winner : @{{game.winner}}</p>
                        <button v-on:click="play({{$id_join}}, 'morpion')">Rejouer</button>
                        <form action="/joinFriend">
                            <p>
                                <input type="hidden" name="id_join" value="{{$id_join}}" />
                                <input type="submit" value="Quitter">
                            </p>
                        </form>
                    </div>
                </div>
                <!-- Morpion -->

                <!-- Puissance 4 -->
                <div class="card" v-if="game.type_partie == 'puissance4'">
                    <div class="card-header">Puissance 4</div>

                    <div v-if="game.winner == null" class="card-body">
                        <h1 v-if="game.tour == id">C'est votre tour !</h1>
                        <h1 v-else>C'est pas ton tour !</h1>
                        <div  v-for="(colonne, index) in game.pions" :key="colonne" style="display: inline-block;">
                            <div  v-for="n in colonne" :key="n">
                                <form v-if="game.tour != id">
                                    <button v-if="n == id" disabled class="bg-danger" style="height: 75px; width: 75px; border: solid black 1px;"></button>
                                    <button v-if="n == {{$id_join}}" disabled class="bg-warning" style="height: 75px; width: 75px; border: solid black 1px;"></button>
                                    <button v-if="!n" disabled class="bg-secondary" style="height: 75px; width: 75px; border: solid black 1px;"></button>
                                </form>
                                <form v-else action="/puissance4" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                    <input type="hidden" name="id_ami" value="{{$id_join}}">
                                    <input type="hidden" name="index" :value="index">
                                    <input type="hidden" name="partie" :value="JSON.stringify(game)">
                                    <button v-if="n == id" disabled class="bg-danger" style="height: 75px; width: 75px; border: solid black 1px;"></button>
                                    <button v-if="n == {{$id_join}}" disabled class="bg-warning" style="height: 75px; width: 75px; border: solid black 1px;"></button>
                                    <button v-if="!n"  type="submit" class="bg-secondary" style="height: 75px; width: 75px; border: solid black 1px;"></button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div v-else class="card-body">
                        <p>Winner : @{{game.winner}}</p>
                        <button v-on:click="play({{$id_join}},  'puissance4')">Rejouer</button>
                        <form action="/joinFriend">
                            <p>
                                <input type="hidden" name="id_join" value="{{$id_join}}" />
                                <input type="submit" value="Quitter">
                            </p>
                        </form>
                    </div>
                </div>
                <!-- Puissance 4 -->



            </div>
            <div class="col">
                <div class="card">
                    <div class="card-header">VISIO</div>

                    <div class="card-body" style="height: 200px;">
                        visio
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">CHAT</div>

                    <div class="card-body" style="height: 200px;">
                        chat
                    </div>
                </div>
            </div>
        </div>
    </div>   
    <script>
        window.id = @json($id);
        window.game = @json($game);
        window.amis = null;
    </script>    
@endsection