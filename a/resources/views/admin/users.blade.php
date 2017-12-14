@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-xs-12 col-sm-12 col-md-12">
            <a class="btn btn-success" href="{{ route('home') }}" >Back</a>
        </div>
        <br>
        <br>
    <div class="row">        
        <div class="col-xs-12 col-sm-12 col-md-12">
             @if(count($errors)>0)
            <br>
            <div class="alert alert-danger">
            <ul>
            @foreach($errors->all() as $error)
            <li> {{ $error }} </li>
            @endforeach
            </ul>
            </div>
            @endif            
            <div class="panel panel-success">
                <div class="panel-heading text-center"><h4>Users</h4></div>
                <div class="panel-body">                       
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th></th>
                        </tr>
                        @foreach($users as $user)
                            <tr>
                                <td> {{ $user->id }} </td>
                                <td> {{ $user->name }} </td>
                                <td> {{ $user->email }} </td>
                                @if($user->status == 0)
                                <td>{!! Form::open(['method'=>'POST', 'route'=>['activate'],'style'=>'display:inline']) !!}                   
                                {!! Form::hidden('id', $user->id) !!}
                                {!! Form::submit('Activate', ['class' => 'btn btn-primary']) !!}
                                {!! Form::close() !!}
                                </td>
                                @else
                                <td>{!! Form::open(['method'=>'POST', 'route'=>['deactivate'],'style'=>'display:inline']) !!}                   
                                {!! Form::hidden('id', $user->id) !!}
                                {!! Form::submit('Deactivate', ['class' => 'btn btn-danger']) !!}
                                {!! Form::close() !!}
                                </td>
                                @endif
                            </tr>
                        @endforeach                        
                    </table>  
                </div>                
            </div>
        </div>
    </div>
    <div>
        {!! $users->links() !!}
    </div>
</div>

@endsection