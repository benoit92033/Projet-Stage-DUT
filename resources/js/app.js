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
        game
    },   

    methods:{
        play(id_join, type_jeu){
            if (type_jeu == 'batailleNavale')
                window.location.href = "/batailleNavale?id_ami=" + id_join;
            if (type_jeu == 'morpion')
                window.location.href = "/morpion?id_ami=" + id_join;
            if (type_jeu == 'puissance4')
                window.location.href = "/puissance4?id_ami=" + id_join;
        }
    }   
});

window.Pusher = require('pusher-js');
//Pusher.logToConsole = true;

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

var channel3 = pusher.subscribe(`game.${home.id}`);
channel3.bind('GameEvent', function(data) {
    partie.game = (data.partie);
});