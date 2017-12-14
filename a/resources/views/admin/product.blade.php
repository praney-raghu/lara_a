@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-xs-12 col-sm-12 col-md-12">
            <a class="btn btn-success" href="{{ url()->previous() }}" >Back</a>
        </div>
        <br>
        <br>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            @if(count($errors)>0)
            <br>
            <div class="alert alert-danger">
            <strong>Oops!!</strong>There were some problems with your input.<br><br>
            <ul>
            @foreach($errors->all() as $error)
            <li> {{ $error }} </li>
            @endforeach
            </ul>
            </div>
            @endif
            <div class="panel panel-success">
                <div class="panel-heading text-center"><h4>Add Product</h4></div>

                <div class="panel-body">
                 {!! Form::open(array('route'=>'store','files'=>true,'method'=>'POST')) !!}   
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>OEM Product Number</th>
                            <td>{!! Form::text('oem',null,['class'=>'form-control']) !!}</td>
                        </tr>
                        <tr>
                            <th>Brand</th>
                            <td>{!! Form::select('brand',[''=>'Select Brand']+$brands,null,['class'=>'form-control','id'=>'brand']) !!}</td>
                        </tr>
                        <tr>
                            <th>Product</th>
                            <td>{!! Form::text('product',null,['class'=>'form-control']) !!}</td>
                        </tr>
                        <tr>
                            <th>Vehicle</th>
                            <td><select name="vehicle" id="vehicle" class="form-control">
                            </select></td>
                        </tr>
                        <tr>
                            <th>Model</th>
                            <td>{!! Form::text('model',null,['class'=>'form-control']) !!}</td>
                        </tr>
                        <tr>
                            <th>Make</th>
                            <td>{!! Form::text('make',null,['class'=>'form-control']) !!}</td>
                        </tr>
                        <tr>
                            <th>Image</th>
                            <td>{!! Form::file('image_file',['class'=>'image']) !!}</td>
                        </tr>
                    </table>
                    <div align="center"><button type="submit" class="btn btn-success">Add Product</button></div>
                    {!! Form::close() !!}                     
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
    $('#brand').change(function(){
    var brand_id = $(this).val();
    if(brand_id){
        $.ajax({
           type:"GET",
           url:"{{url('/getVehicles')}}?brand_id="+brand_id,
           success:function(res){               
            if(res){
                $("#vehicle").empty();
                $("#vehicle").append('<option>Select Vehicle</option>');
                $.each(res,function(key,value){
                    $("#vehicle").append('<option value="'+key+'">'+value+'</option>');
                });
           
            }else{
               $("#vehicle").empty();
            }
           }
        });
    }      
   });
   });     
</script>
@endsection