@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Gente</h1>

    <form method="GET" action="{{route('user.index')}}" id="buscador">
        <div class="row">
            <div class="form-group col">
                <input type="text" id="search" class="form-control">
            </div>
            <div class="form-group col">
                <input type="submit" value="Buscar" class="btn btn-success btn-search">
            </div>
        </div>
        
    </form>
    <hr>

    <div class="row justify-content-center">
        <div class="col-md-8">
            @foreach($users as $user)
                <div class="profile-user">
                    @if($user->image)
                        <div class="container-avatar">
                            <img src="{{route('user.avatar', ['filename'=>$user->image])}}" alt="" class="avatar">
                        </div>
                    @endif
                
                    <div class="user-info">
                        <h2>{{'@'.$user->nick}}</h2>
                        <h3>{{$user->name.' '.$user->surname}}</h3>
                        <p> {{' Se unió hace:  '.\FormatTime::LongTimeFilter($user->created_at)}} <p>
                        <a href="{{route('profile', ['id' => $user->id])}}" class="btn btn-success">Ver perfil</a>
                    </div>
                </div>
            @endforeach
            <!-- Enlaces de paginación -->
            <div class="clearfix"></div>
            {{$users->links()}}
        </div>
    </div>
</div>
@endsection