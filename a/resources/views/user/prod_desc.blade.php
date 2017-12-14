@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-xs-12 col-sm-12 col-md-12">
            <a class="btn btn-success" href="{{ url()->previous() }}" >Back</a>
        </div>
        <br>
        <br>
    <div class="row">
        <div class="col-md-6">
            <div id="imgShow" class="well" style="width: 460px;height: 400px">
                <img src=" http://127.0.0.1:8000{{$img[0]}} " width="420" height="360">
            </div>
        </div>
        <div class="col-md-6">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" >
                    <tr><td class="txtbold">Product Code</td><td> {{ $item->product_code }} </td></tr>
                    <tr><td class="txtbold">Product</td><td>{{ $item->item }}</td></tr>
                    <tr><td class="txtbold">OEM Part Number</td><td>{{ $item->oem_part_no }}</td></tr>
                    <tr><td class="txtbold">Year</td><td>{{ $item->model }}</td></tr>
                    <tr><td class="txtbold">Make</td><td>{{ $item->make }}</td></tr>
                </table>
            </div>
        </div>                  
    </div>
    <div class="col-md-12">
        <ul style="display:flex;list-style:none;">
            @foreach($img as $image)
                <li><div id="imgClick" class="img-wrap"><img id="pointer" src=" http://127.0.0.1:8000{{$image}} " width="100" height="100"></div></li>
            @endforeach
        </ul>
    </div>
</div>
<br><br>
<footer class="container-fluid text-center">
    <p class="footer">The vehicle brand, that are mentioned in this catalogue, are of manufacturers ownership of the cars to which they make reference. Their use in this catalogue, as well as the genuine codes included, were used with the only purpose of helping to identify all the vehicles models in which the spare part must be set. The items, though they are not genuine, are perfectly adaptable to the cars to which they make reference.</p>
        <p class="footer">&copy 2017 Auto Villa All Rights Reserved.</p>
</footer>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
    $('#imgClick img').click(function(){    

    var url = $(this).attr('src');
    if(url){
        $('#imgShow img').attr('src',url);
    }      
   });
   });     
</script>
@endsection
