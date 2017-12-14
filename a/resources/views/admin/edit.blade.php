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
                <div class="panel-heading text-center"><h4>Edit Product Details</h4></div>

                <div class="panel-body">
                    @foreach($item as $i)
                 {!! Form::model($item,array('route'=> array('update'),'files'=>true,'method'=>'PUT')) !!}
                 {!! Form::hidden('item_id',$i->item_id) !!}   
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>Product Code</th>
                            <td>{!! Form::text('code',$i->product_code,['class'=>'form-control']) !!}</td>
                        </tr>
                        <tr>
                            <th>OEM Product Number</th>
                            <td>{!! Form::text('oem',$i->oem_part_no,['class'=>'form-control']) !!}</td>
                        </tr>
                        <tr>
                            <th>Brand</th>
                            <td>{!! Form::select('brand',$brands,$i->brand,['class'=>'form-control','id'=>'brand']) !!}</td>
                        </tr>
                        <tr>
                            <th>Product</th>
                            <td>{!! Form::text('product',$i->item,['class'=>'form-control']) !!}</td>
                        </tr>
                        <tr>
                            <th>Vehicle</th>
                            <td><select name="vehicle" id="vehicle" class="form-control"><option value="{{ $i->vehicle }}">{{ $i->vehicle_name }}</option>
                            </select></td>
                        </tr>
                        <tr>
                            <th>Model</th>
                            <td>{!! Form::text('model',$i->model,['class'=>'form-control']) !!}</td>
                        </tr>
                        <tr>
                            <th>Make</th>
                            <td>{!! Form::text('make',$i->make,['class'=>'form-control']) !!}</td>
                        </tr>
                        <tr>
                            <th>Image</th>
                            <td>{!! Form::file('image_file',['class'=>'image']) !!}</td>
                        </tr>
                    </table>
                    <div align="center"><button type="submit" class="btn btn-success">Update</button></div>
                    {!! Form::close() !!}                    
                    <div class="col-md-12"> 
                    <ul style="display:flex;list-style:none;">                       
                        @foreach($img as $image)
                            @if($image!=NULL)
                            
                            <li>{!! Form::open(['method'=>'DELETE', 'route'=>['del_img'],'onclick'=>'submit()']) !!}                   
                            {!! Form::hidden('img_url', $image) !!}
                            <div class="img-wrap"><span class="close">&times;</span><img src=" http://127.0.0.1:8000{{$image}} " width="100" height="100"></div>
                            {!! Form::close() !!}</li>                        
                            
                         @endif
                         @endforeach
                         </ul>
                    </div>                                         
                </div>
                @endforeach
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