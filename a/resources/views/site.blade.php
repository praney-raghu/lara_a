@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <br>
        <img src="http://127.0.0.1:8000/uploads/autovilla.png" class="img-responsive" style="margin: 0 auto;" width="150" height="150">
        <br>
        <br>
        {!! Form::open(array('route'=>'user_search','method'=>'GET','class'=>'navbar-form','role'=>'search')) !!}
        <div class="input-group col-md-10 col-md-offset-2" >
        <input type="text" class="form-control" name="query" placeholder="Search here....."><span class="input-group-btn"><button type="submit" class="btn btn-primary">Search</button></span> 
        </div>
        {!! Form::close() !!}              
    </div>
</div>

@endsection
