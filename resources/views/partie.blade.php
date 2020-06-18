@extends('layouts.app')

@section('content')
    <div id="partie" class="container" :key="component_key">
        <h1>Moi : @{{user.name}} - VS - Lui : @{{id_ami}}</h1>
        <div>
            <audio autoplay="autoplay">
                <source  :src="game.sound" type="audio/mpeg">
                <embed hidden="true" autostart="true" loop="false" :src="game.sound">
            </audio>
        </div>
        <div class="row">
            <div class="col-8">



                <!-- Jeux -->
                <div v-if="!game.type_partie">
                    <div class="card">
                        <div class="card-header">Puissance 4</div>

                        <div class="card-body">
                            <p>Description du jeu + images</p>
                            <button v-on:click="initPuissance4()">Jouer</button>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">Morpion</div>

                        <div class="card-body">
                            <p>Description du jeu + images</p>
                            <button v-on:click="initMorpion()">Jouer</button>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">Bataille navale</div>

                        <div class="card-body">
                            <p>Description du jeu + images</p>
                            <button v-on:click="initBatailleNavale()">Jouer</button>
                        </div>
                    </div>
                </div>
                <!-- Jeux -->

                <!-- Morpion -->
                <div class="card" v-if="game.type_partie == 'morpion'">
                    <div class="card-header">Morpion</div>

                    <div v-if="game.winner == null" class="card-body">
                        <h1 v-if="game.tour == user.id">C'est votre tour !</h1>
                        <h1 v-else>C'est pas votre tour !</h1>
                        <div  v-for="(n, index) in game.tableau" :key="n" style="display: inline-block;">
                            <form v-if="game.tour != user.id">
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
                    </div>
                    <div v-else class="card-body">
                        <p>Winner : @{{game.winner}}</p>
                        <button v-on:click="initMorpion()">Rejouer</button>
                        <button v-on:click="quit()">Quitter</button>
                    </div>
                </div>
                <!-- Morpion -->

                <!-- Puissance 4 -->
                <div class="card" v-if="game.type_partie == 'puissance4'">
                    <div class="card-header">Puissance 4</div>

                    <div v-if="game.winner == null" class="card-body">
                        <h1 v-if="game.tour == user.id">C'est votre tour !</h1>
                        <h1 v-else>C'est pas votre tour !</h1>
                        <div  v-for="(colonne, index) in game.tableau" style="display: inline-block;">
                            <div  v-for="n in colonne" :key="n">
                                <form v-if="game.tour != user.id">
                                    <button v-if="n == game.couleur" disabled class="bg-secondary-hover-disabled puissance4"><i class="fas fa-circle fa-4x" style="color: #e3342f;"></i></button>
                                    <button v-else-if="!n" disabled class="bg-secondary-hover-disabled puissance4"><i class="fas fa-circle fa-4x" style="color: #ffffff;"></i></button>
                                    <button v-else disabled class="bg-secondary-hover-disabled puissance4"><i class="fas fa-circle fa-4x" style="color: #ffed4a;"></i></button>
                                </form>
                                <form v-else>
                                    <button v-if="n == game.couleur" disabled class="bg-secondary-hover-disabled puissance4"><i class="fas fa-circle fa-4x" style="color: #e3342f;"></i></button>
                                    <button v-else-if="!n"  v-on:click="puissance4(index)" class="bg-secondary puissance4"><i class="fas fa-circle fa-4x" style="color: #ffffff;"></i></button>
                                    <button v-else disabled class="bg-secondary-hover-disabled puissance4"><i class="fas fa-circle fa-4x" style="color: #ffed4a;"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div v-else class="card-body">
                        <p>Winner : @{{game.winner}}</p>
                        <button v-on:click="initPuissance4()">Rejouer</button>
                        <button v-on:click="quit()">Quitter</button>
                    </div>
                </div>
                <!-- Puissance 4 -->

                <!-- Bataille navale -->
                <div class="card" v-if="game.type_partie == 'batailleNavale'">
                    <div class="card-header">Bataille navale</div>

                    <div v-if="game.winner == null" class="card-body">
                        <h1 v-if="game.tour == user.id">C'est votre tour !</h1>
                        <h1 v-else>C'est pas votre tour !</h1>



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
                                    <form v-if="game.tour != user.id">
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
                                <button v-if="typeBomb == 1" disabled class="bg-warning" style="height: 75px; width: 75px;">Bombe</button>
                                <button v-else v-on:click="changeBomb(1)" class="bg-secondary" style="height: 75px; width: 75px;">Bombe</button>
                                <button v-if="typeBomb == 2" disabled class="bg-warning" style="height: 75px; width: 75px;">Missile @{{game.bombs_2[1]}}</button>
                                <button v-else v-on:click="changeBomb(2)" class="bg-secondary" style="height: 75px; width: 75px;">Missile @{{game.bombs_2[1]}}</button>
                                <button v-if="typeBomb == 3" disabled class="bg-warning" style="height: 75px; width: 75px;">Boom ! @{{game.bombs_2[2]}}</button>
                                <button v-else v-on:click="changeBomb(3)" class="bg-secondary" style="height: 75px; width: 75px;">Boom ! @{{game.bombs_2[2]}}</button>
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
                                    <form v-if="game.tour != user.id">
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
                                <button v-if="typeBomb == 1" disabled class="bg-warning" style="height: 75px; width: 75px;">Bombe</button>
                                <button v-else v-on:click="changeBomb(1)" class="bg-secondary" style="height: 75px; width: 75px;">Bombe</button>
                                <button v-if="typeBomb == 2" disabled class="bg-warning" style="height: 75px; width: 75px;">Missile @{{game.bombs[1]}}</button>
                                <button v-else v-on:click="changeBomb(2)" class="bg-secondary" style="height: 75px; width: 75px;">Missile @{{game.bombs[1]}}</button>
                                <button v-if="typeBomb == 3" disabled class="bg-warning" style="height: 75px; width: 75px;">Boom ! @{{game.bombs[2]}}</button>
                                <button v-else v-on:click="changeBomb(3)" class="bg-secondary" style="height: 75px; width: 75px;">Boom ! @{{game.bombs[2]}}</button>
                            </div>
                        </div>



                    </div>
                    <div v-else class="card-body">
                        <p>Winner : @{{game.winner}}</p>
                        <button v-on:click="initBatailleNavale()">Rejouer</button>
                        <button v-on:click="quit()">Quitter</button>
                    </div>
                </div>
                <!-- Bataille navale -->



            </div>
            <div class="col">
                <div class="card">
                    <div class="card-header">VISIO</div>

                    <div class="card-body" style="height: 130px;">
                        visio
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">CHAT</div>

                    <div class="card-body" style="height: 130px;">
                        chat
                    </div>
                </div>
            </div>
        </div>
    </div>   
    <script>
        window.user = @json($user);
        window.id_ami = @json($id_ami);
        window.idGame = @json($idGame);
        window.game = {};
        window.amis = null;
        window.component_key = 0;
        window.typeBomb = 1
    </script>    
@endsection