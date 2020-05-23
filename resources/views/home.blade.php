@extends('layouts.app')

@section('content')
    <div id="app" class="container">
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-header">Puissance 4</div>

                    <div class="card-body">
                        <p>Description du jeu + images</p>
                        <button>Jouer</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Morpion</div>

                    <div class="card-body">
                        <p>Description du jeu + images</p>
                        <button>Jouer</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Bataille navale</div>

                    <div class="card-body">
                        <p>Description du jeu + images</p>
                        <button>Jouer</button>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-header">Ajouter un ami</div>

                    <div class="card-body">
                        <p>Mon code ami : {{ $friend_code }} </p>
                        <form action="/addFriend">
                            <p>
                                <label for="idFriend">Ajouter ami : </label>
                                <input id="idFriend" v-model="idFriend" type="text" name="idFriend" placeholder="Code ami" required>
                            </p>
                            <p>
                                <input type="submit" value="Ajouter">
                            </p>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Mes amis</div>

                    <div class="card-body">
                        <ul>
                            <li v-for="amiUpdate in amisUpdate" :key="amiUpdate">
                                <p v-if="amiUpdate.id == id_join" class="text-succes">@{{amiUpdate[0].name}}</p>
                                <p v-else>@{{amiUpdate[0].name}}</p>
                                <form>
                                    <p>
                                        <input type="hidden" name="id_ami" :value="amiUpdate.id" />
                                        <input type="submit" value="Rejoindre">
                                    </p>
                                </form>
                            </li>
                            <li v-for="ami in {{$amis}}" :key="ami">
                                <p v-if="ami.id == id_join" class="text-succes">@{{ami.name}}</p>
                                <p v-else>@{{ami.name}}</p>
                                <form>
                                    <p>
                                        <input type="hidden" v-model="id_join" name="id_ami" :value="ami.id_ami" />
                                        <input type="submit" value="Rejoindre">
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
        window.id = @json($id);
        //window.amis = @json($amis)
    </script>
@endsection