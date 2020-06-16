/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('@fortawesome/fontawesome-free/js/all.js');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('home', require('./components/Home.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const home = new Vue({
    el: '#home',

    data: {
        id,
        idFriend: '',
        amis
    }
});

const partie = new Vue({
    el: '#partie',

    data: {
        id,
        game,
        typeBomb,
        test
    },   

    methods:{
        play(id_join, type_jeu){

            echo.private(`chat.${id_join}`)
                .whisper('typing', {
                    user: 'moi',
                    id: this.id
                });

            /*if (type_jeu == 'batailleNavale')
                window.location.href = "/batailleNavale?id_ami=" + id_join;
            else if (type_jeu == 'morpion')
                window.location.href = "/morpion?id_ami=" + id_join;
            else
                window.location.href = "/puissance4?id_ami=" + id_join;*/
        },

        initMorpion(id_join, ){

            echo.private(`chat.${id_join}`)
                .whisper('typing', {
                    user: 'moi',
                    id: this.id
                });

                $id = Auth::user()->id;
                $id_ami = $request->id_ami;
        
                if (!$request->partie){
                    $tableau = array_fill(0, 9, null);
                    $partie = new Partie($id, $tableau, 'morpion');
                }
        
                else {
                    $partie = $request->partie;
                    $partie = json_decode($partie);
                    $partie->tableau[$request->index] = $id;
                    $partie->tour = $id_ami;
                    $partie->sound = "/sounds/CraieMorpion.mp3";
                
                    /* Détection WINNER*/ 
                    $lines = [
                        [0, 1, 2],
                        [3, 4, 5],
                        [6, 7, 8],
                        [0, 3, 6],
                        [1, 4, 7],
                        [2, 5, 8],
                        [0, 4, 8],
                        [2, 4, 6]
                    ];
        
                    foreach($lines as $line) {
                        $a = $line[0]; $b = $line[1]; $c = $line[2];
                        if ($partie->tableau[$a] && $partie->tableau[$a] === $partie->tableau[$b] && $partie->tableau[$a] === $partie->tableau[$c])
                            $partie->winner = $partie->tableau[$a];
                    }
                    /* Détection EGALITE*/
                    if(!in_array(null, $partie->tableau)){
                        $partie->winner = 'Egalité';
                    }
                }
                
                broadcast(new GameEvent($partie, $id_ami));
        
                return view('partie', [
                    "game" => $partie,
                    "id_join" => $id_ami,
                    "id" => $id
                ]);
        },

        morpion(){},

        changeBomb(type){
            if (type == 1)
                this.typeBomb = 1
            else if (type == 2)
                this.typeBomb = 2
            else
                this.typeBomb = 3
        },
        playSound(){
            var audio = new Audio(this.game.sound);
            audio.play();
        }
    }   
});

window.Pusher = require('pusher-js');
Pusher.logToConsole = true;

var pusher = new Pusher('4c1d236d405c41c95c80', {
    cluster: 'eu'
});

var channel = pusher.subscribe(`updateAmis.${home.id}`);
channel.bind('AmisEvent', function(data) {
    home.amis = (data.amis);
});

var channel2 = pusher.subscribe(`joinAmis.${home.id}`);
channel2.bind('JoinAmisEvent', function(data) {
    window.location.href = '/joinFriend?broadcast=false&id_join=' + data.id_ami;
});

/*var channel3 = pusher.subscribe(`game.${home.id}`);
channel3.bind('GameEvent', function(data) {
    partie.game = (data.partie);
    partie.playSound();
});*/

import Echo from 'laravel-echo'

console.log(partie.test);

window.echo = new Echo({
  broadcaster: 'pusher',
  key: '4c1d236d405c41c95c80',
  cluster: 'eu',
  encrypted: true,
  forceTLS: true,
  auth: {
    headers: {
        Authorization: 'Bearer ' + partie.test,
    }
  }
});

echo.private(`morpion.${id}`)
    .listenForWhisper('morpion', (e) => {
        partie.game = (data.partie);
        partie.playSound();
    });