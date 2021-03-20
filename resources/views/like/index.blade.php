@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1>Mis imagenes favoritas</h1>
            <hr>
            @include('includes.message')
            @foreach($likes as $like)
                @include('includes.image', ['image'=>$like->image])
            @endforeach
            <!-- Enlaces de paginación -->
            <div class="clearfix"></div>
            {{$likes->links()}}
        </div>
    </div>
</div>
@endsection