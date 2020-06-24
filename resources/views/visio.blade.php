@extends('layouts.app')

@section('content')
    <div id="visio">
        <video id="selfview" autoplay playsinline style="height: 100px; width: 200px;"></video>
        <video id="remoteview" autoplay playsinline style="height: 200px; width: 400px;"></video>
        <button id="endCall" style="display: none;" v-on:click="endCurrentCall()">End Call </button>
        <input type="button" style="float:right;"  value="Call" v-on:click="callUser()" id="makeCall" /></li>
    </div>
    <script>
        window.user = @json($user);
        window.amis = null
        window.game = null;
        window.typeBomb = null;
        window.id_ami = null;
        window.component_key = null;
        window.idSession = null;
        window.messages = null;

        window.caller = null;
        window.localUserMedia = null;
    </script>
@endsection