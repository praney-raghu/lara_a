@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <br>        
        {!! Form::open(array('route'=>'user_search','method'=>'GET','class'=>'navbar-form','role'=>'search')) !!}
        <div class="input-group col-md-10 col-md-offset-2" >
        <input type="text" class="form-control" name="query" placeholder="Search here....."><span class="input-group-btn"><button type="submit" class="btn btn-primary">Search</button></span> 
        </div>
        {!! Form::close() !!}
        <br>
        @if(!$result->isEmpty())
        <p>Showing results for : <b>{{$q}}</b></p>
            <br>
        @foreach($result as $item)
        <div class="col-md-3 col-lg-3 col-sm-3 col-xs " >
            <div class="thumbnail" >
                <a href=" {{ route('pro_show',['id' => $item->item_id]) }} "><img class="se-img" src=" http://127.0.0.1:8000{{ $img[$i++] }} " ></a>
                <div class="caption">
                    <p class="text-capitalize">{{ $item->product_code }}</p>
                    <p class="text-capitalize"><a href="{{ route('pro_show', $item->item_id) }}">{{ $item->item }}</a></p>
                    <p class="text-capitalize">{{ $item->oem_part_no }}</p>
                    <p class="text-capitalize">{{ $item->model }}</p>
                    <p class="text-capitalize">{{ $item->make }}</p>
                </div>
            </div>            
        </div>
        @endforeach
        @else
                <p>Your search - <b>{{$q}}</b> - did not match any documents.</p>
                <br>
                <p>Suggestions:</p>
                <ul>
                    <li>Make sure that all words are spelled correctly.</li>
                    <li>Try different keywords.</li>                
                </ul>
            @endif                      
    </div>
    <div>
        {!! $result->appends(['query'=>$q])->links() !!}
    </div>
</div>

@endsection
