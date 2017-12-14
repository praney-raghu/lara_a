@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <a class="btn btn-success" href=" {{ route('product') }} " >Add Product</a>
        </div>
        <br>
        <br>
        {!! Form::open(array('route'=>'search','method'=>'GET','class'=>'navbar-form','role'=>'search')) !!}
        <div class="input-group col-md-8 col-md-offset-2" >
        <input type="text" class="form-control" name="query" placeholder="Search here....."><span class="input-group-btn"><button type="submit" class="btn btn-primary">Search</button></span>    
        </div>
        {!! Form::close() !!}
        <br>
        <div class="col-xs-12 col-sm-12 col-md-12">
            @if(!$result->isEmpty())
            @if($q!=null)
            <p>Showing results for : <b>{{$q}}</b></p>
            <br>
            @endif
            <table id="users-table" class="table table-bordered table-striped">                
                <thead>
                <tr>
                    <th>OEM PART NO.</th>
                    <th>BRAND</th>
                    <th>ITEM</th>
                    <th>VEHICLE</th>
                    <th>MAKE</th>
                    <th>EDIT</th>
                    <th>DELETE</th>
                </tr>
                </thead>
                @foreach($result as $item)
                <tr>
                    <td>{{ $item->oem_part_no }}</td>
                    <td>{{ $item->brand_name }}</td>
                    <td>{{ $item->item }}</td>
                    <td>{{ $item->vehicle_name }}</td>
                    <td>{{ $item->make }}</td>                    
                    <td>{!! Form::open(['method'=>'GET','route'=>['edit']]) !!}
                    {!! Form::hidden('item_id',$item->item_id) !!}
                    {!! Form::submit('Edit', ['class' => 'btn btn-info']) !!}{!! Form::close() !!}</td>
                    <td>{!! Form::open(['method'=>'DELETE', 'route'=>['destroy'],'style'=>'display:inline']) !!}                   
                    {!! Form::hidden('id', $item->item_id) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                    {!! Form::close() !!}</td>
                </tr>
                @endforeach
            </table>
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
            @if($q==NULL)
            {!! $result->links() !!}
            @else
            {!! $result->appends(['query'=>$q])->links() !!}
            @endif
        </div>
    </div>
</div>

@endsection
