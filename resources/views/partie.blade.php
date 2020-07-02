@extends('layouts.app')

@section('content')
    <div id="partie" class="container" :key="component_key">
        <h1 class="display-4 text-center">@{{user.name}} - VS - {{$ami_name}}</h1>
        <div>
            <audio autoplay="autoplay">
                <source  :src="game.sound" type="audio/mpeg">
                <embed hidden="true" autostart="true" loop="false" :src="game.sound">
            </audio>
        </div>
        <div class="row">
            <div class="col-8">



                <!-- Jeux -->
                <div v-if="!game.type_partie && subscribe == true">
                    <div v-on:click="initPuissance4()" class="game" @mouseover="hover = true" @mouseleave="hover = false" style="background: grey url('{{asset('storage/images/Puissance4.png')}}') center center no-repeat; background-size: cover;">
                        <span v-if="!hover" class="title">Puissance 4</span>
                        <span v-if="hover" class="jouer">Jouer</span>
                    </div>
                    <div v-on:click="initMorpion()" class="game" @mouseover="hover1 = true" @mouseleave="hover1 = false" style="background: grey url('{{asset('storage/images/Morpion.png')}}') center center no-repeat; background-size: cover;">
                        <span v-if="!hover1" class="title">Morpion</span>
                        <span v-if="hover1" class="jouer">Jouer</span>
                    </div>
                    <div v-on:click="initBatailleNavale()" class="game" @mouseover="hover2 = true" @mouseleave="hover2 = false" style="background: grey url('{{asset('storage/images/BatailleNavale2.png')}}') center center no-repeat; background-size: cover;">
                        <span v-if="!hover2" class="title">Bataille Navale</span>
                        <span v-if="hover2" class="jouer">Jouer</span>
                    </div>
                </div>
                <!-- Jeux -->

                <!-- Morpion -->
                <div class="card" v-if="game.type_partie == 'morpion'">
                    <div class="card-header">Morpion</div>

                    <div class="card-body">
                        <h1 v-if="game.winner == user.id">Bravo ! Tu as gagné !</h1>
                        <h1 v-else-if="game.winner == -1">Égalité !</h1>
                        <h1 v-else-if="game.winner != null">Tu as perdu !</h1>
                        <h1 v-else-if="game.tour == user.id">C'est ton tour !</h1>
                        <h1 v-else>Attends pour jouer !</h1>
                        <div  v-for="(n, index) in game.tableau" :key="n" style="display: inline-block;">
                            <form v-if="game.tour != user.id || game.winner != null">
                                <button v-if="n == game.couleur" disabled class="bg-success-hover-disabled morpion"><i class="far fa-circle fa-10x" style="color: #1AA354;"></i></button>
                                <button v-else-if="!n && game.couleur == user.id" disabled class="bg-secondary-hover-disabled morpion"><i class="far fa-circle fa-10x" style="color: #6c757d;"></i></button>
                                <button v-else-if="!n && game.couleur != user.id" disabled class="bg-secondary-hover-disabled morpion"><i class="fas fa-times fa-10x" style="color: #6c757d;"></i></button>
                                <button v-else disabled class="bg-primary-hover-disabled morpion"><i class="fas fa-times fa-10x" style="color: #1672BE;"></i></button>
                            </form>
                            <form v-else>
                                <button v-if="n == game.couleur" disabled class="bg-success-hover-disabled morpion"><i class="far fa-circle fa-10x" style="color: #1AA354;"></i></button>
                                <button v-else-if="!n && game.couleur == user.id" v-on:click="morpion(index)" class="bg-secondary morpion"><i class="far fa-circle fa-10x" style="color: #6c757d;"></i></button>
                                <button v-else-if="!n && game.couleur != user.id" v-on:click="morpion(index)" class="bg-secondary morpion"><i class="fas fa-times fa-10x" style="color: #6c757d;"></i></button>
                                <button v-else disabled class="bg-primary-hover-disabled morpion"><i class="fas fa-times fa-10x" style="color: #1672BE;"></i></button>
                            </form>
                        </div>
                        <button class="col-md-3 ml-1 mt-1 btn btn-primary" v-on:click="initMorpion()">Rejouer</button>
                        <button class="col-md-3 ml-1 mt-1 btn btn-primary" v-on:click="quit()">Quitter</button>
                    </div>
                </div>
                <!-- Morpion -->

                <!-- Puissance 4 -->
                <div class="card" v-if="game.type_partie == 'puissance4'">
                    <div class="card-header">Puissance 4</div>

                    <div class="card-body">
                        <h1 v-if="game.winner == user.id">Bravo ! Tu as gagné !</h1>
                        <h1 v-else-if="game.winner == -1">Égalité !</h1>
                        <h1 v-else-if="game.winner != null">Tu as perdu !</h1>
                        <h1 v-else-if="game.tour == user.id">C'est ton tour !</h1>
                        <h1 v-else>Attends pour jouer !</h1>
                        <div  v-for="(colonne, index) in game.tableau" style="display: inline-block;">
                            <div  v-for="n in colonne" :key="n">
                                <form v-if="game.tour != user.id">
                                    <button v-if="n == game.couleur" disabled class="bg-secondary-hover-disabled puissance4"><i class="fas fa-circle fa-4x" style="color: #e3342f;"></i></button>
                                    <button v-else-if="!n" disabled class="bg-secondary-hover-disabled puissance4"><i class="fas fa-circle fa-4x" style="color: #ffffff;"></i></button>
                                    <button v-else disabled class="bg-secondary-hover-disabled puissance4"><i class="fas fa-circle fa-4x" style="color: #ffed4a;"></i></button>
                                </form>
                                <form v-else>
                                    <button v-if="n == game.couleur" disabled class="bg-secondary-hover-disabled puissance4"><i class="fas fa-circle fa-4x" style="color: #e3342f;"></i></button>
                                    <button v-else-if="!n && game.winner == null"  v-on:click="puissance4(index)" class="bg-secondary puissance4"><i class="fas fa-circle fa-4x" style="color: #ffffff;"></i></button>
                                    <button v-else disabled class="bg-secondary-hover-disabled puissance4"><i class="fas fa-circle fa-4x" style="color: #ffed4a;"></i></button>
                                </form>
                            </div>
                        </div>
                        <button class="col-md-3 ml-1 mt-1 btn btn-primary" v-on:click="initPuissance4()">Rejouer</button>
                        <button class="col-md-3 ml-1 mt-1 btn btn-primary" v-on:click="quit()">Quitter</button>
                    </div>
                </div>
                <!-- Puissance 4 -->

                <!-- Bataille navale -->
                <div class="card" v-if="game.type_partie == 'batailleNavale'">
                    <div class="card-header">Bataille navale</div>

                    <div class="card-body">
                        <h1 v-if="game.winner == user.id">Bravo ! Tu as gagné !</h1>
                        <h1 v-else-if="game.winner != null">Tu as perdu !</h1>
                        <h1 v-else-if="game.tour == user.id">C'est ton tour !</h1>
                        <h1 v-else>Attends pour jouer !</h1>



                        <div v-if="game.couleur == user.id">
                            <div v-for="colonne in game.tableau" :key="colonne" style="display: inline-block; background-color: #3490dc;">
                                <div  v-for="n in colonne" :key="n">
                                    <button v-if="n < 0 && n > -10" disabled class="bg-secondary-hover-disabled bateau"><i class="fas fa-circle" style="color: #6c757d;"></i></button>
                                    <button v-if="n == id_ami" disabled class="bg-primary-hover-disabled batailleNavale"><i class="fas fa-tint" style="color: #1672BE;"></i></button>
                                    <button v-if="n < -10" disabled class="bg-secondary-hover-disabled bateau"><i class="fas fa-fire" style="color:orange;"></i></button>
                                    <button v-if="n == 'coulé'" disabled class="bg-secondary-hover-disabled bateau"><i class="fas fa-skull" style="color: #e3342f;"></i></button>
                                    <button v-if="n == 0" disabled class="bg-primary-hover-disabled batailleNavale"><i class="fas fa-fire" style="color: #3490dc;"></i></button>
                                </div>
                            </div>
                            <div style="width: 15px; display: inline-block;"></div>
                            <div v-for="(colonne, indexColonne) in game.tableau_2" :key="colonne" style="display: inline-block; background-color: #3490dc;">
                                <div  v-for="(n, indexLigne) in colonne" :key="n">
                                    <form v-if="game.tour != user.id || game.winner != null">
                                        <button v-if="n == user.id" disabled class="bg-primary-hover-disabled batailleNavale"><i class="fas fa-tint" style="color: #1672BE;"></i></button>
                                        <button v-if="n < -10" disabled class="bg-secondary-hover-disabled bateau"><i class="fas fa-fire" style="color:orange;"></i></button>
                                        <button v-if="n == 'coulé'" disabled class="bg-secondary-hover-disabled bateau"><i class="fas fa-skull" style="color: #e3342f;"></i></button>
                                        <button v-if="n == 0  || (n < 0  && n > -10)" disabled class="bg-primary-hover-disabled batailleNavale"><i class="fas fa-times fa-xs" style="color: #207CC8;"></i></button>
                                    </form>
                                    <form v-else>
                                        <button v-if="n == user.id" disabled class="bg-primary-hover-disabled batailleNavale"><i class="fas fa-tint" style="color: #1672BE;"></i></button>
                                        <button v-if="n < -10" disabled class="bg-secondary-hover-disabled bateau"><i class="fas fa-fire" style="color:orange;"></i></button>
                                        <button v-if="n == 'coulé'" disabled class="bg-secondary-hover-disabled bateau"><i class="fas fa-skull" style="color: #e3342f;"></i></button>
                                        <button v-if="n == 0 || (n < 0  && n > -10)"  v-on:click="batailleNavale(indexColonne, indexLigne, typeBomb)" class="bg-primary batailleNavale"><i class="fas fa-times fa-xs" style="color: #207CC8;"></i></button>
                                    </form>
                                </div>
                            </div>
                            <div>
                                <button v-if="typeBomb == 1" disabled class="mt-1 btn btn-success" style="height: 75px; width: 75px;">Bombe</button>
                                <button v-else v-on:click="changeBomb(1)" class="mt-1 btn btn-secondary" style="height: 75px; width: 75px;">Bombe</button>
                                <button v-if="typeBomb == 2" disabled class="mt-1 btn btn-success" style="height: 75px; width: 75px;">Missile @{{game.bombs_2[1]}}</button>
                                <button v-else v-on:click="changeBomb(2)" class="mt-1 btn btn-secondary" style="height: 75px; width: 75px;">Missile @{{game.bombs_2[1]}}</button>
                                <button v-if="typeBomb == 3" disabled class="mt-1 btn btn-success" style="height: 75px; width: 75px;">Boom ! @{{game.bombs_2[2]}}</button>
                                <button v-else v-on:click="changeBomb(3)" class="mt-1 btn btn-secondary" style="height: 75px; width: 75px;">Boom ! @{{game.bombs_2[2]}}</button>
                            </div>
                        </div>



                        <div v-else>
                            <div v-for="colonne in game.tableau_2" :key="colonne" style="display: inline-block; background-color: #3490dc;">
                                <div  v-for="n in colonne" :key="n">
                                    <button v-if="n < 0 && n > -10" disabled class="bg-secondary-hover-disabled bateau"><i class="fas fa-circle" style="color: #6c757d;"></i></button>
                                    <button v-if="n == id_ami" disabled class="bg-primary-hover-disabled batailleNavale"><i class="fas fa-tint" style="color: #1672BE;"></i></button>
                                    <button v-if="n < -10" disabled class="bg-secondary-hover-disabled bateau"><i class="fas fa-fire" style="color:orange;"></i></button>
                                    <button v-if="n == 'coulé'" disabled class="bg-secondary-hover-disabled bateau"><i class="fas fa-skull" style="color: #e3342f;"></i></button>
                                    <button v-if="n == 0" disabled class="bg-primary-hover-disabled batailleNavale"><i class="fas fa-fire" style="color: #3490dc;"></i></button>
                                </div>
                            </div>
                            <div style="width: 15px; display: inline-block;"></div>
                            <div v-for="(colonne, indexColonne) in game.tableau" :key="colonne" style="display: inline-block; background-color: #3490dc;">
                                <div  v-for="(n, indexLigne) in colonne" :key="n">
                                    <form v-if="game.tour != user.id || game.winner != null">
                                        <button v-if="n == user.id" disabled class="bg-primary-hover-disabled batailleNavale"><i class="fas fa-tint" style="color: #1672BE;"></i></button>
                                        <button v-if="n < -10" disabled class="bg-secondary-hover-disabled bateau"><i class="fas fa-fire" style="color:orange;"></i></button>
                                        <button v-if="n == 'coulé'" disabled class="bg-secondary-hover-disabled bateau"><i class="fas fa-skull" style="color: #e3342f;"></i></button>
                                        <button v-if="n == 0  || (n < 0  && n > -10)" disabled class="bg-primary-hover-disabled batailleNavale"><i class="fas fa-times fa-xs" style="color: #207CC8;"></i></button>
                                    </form>
                                    <form v-else>
                                        <button v-if="n == user.id" disabled class="bg-primary-hover-disabled batailleNavale"><i class="fas fa-tint" style="color: #1672BE;"></i></button>
                                        <button v-if="n < -10" disabled class="bg-secondary-hover-disabled bateau"><i class="fas fa-fire" style="color:orange;"></i></button>
                                        <button v-if="n == 'coulé'" disabled class="bg-secondary-hover-disabled bateau"><i class="fas fa-skull" style="color: #e3342f;"></i></button>
                                        <button v-if="n == 0 || (n < 0  && n > -10)" v-on:click="batailleNavale(indexColonne, indexLigne, typeBomb)" class="bg-primary batailleNavale"><i class="fas fa-times fa-xs" style="color: #207CC8;"></i></button>
                                    </form>
                                </div>
                            </div>
                            <div>
                                <button v-if="typeBomb == 1" disabled class="mt-1 btn btn-success" style="height: 75px; width: 75px;">Bombe</button>
                                <button v-else v-on:click="changeBomb(1)" class="mt-1 btn btn-secondary" style="height: 75px; width: 75px;">Bombe</button>
                                <button v-if="typeBomb == 2" disabled class="mt-1 btn btn-success" style="height: 75px; width: 75px;">Missile @{{game.bombs[1]}}</button>
                                <button v-else v-on:click="changeBomb(2)" class="mt-1 btn btn-secondary" style="height: 75px; width: 75px;">Missile @{{game.bombs[1]}}</button>
                                <button v-if="typeBomb == 3" disabled class="mt-1 btn btn-success" style="height: 75px; width: 75px;">Boom ! @{{game.bombs[2]}}</button>
                                <button v-else v-on:click="changeBomb(3)" class="mt-1 btn btn-secondary" style="height: 75px; width: 75px;">Boom ! @{{game.bombs[2]}}</button>
                            </div>
                        </div>



                        <button class="col-md-3 ml-1 mt-1 btn btn-primary" v-on:click="initBatailleNavale()">Rejouer</button>
                        <button class="col-md-3 ml-1 mt-1 btn btn-primary" v-on:click="quit()">Quitter</button>
                    </div>
                </div>
                <!-- Bataille navale -->



            </div>
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <button v-if="subscribe == true" class="btn btn-primary" id="makeCall" v-on:click="callUser()">Appel vidéo <i class="fas fa-phone"></i></button>
                        <button class="btn btn-primary" disabled id="endCall" v-on:click="endCurrentCall()">Fermer vidéo <i class="fas fa-phone-slash"></i></button>
                    </div>

                    <div class="card-body">
                        <video id="selfview" autoplay playsinline style="height: 50px; width: 100px;"></video>
                        <video id="remoteview" autoplay playsinline style="height: 100px; width: 200px;"></video>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">CHAT</div>

                    <div class="card-body">
                        <div v-for="msg in messages">
                            <p>@{{msg}}</p>
                        </div>
                        <div class="form-group row">
                            <input id="message" class="col-md-7 form-control" v-model="message" type="text" name="message" placeholder="Ecrivez un message ici !" required>
                            <button v-if="subscribe == true" class="col-md-3 ml-1 btn btn-primary" v-on:click="sendMessage(message)">Envoyer</button>
                        </div>    
                    </div>
                </div>
            </div>
        </div>
    </div>   
    <script>
        window.user = @json($user);
        window.id_ami = @json($id_ami);
        window.idSession = @json($idSession);
        window.amis = null;
    </script>    
@endsection