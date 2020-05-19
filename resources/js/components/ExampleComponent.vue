<template>
    <div class="container">
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-header">Puissance 4</div>

                    <div class="card-body">
                        <button>Jouer</button>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-header">Ajouter un ami</div>

                    <div class="card-body">
                        <p>Mon code ami : {{ friend_code }} </p>
                        <form id="app" @submit="onSubmit" action="/test">
                            <p>
                                <label for="idFriend">Ajouter ami : </label>
                                <input id="idFriend" v-model="idFriend" type="text" name="idFriend" placeholder="Code ami">
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
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props : ['friend_code'],

        data() {
            return {
                idFriend: ''
            }
        },
        methods:{
            onSubmit() {
                console.log(idFriend)
            },
        }   
    }
</script>
