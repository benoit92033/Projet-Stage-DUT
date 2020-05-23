<template>
    <div class="container">
        <div class="row">
            <div class="col-8">
                <div>
                    <p>C'est censé s'afficher là !</p>
                    <ul>
                        <li v-for="message in messages" :key="message">
                            {{message}}
                        </li>
                    </ul>
                </div>
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
                        <p>Mon code ami : {{ friend_code }} </p>
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
                            <li v-for="ami in amis" :key="ami">
                                {{ami.name}}
                                <form action="/play">
                                    <p>
                                        <input type="hidden" name="id_ami" :value="ami.id" />
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
</template>

<script>
    window.Pusher = require('pusher-js');
    Pusher.logToConsole = true;

    export default {
        props : ['friend_code', 'amis', 'messageNON'],

        data() {
            return {
                idFriend: '',
                messages: [
                    {message: 'Test'},
                    {message: 'Test2'}
                ],
            }
        },

        created(){
            this.subscribe()
        },    

        methods:{
            log(message){
                console.log(message);
            },
            subscribe(){           
                var pusher = new Pusher('4c1d236d405c41c95c80', {
                    cluster: 'eu'
                });
                var channel = pusher.subscribe('my-channel');

                channel.bind('AmisEvent', function(data) {
                    //this.messages.push(JSON.stringify(data));
                    this.messages.push({message:'test3'});
                });
            }
        }   
    }
</script>
