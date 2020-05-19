@extends('layouts.app')

@section('content')
    <example-component :friend_code = "'{{$friend_code}}'" ></example-component>
@endsection