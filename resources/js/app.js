/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('@fortawesome/fontawesome-free/js/all.js');

window.Vue = require('vue');
import Echo from 'laravel-echo'

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
        user,
        amis,
        subscribe: false,
        component_key: 1
    },

    methods:{
        toggleButtons() {
            this.subscribe = true;
            this.component_key += 1;
        }
    }
});

const partie = new Vue({
    el: '#partie',

    data: {
        id_ami,
        game: {},
        typeBomb: 1,
        component_key: 1,
        idSession,
        user,
        messages: [],
        message: '',
        caller: null,
        localUserMedia: null,
        hover: false, hover1: false, hover2: false,
        subscribe: false
    },   

    methods:{
        toggleButtons() {
            this.subscribe = true;
            this.component_key += 1;
        },

        GetRTCIceCandidate() {
            window.RTCIceCandidate = window.RTCIceCandidate || window.webkitRTCIceCandidate || window.mozRTCIceCandidate || window.msRTCIceCandidate;
            return window.RTCIceCandidate;
        },

        GetRTCPeerConnection() {
            window.RTCPeerConnection = window.RTCPeerConnection || window.webkitRTCPeerConnection || window.mozRTCPeerConnection || window.msRTCPeerConnection;
            return window.RTCPeerConnection;
        },

        GetRTCSessionDescription() {
            window.RTCSessionDescription = window.RTCSessionDescription || window.webkitRTCSessionDescription || window.mozRTCSessionDescription || window.msRTCSessionDescription;
            return window.RTCSessionDescription;
        },

        prepareCaller() {
            //Initializing a peer connection
            this.caller = new window.RTCPeerConnection();
            //Listen for ICE Candidates and send them to remote peers
            this.caller.onicecandidate = function(evt) {
              if (!evt.candidate) return;
              console.log("onicecandidate called");
              partie.onIceCandidate(partie.caller, evt);
            };
            //onaddstream handler to receive remote feed and show in remoteview video element
            this.caller.onaddstream = function(evt) {
              console.log("onaddstream called");
              /*if (window.URL) {
                document.getElementById("remoteview").src = window.URL.createObjectURL(
                  evt.stream
                );
              } else {*/
                document.getElementById("remoteview").srcObject = evt.stream;
              //}
            };
        },

        onIceCandidate(peer, evt) {
            if (evt.candidate) {
                echo.private(`client-candidate.${idSession}`)
                    .whisper('client-candidate', {
                        "candidate": evt.candidate,
                    });
            }
        },

        getCam() {
            //Get local audio/video feed and show it in selfview video element
            return navigator.mediaDevices.getUserMedia({
              video: true,
              audio: true
            });
        },

        callUser() {
            this.getCam()
              .then(stream => {
                /*if (window.URL) {
                  document.getElementById("selfview").src = window.URL.createObjectURL(
                    stream
                  );
                } else {*/
                  document.getElementById("selfview").srcObject = stream;
               // }
                partie.toggleEndCallButton();
                partie.caller.addStream(stream);
                partie.localUserMedia = stream;
                partie.caller.createOffer().then(function(desc) {
                  partie.caller.setLocalDescription(new RTCSessionDescription(desc));
                  echo.private(`client-sdp.${idSession}`)
                    .whisper('client-sdp', {
                        sdp: desc,
                        from: partie.user.id
                    });
                });
              })
              .catch(error => {
                console.log("an error occured", error);
              });
        },

        toggleEndCallButton() {
            if (document.getElementById("endCall").disabled = true) {
              document.getElementById("endCall").disabled = false;
            } else {
              document.getElementById("endCall").disabled = true;
            }
        },

        endCall() {
            this.caller.close();
            for (let track of this.localUserMedia.getTracks()) {
              track.stop();
            }
            this.prepareCaller();
            this.toggleEndCallButton();
        },

        endCurrentCall() {
            echo.private(`client-endcall.${idSession}`)
                .whisper('client-endcall', {});
            this.endCall();
        },

        sendMessage(message){
            this.messages.push(message);
            this.message = ''

            echo.private(`chat.${idSession}`)
                .whisper('chat', {messages: this.messages});
        },

        quit(){
            this.game.type_partie = null;
            this.game.sound = '';
            
            echo.private(`game.${idSession}`)
                .whisper('game', {game: this.game});
            this.component_key += 1
        },

        initMorpion(){
            this.game.tableau = [null, null, null, null, null, null, null, null, null];
            this.game.type_partie = 'morpion';
            this.game.tour = this.user.id;
            this.game.couleur = this.user.id;
            this.game.winner = null;
            this.game.sound = '';
            
            /* Broadcast */
            echo.private(`game.${idSession}`)
                .whisper('game', {game: this.game});
            this.component_key += 1
        },

        morpion(index){
            this.game.sound = "/sounds/CraieMorpion.mp3";
            this.game.tableau[index] = user.id;
            this.game.tour = id_ami;
        
            /* Détection EGALITE */
            if(this.game.tableau.includes(null) == false){
                this.game.winner = -1;
            }

            /* Détection WINNER */
            var lines = [[0, 1, 2], [3, 4, 5], [6, 7, 8], [0, 3, 6], [1, 4, 7], [2, 5, 8], [0, 4, 8], [2, 4, 6]];

            for(let line of lines) {
                let a = line[0]; let b = line[1]; let c = line[2];
                if (this.game.tableau[a] && this.game.tableau[a] === this.game.tableau[b] && this.game.tableau[a] === this.game.tableau[c])
                this.game.winner = this.game.tableau[a]
            }
        
            /* Broadcast */
            echo.private(`game.${idSession}`)
                .whisper('game', {game: this.game});
            this.component_key += 1
        },

        initPuissance4(){
            this.game.tableau = [
                [null, null, null, null, null, null],
                [null, null, null, null, null, null],
                [null, null, null, null, null, null],
                [null, null, null, null, null, null],
                [null, null, null, null, null, null],
                [null, null, null, null, null, null],
                [null, null, null, null, null, null]
            ];
            
            this.game.type_partie = 'puissance4';
            this.game.tour = this.user.id;
            this.game.couleur = this.user.id;
            this.game.winner = null;
            
            /* Broadcast */
            echo.private(`game.${idSession}`)
                .whisper('game', {game: this.game});
            this.component_key += 1
        },

        puissance4(index){
            this.game.tableau[index];
            for(let [key, elem] of this.game.tableau[index].reverse().entries()){
                if (!elem){
                    this.game.tableau[index].reverse()
                    this.game.tableau[index][5-key] = user.id;
                    var position = 5-key;
                    break;
                }
            }

            this.game.tour = id_ami;

            /* Détection EGALITE */
            let compteur = 0;
            for(let colonne of this.game.tableau){
                if(colonne.includes(null) == false){
                    compteur += 1;
                }
            }
            if (compteur == 7)
                this.game.winner = 'Egalité';

            /* Détection WINNER */
            let lines = [[1, 2, 3, 0, 0, 0],
                [-1, -2, -3, 0, 0, 0],
                [0, 0, 0, 1, 2, 3],
                [0, 0, 0, -1, -2, -3],
                [1, 2, 3, 1, 2, 3],
                [-1, -2, -3, -1, -2, -3],
                [-1, -2, -3, 1, 2, 3],
                [1, 2, 3, -1, -2, -3]];

            for(let line of lines) {
                let ai = line[0]; let bi = line[1]; let ci = line[2]; let ap = line[3]; let bp = line[4]; let cp = line[5];
                try {
                    if (this.game.tableau[index][position] == this.game.tableau[index + ai][position + ap] 
                    && this.game.tableau[index][position] == this.game.tableau[index + bi][position + bp] 
                    && this.game.tableau[index][position] == this.game.tableau[index + ci][position + cp]){
                        this.game.winner = this.game.tableau[index][position];
                    }
                } catch (error) {}
            }
        
            /* Broadcast */
            echo.private(`game.${idSession}`)
                .whisper('game', {game: this.game});
            this.component_key += 1
        },

        initBatailleNavale(){
            
            let colonnes = [
                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            ]

            let colonnes_2 = JSON.parse(JSON.stringify(colonnes));

            colonnes = this.genereBoat(colonnes);
            colonnes_2 = this.genereBoat(colonnes_2);
            
            colonnes.pop();
            colonnes.shift();

            colonnes_2.pop();
            colonnes_2.shift();

            for(let col of colonnes_2){
                col.pop();
                col.shift();
            }
            for(let col of colonnes){
                col.pop();
                col.shift();
            }

            console.log(colonnes)

            this.game.tableau = colonnes;
            this.game.tableau_2 = colonnes_2;
            this.game.type_partie = 'batailleNavale';
            this.game.tour = this.user.id;
            this.game.couleur = this.user.id;
            this.game.winner = null;
            this.game.sound = '';
            this.game.bateaux = [1,2,3,4,5];
            this.game.bateaux_2 = [1,2,3,4,5];
            this.game.bombs = [999,3,1];
            this.game.bombs_2 = [999,3,1];

            echo.private(`game.${idSession}`)
                .whisper('game', {game: this.game});
            this.component_key += 1
        },

        batailleNavale(indexColonne, indexLigne, typeBomb){
            this.game.sound = '/sounds/plouf.mp3';

            //Différentes bombes
            let bombs
            if(this.game.couleur == this.user.id){
                if (typeBomb == 2 && this.game.bombs_2[1] > 0){
                    bombs = [[0, 0], [1, 0], [0, 1], [-1, 0], [0, -1]];
                    this.game.bombs_2[1] -= 1;
                }
                else if (typeBomb == 3 && this.game.bombs_2[2] > 0){
                    bombs = [[0, 0], [1, 1], [1, -1], [-1, 1], [-1, -1], [0, 2], [2, 0], [-2, 0], [0, -2]];
                    this.game.bombs_2[2] -= 1;
                }
                else
                    bombs = [[0, 0]];
            }
            
            else {
                if (typeBomb == 2 && this.game.bombs[1] > 0){
                    bombs = [[0, 0], [1, 0], [0, 1], [-1, 0], [0, -1]];
                    this.game.bombs[1] -= 1;
                }
                else if (typeBomb == 3 && this.game.bombs[2] > 0){
                    bombs = [[0, 0], [1, 1], [1, -1], [-1, 1], [-1, -1], [0, 2], [2, 0], [-2, 0], [0, -2]];
                    this.game.bombs[2] -= 1;
                }
                else
                    bombs = [[0, 0]];
            }

            this.game.tour = this.id_ami;

            for(let bomb of bombs){
                try{
                    if(this.game.couleur == this.user.id){
                        if(this.game.tableau_2[indexColonne + bomb[0]][indexLigne + bomb[1]] < 0 && this.game.tableau_2[indexColonne + bomb[0]][indexLigne + bomb[1]] > -10){
                            this.game.tableau_2[indexColonne + bomb[0]][indexLigne + bomb[1]] -= 10;
                            this.game.tour = this.user.id;
                            this.game.sound = "/sounds/boom.mp3";
                        }
                        else if(this.game.tableau_2[indexColonne + bomb[0]][indexLigne + bomb[1]] == 0)
                            this.game.tableau_2[indexColonne + bomb[0]][indexLigne + bomb[1]] = this.user.id;
                    }

                    else{
                        if(this.game.tableau[indexColonne + bomb[0]][indexLigne + bomb[1]] < 0 && this.game.tableau[indexColonne + bomb[0]][indexLigne + bomb[1]] > -10){
                            this.game.tableau[indexColonne + bomb[0]][indexLigne + bomb[1]] -= 10;
                            this.game.tour = this.user.id;
                            this.game.sound = "/sounds/boom.mp3";
                        }
                        else if(this.game.tableau[indexColonne + bomb[0]][indexLigne + bomb[1]] == 0)
                            this.game.tableau[indexColonne + bomb[0]][indexLigne + bomb[1]] = this.user.id;
                    }
                } catch(error){}
            }
            
            if(this.game.couleur == this.user.id){
                for(let [key, bat] of this.game.bateaux_2.entries()){
                    let compteur = 0;
                    if (bat != null){
                        for(let colonne of this.game.tableau_2){
                            if(colonne.includes(-bat) == false){
                                compteur += 1;
                            }
                        }
                        if (compteur == 10){
                            this.game.tour = this.id_ami;
                            for(let [indexCol, colonne] of this.game.tableau_2.entries())
                                for(let [indexLigne, elm] of colonne.entries()){
                                    let test = -bat-10
                                    if (elm == test)
                                        this.game.tableau_2[indexCol][indexLigne] = 'coulé';
                                }
                            this.game.bateaux_2[key] = null;
                        }
                    }
                }
            }
            else {
                for(let [key, bat] of this.game.bateaux.entries()){
                    let compteur = 0;
                    if (bat != null){
                        for(let colonne of this.game.tableau){
                            if(colonne.includes(-bat) == false){
                                compteur += 1;
                            }
                        }
                        if (compteur == 10){
                            this.game.tour = this.id_ami;
                            for(let [indexCol, colonne] of this.game.tableau.entries())
                                for(let [indexLigne, elm] of colonne.entries()){
                                    let test = -bat-10
                                    if (elm == test)
                                        this.game.tableau[indexCol][indexLigne] = 'coulé';
                                }
                            this.game.bateaux[key] = null;
                        }
                    }
                }
            }

            /* Détection WINNER */
            let compteur = 0;
            for(let bat of this.game.bateaux_2){
                if(!bat)
                    compteur += 1;
            }
            if (compteur == 5)
                this.game.winner = this.game.couleur;
            
            compteur = 0;
            for(let bat of this.game.bateaux){
                if(!bat)
                    compteur += 1;
            } 
            if (compteur == 5){
                if(this.game.couleur == this.user.id)
                    this.game.winner = this.id_ami;
                else
                    this.game.winner = this.user.id;
            }
              
            echo.private(`game.${idSession}`)
                .whisper('game', {game: this.game});
            this.component_key += 1
        },

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
        },

        genereBoat(colonnes){
            let bateaux = [5,4,3,3,2];
            let tempCol = 0;
            let gen = 0;
            for(let [index, longueur] of bateaux.entries()){
                index += 1;
                gen = false;
                while(gen == false){
                    gen = true;
                    tempCol = JSON.parse(JSON.stringify(colonnes));
                    let dir = Math.floor(Math.random() * 2);  
                    let vertical = Math.floor(Math.random() * 2); 

                    if(vertical && dir){
                        let colonne = Math.floor(Math.random() * 10) + 1; 
                        let ligne = Math.floor(Math.random() * 10-longueur-1) + 1; 
                        try {
                            if (tempCol[colonne][ligne - 1] == 0){
                                for(let i=0; i<longueur; i++){
                                    if(tempCol[colonne][ligne + i] == 0 && tempCol[colonne + 1][ligne + i] == 0 && tempCol[colonne - 1][ligne + i] == 0 && tempCol[colonne][ligne + i + 1] == 0)
                                        tempCol[colonne][ligne + i] = -index;
                                    else gen = false;
                                }
                            }
                            else gen = false;
                        } catch (error){gen = false;}
                    }
            
                    else if(vertical && !dir){
                        let colonne = Math.floor(Math.random() * 10) + 1; 
                        let ligne = Math.floor(Math.random() * (10 - 1+longueur-1 + 1) ) + 1+longueur-1;
                        try {
                            if (tempCol[colonne][ligne + 1] == 0){
                                for(let i=0; i<longueur; i++){
                                    if(tempCol[colonne][ligne - i] == 0 && tempCol[colonne + 1][ligne - i] == 0 && tempCol[colonne - 1][ligne - i] == 0 && tempCol[colonne][ligne - i - 1] == 0)
                                        tempCol[colonne][ligne - i] = -index;
                                    else gen = false;
                                }
                            }
                            else gen = false;
                        } catch (error){gen = false}
                    }
            
                    else if(!vertical && dir){
                        let ligne = Math.floor(Math.random() * 10) + 1;
                        let colonne = Math.floor(Math.random() * 10-longueur-1) + 1;
                        try {
                            if (tempCol[colonne - 1][ligne] == 0){
                                for(let i=0; i<longueur; i++){
                                    if(tempCol[colonne + i][ligne] == 0 && tempCol[colonne + i + 1][ligne] == 0 && tempCol[colonne + i][ligne + 1] == 0 && tempCol[colonne + i][ligne - 1] == 0)
                                        tempCol[colonne + i][ligne] = -index;
                                    else gen = false;
                                }
                            }
                            else gen = false;
                        } catch (error){gen = false;}
                    }
            
                    else {
                        let ligne = Math.floor(Math.random() * 10) + 1;
                        let colonne = Math.floor(Math.random() * (10 - 1+longueur-1 + 1)) + 1+longueur-1;
                        try {
                            if (tempCol[colonne + 1][ligne] == 0){
                                for(let i=0; i<longueur; i++){
                                    if(tempCol[colonne - i][ligne] == 0 && tempCol[colonne - i - 1][ligne] == 0 && tempCol[colonne - i][ligne + 1] == 0 && tempCol[colonne - i][ligne - 1] == 0)
                                        tempCol[colonne - i][ligne] = -index;
                                    else gen = false;
                                }
                            }
                            else gen = false;
                        } catch (error){gen = false;}
                    }
                }
                colonnes = JSON.parse(JSON.stringify(tempCol));
            }
            return JSON.parse(JSON.stringify(colonnes));     
        }
    }   
});


if (home.amis != null){
    window.Pusher = require('pusher-js');
    //Pusher.logToConsole = true;

    var pusher = new Pusher('4c1d236d405c41c95c80', {
        cluster: 'eu'
    });

    var channel = pusher.subscribe(`updateAmis.${home.user.id}`);
    channel.bind('AmisEvent', function(data) {
        home.amis = (data.amis);
    });

    var channel2 = pusher.subscribe(`joinAmis.${home.user.id}`);
    channel2.bind('JoinAmisEvent', function(data) {
        window.location.href = '/joinFriend?broadcast=false&id_join=' + data.ami.id + '&ami_name=' + data.ami.name + '&idSession=' + data.idSession;
    });

    channel2.bind('pusher:subscription_succeeded', function() {
        home.toggleButtons()
    });
}

else {
    window.Pusher = require('pusher-js');
    //Pusher.logToConsole = true;

    window.echo = new Echo({
        broadcaster: 'pusher',
        key: '4c1d236d405c41c95c80',
        cluster: 'eu',
        forceTLS: true,
        auth: {
            headers: {
                Authorization: 'Bearer ' + partie.user.api_token,
            }
        }
    });
    echo.private(`game.${idSession}`)
        .listenForWhisper('game', (data) => {
            partie.game = (data.game);
            partie.playSound();
        });

    echo.private(`chat.${idSession}`)
        .listenForWhisper('chat', (data) => {
            partie.messages = (data.messages);
        });

    //To iron over browser implementation anomalies like prefixes
    partie.GetRTCPeerConnection();
    partie.GetRTCSessionDescription();
    partie.GetRTCIceCandidate();
    //prepare the caller to use peerconnection
    partie.prepareCaller();

    echo.private(`client-candidate.${idSession}`)
        .listenForWhisper('client-candidate', (msg) => {
            partie.caller.addIceCandidate(new RTCIceCandidate(msg.candidate));
        });

    echo.private(`client-sdp.${idSession}`)
        .listenForWhisper('client-sdp', (msg) => {
            partie.getCam()
            .then(stream => {
                partie.localUserMedia = stream;
                partie.toggleEndCallButton();
                /*if (window.URL) {
                    document.getElementById("selfview").src = window.URL.createObjectURL(stream);
                } else {*/
                    document.getElementById("selfview").srcObject = stream;
                //}
                partie.caller.addStream(stream);
                var sessionDesc = new RTCSessionDescription(msg.sdp);
                partie.caller.setRemoteDescription(sessionDesc);
                partie.caller.createAnswer().then(function(sdp) {
                    partie.caller.setLocalDescription(new RTCSessionDescription(sdp));
                    echo.private(`client-answer.${idSession}`)
                        .whisper('client-answer', {"sdp": sdp});
                });

            })
            .catch(error => {
                console.log('an error occured', error);
            })
        });
    echo.private(`client-answer.${idSession}`)
        .listenForWhisper('client-answer', (answer) => {
            console.log("answer received");
            partie.caller.setRemoteDescription(new RTCSessionDescription(answer.sdp));
        });
    echo.private(`client-endcall.${idSession}`)
        .listenForWhisper('client-endcall', (nothing) => {
            console.log("endCall");
            partie.endCall();
        });

    setTimeout(function(){ partie.toggleButtons() }, 2000);
}