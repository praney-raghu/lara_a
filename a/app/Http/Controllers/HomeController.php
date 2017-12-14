<?php

namespace Autovilla\Http\Controllers;

use Illuminate\Http\Request;
use Autovilla\Brand;
use Illuminate\Support\Facades\DB;
use Autovilla\Item;
use Autovilla\User;
use Auth;
use Datatables;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Input;
use Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth',['except' => ['getData']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Returns the homepage for Admin
        $result = DB::table('items')->orderBy('item_id')
                    ->join('brands','items.brand','=','brand_id')
                    ->join('vehicles','items.vehicle','=','vehicle_id')
                    ->paginate(20);
        $q = NULL;
        return view('home', compact('result','q'));
    }

    public function user()
    {
        $users = DB::table('users')
                    ->join('user_has_role','users.id','=','user_has_role.user_id')
                    ->where('role_id','2')
                    ->paginate(20);
        //dd($users);
        return view('admin.users', compact('users'));
    }

    public function activate(Request $request)
    {
        //dd($request->id);
        $id = $request->id;
        DB::table('users')->where('id',$id)->update([
                'status' => 1 ]);
        if($this->send_mail($id))
            return redirect()->route('users');        
        else
           return redirect()->route('users')->withErrors('Mail could not be sent.');
    }

    public function deactivate(Request $request)
    {
       $id = $request->id;
        DB::table('users')->where('id',$id)->update([
                'status' => 0 ]);
        return redirect()->route('users'); 
    }

    public function send_mail($id)
    {
        $usr = User::where('id',$id)->first();
        //dd($usr->email);
        $data = array('name' => $usr->name);
        Mail::send('mail',$data,function($message) use($usr) {
            $message->to($usr->email ,$usr->name)->subject('Account Activated');
            $message->from('admin@autovilla.in','Admin');
        });
        if (Mail::failures()) {
            return false;
        }
        return true;
    }

    public function search(Request $request)
    {
        $q = $request['query'];
        if($q == NULL) {
            return redirect()->route('home');
        }
        $keywords = preg_split('/[\s,\+]+/', $q);
        $big_array = preg_split('/[\s,\+]+/', $q); 
        $k = sizeof($keywords);
        //dd($keywords,$k);
        $items = NULL;
        
        foreach ($keywords as $i => $keyword) {            
            // escapes and quotes those keywords to prevent against injection attack
            $keywords[$i] = "'" .$keyword. "'";            
        }
        //dd($i);
        //Checking for every keyword in database.
        $like_search = " + ";
        for($l=0;$l<$k;$l++){
            $like_search .= " CASE WHEN items.oem_part_no LIKE '%".$big_array[$l]."%' THEN 1 ELSE 0 END + CASE WHEN brands.brand_name LIKE '%".$big_array[$l]."%' THEN 1 ELSE 0 END + CASE WHEN items.product_code LIKE '%".$big_array[$l]."%' THEN 1 ELSE 0 END + CASE WHEN items.model LIKE '%".$big_array[$l]."%' THEN 1 ELSE 0 END + CASE WHEN items.item LIKE '%".$big_array[$l]."%' THEN 1 ELSE 0 END + CASE WHEN vehicles.vehicle_name LIKE '%".$big_array[$l]."%' THEN 1 ELSE 0 END + CASE WHEN items.make LIKE '%".$big_array[$l]."%' THEN 1 ELSE 0 END + ";
        }
        //dd($like_search);
        for($j=0;$j<$k-2;$j++){
            
            $next = "'".$big_array[$j]." ".$big_array[$j+1]."'";
            $nexts = "'".$big_array[$j+1]." ".$big_array[$j]."'";
            if($k>=3){
                $next3 = "'".$big_array[$j]." ".$big_array[$j+1]." ".$big_array[$j+2]."'";
                if (!in_array($next3, $keywords))
                    array_push($keywords,$next3);
            }
            array_push($keywords,$next,$nexts);
        }

        $keywords = implode(',', $keywords);
        
        $cases = " ( CASE WHEN items.oem_part_no IN ($keywords) THEN 2 ELSE 0 END $like_search CASE WHEN brands.brand_name IN ($keywords) THEN 2 ELSE 0 END + CASE WHEN items.product_code IN ($keywords) THEN 2 ELSE 0 END + CASE WHEN items.model IN ($keywords) THEN 2 ELSE 0 END + CASE WHEN items.item IN ($keywords) THEN 2 ELSE 0 END + CASE WHEN vehicles.vehicle_name IN ($keywords) THEN 2 ELSE 0 END + CASE WHEN items.make IN ($keywords) THEN 2 ELSE 0 END ) ";
        
        
        $sql_query = "select items.item_id,items.product_code,items.model,items.oem_part_no,brands.brand_name,items.item,vehicles.vehicle_name, items.make, ".$cases." AS rank  FROM items left join brands on items.brand = brands.brand_id left join vehicles on items.vehicle = vehicles.vehicle_id having rank >0 ORDER BY rank DESC";// LIMIT ".$start_value.",20 ";
        $items = DB::select(DB::raw($sql_query));
        //dd($items);
        //  $num_per_page = 20;
        //  $page = 1;
        // if (!$page) {
        //     $page = 1;
        // }

        // $offset = ( $page - 1) * $num_per_page;
        // $items = array_slice($items,$offset, $num_per_page);
        // $totalCount = count($items);

        // $paginator = new Paginator($items, $totalCount, Input::get('limit') ?: '10');
        $paginate = 20;
        $page = Input::get('page', 1);
    
        $offSet = ($page * $paginate) - $paginate;  
        $itemsForCurrentPage = array_slice($items, $offSet, $paginate, true);  
        $result = new \Illuminate\Pagination\LengthAwarePaginator($itemsForCurrentPage, count($items), $paginate, LengthAwarePaginator::resolveCurrentPage(),array('path' =>  LengthAwarePaginator::resolveCurrentPath()));
        // $result = $result->toArray();

        //return $paginator;

        //dd($result->isEmpty());
        return view('home', compact('result','q'));   
       
    }    

    public function show()
    {
        //Accessing brands from database for use in 'Add product' view.
        $brands = Brand::pluck('brand_name','brand_id')->toArray();
        return view('admin.product', compact('brands'));
    }

    public function store(Request $request)
    {
        //Validating user input.
        $this->validate($request, ['oem'=>'max:50','brand'=>'required','product'=>'required|max:30','vehicle'=>'required','model'=>'max:10','make'=>'required|max:30','image_file'=>'image|mimes:jpeg,png,jpg,gif,svg|max:1024']);

        //Adding new Item to database.
        $item = new Item;
        $item->oem_part_no = $request['oem'];
        $item->product_code = 1;
        $item->brand = $request['brand'];
        $item->item = $request['product'];
        $item->vehicle = $request['vehicle'];
        $item->model = $request['model'];
        $item->make = $request['make'];
        $item->save();
        
        $item_id = $item->item_id;
        
        //Getting 'Brand code' & 'Vehicle code' for making 'product code'.
        $brand_code = DB::table("brands")
                    ->where("brand_id",$request['brand'])
                    ->pluck("brand_code")->first();
        $vehicle_code = DB::table("vehicles")
                    ->where("vehicle_id",$request['vehicle'])
                    ->pluck("vehicle_code")->first();

        if ($item_id<10)
            $product_code = $brand_code.$vehicle_code.'0000'.$item_id;
        else if ($item_id<100 && $item_id>9)
            $product_code = $brand_code.$vehicle_code.'000'.$item_id;
        else if ($item_id<1000 && $item_id>99)
            $product_code = $brand_code.$vehicle_code.'00'.$item_id;
        else if ($item_id<10000 && $item_id>999)
            $product_code = $brand_code.$vehicle_code.'0'.$item_id;
        
        DB::table('items')->where('item_id',$item_id)->update([
                'product_code'=>$product_code]);
        
        //Uploading image to folder & storing its URL in database.
        $image_file = $request['image_file'];
        if($image_file!=NULL) {
            $image_name = time().'.'.$image_file->getClientOriginalName();
            $destination_path = '../public/uploads'; 
            $image_file->move($destination_path,$image_name);

            DB::table('images')->insert([
                'image_url'=>'/uploads'.'/'.$image_name]);

            $img_id = DB::table("images")
                    ->where("image_url",'/uploads'.'/'.$image_name)
                    ->pluck("image_id")->first();

            DB::table('item_images')->insert([
                'item_id'=>$item_id,'image_id'=>$img_id
            ]);            
        }

        return redirect()->route('home');
    }

    public function edit(Request $request)
    {
        //Editing product details
        $id = $request->item_id;
        
        $img_ids = DB::table('item_images')->where('item_id',$id)->pluck('image_id')->toArray();
        if($img_ids!=NULL) {
            foreach ($img_ids as $key  ) {
                 $img[] = DB::table('images')->where('image_id',$key)->pluck('image_url')->first();
             }            
            }
            else
            {
                $img[] = NULL;
            }
        $item = DB::table('items')->where('item_id',$id)
        ->join('vehicles','items.vehicle','=','vehicle_id')->get();
        $brands = Brand::pluck('brand_name','brand_id')->toArray();
        
        return view('admin.edit',compact('item','brands','img'));
    }

    public function update(Request $request)
    {
        $this->validate($request, ['code'=>'max:30','oem'=>'max:50','brand'=>'required','product'=>'required|max:30','vehicle'=>'required','model'=>'max:10','make'=>'required|max:30','image_file'=>'image|mimes:jpeg,png,jpg,gif,svg|max:1024']);
        $id = $request->item_id;

        //Updating product details.
        $oem_part_no = $request['oem'];
        $product_code = $request['code'];
        $brand = $request['brand'];
        $item = $request['product'];
        $vehicle = $request['vehicle'];
        $model = $request['model'];
        $make = $request['make'];
        
        DB::table('items')->where('item_id',$id)->update([
                'product_code'=>$product_code,'oem_part_no'=>$oem_part_no,'brand'=>$brand,'item'=>$item,'vehicle'=>$vehicle,'model'=>$model,'make'=>$make]);

        $image_file = $request['image_file'];
        if($image_file!=NULL) {
            $image_name = time().'.'.$image_file->getClientOriginalName();
            $destination_path = '../public/uploads'; 
            $image_file->move($destination_path,$image_name);

            DB::table('images')->insert([
                'image_url'=>'/uploads'.'/'.$image_name]);

            $img_id = DB::table("images")
                    ->where("image_url",'/uploads'.'/'.$image_name)
                    ->pluck("image_id")->first();

            
                DB::table('item_images')->insert([
                'item_id'=>$id,'image_id'=>$img_id]);
            }         
        

        return redirect()->route('home');
    }

    public function destroy(Request $request)
    {
        //Deleting product from database.
        $id = $request->id;
        DB::table('items')->where('item_id',$id)->delete();
        $img_id = DB::table('item_images')->where('item_id',$id)->pluck('image_id')->first();
        DB::table('item_images')->where('item_id',$id)->delete();
        DB::table('images')->where('image_id',$img_id)->delete();
        return redirect()->route('home');
    }

    public function getVehicles(Request $request)
    {
        //For selecting vehicle of a particular brand.
        $vehicles = DB::table("vehicles")
                    ->where("brand_id",$request->brand_id)
                    ->pluck("vehicle_name","vehicle_id")->toArray();
        return response()->json($vehicles);        
    }

    public function error(Request $request)
    {
        $request->session()->invalidate();
        return view('error');
    }

    public function destroy_image(Request $request)
    {
        //dd($request);
        $img_url = $request->img_url;
        $img_id = DB::table('images')->where('image_url',$img_url)->pluck('image_id')->first();
        DB::table('item_images')->where('image_id',$img_id)->delete();
        //DB::table('images')->where('image_id',$img_id)->delete();

        return redirect()->back();
    }

    /*public function getData()
    {
        //Datatable
        $items = Item::join('brands','items.brand','=','brands.brand_id')->join('vehicles','items.vehicle','=','vehicles.vehicle_id')->select(['items.item_id','items.product_code','items.item','items.make','brands.brand_name','vehicles.vehicle_name']);  
        
        return Datatables::of($items)
                    ->addColumn('action', function ($items) {
                return '<a href="'.route("edit", ['id'=>$items->item_id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                <a href="'.route("destroy", ['id'=>$items->item_id]).'" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            
            })->make(true);
    }*/
}
