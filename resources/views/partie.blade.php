@extends('layouts.app')

@section('content')
    <div id="partie" class="container">
        <h1>{{$id}} - VS - {{$id_join}}</h1>
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
    </script>    
@endsection