<?php

namespace Autovilla\Http\Controllers;

use Illuminate\Http\Request;
use Autovilla\Brand;
use Illuminate\Support\Facades\DB;
use Autovilla\Item;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Input;

class SiteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Returns homepage for user
        return view('site');
    }

    public function search(Request $request)
    {
        //For searching database.
        $q = $request['query'];
        if($q==NULL)
            return redirect()->route('site');
        // $terms = explode(' ', $q);
        // $term = implode('%', $terms);
        $keywords = preg_split('/[\s,\+]+/', $q);
        $big_array = preg_split('/[\s,\+]+/', $q); 
        $k = sizeof($keywords);
        $items = NULL;

        foreach ($keywords as $i => $keyword) {            
            // escapes and quotes those keywords to prevent against injection attack
            $keywords[$i] = "'" .$keyword. "'";            
        }

        //Checking for every keyword in database.
        $like_search = " + ";
        for($l=0;$l<$k;$l++){
            $like_search .= " CASE WHEN items.oem_part_no LIKE '%".$big_array[$l]."%' THEN 1 ELSE 0 END + CASE WHEN brands.brand_name LIKE '%".$big_array[$l]."%' THEN 1 ELSE 0 END + CASE WHEN items.product_code LIKE '%".$big_array[$l]."%' THEN 1 ELSE 0 END + CASE WHEN items.model LIKE '%".$big_array[$l]."%' THEN 1 ELSE 0 END + CASE WHEN items.item LIKE '%".$big_array[$l]."%' THEN 1 ELSE 0 END + CASE WHEN vehicles.vehicle_name LIKE '%".$big_array[$l]."%' THEN 1 ELSE 0 END + CASE WHEN items.make LIKE '%".$big_array[$l]."%' THEN 1 ELSE 0 END + ";
        }

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

        $paginate = 20;
        $page = Input::get('page', 1);
    
        $offSet = ($page * $paginate) - $paginate;  
        $itemsForCurrentPage = array_slice($items, $offSet, $paginate, true);  
        $result = new \Illuminate\Pagination\LengthAwarePaginator($itemsForCurrentPage, count($items), $paginate, LengthAwarePaginator::resolveCurrentPage(),array('path' =>  LengthAwarePaginator::resolveCurrentPath()));

        //dd($terms);
        //foreach ($terms as $term) {
            // $items = DB::table('items')
            //         ->join('brands','items.brand','=','brand_id')
            //         ->join('vehicles','items.vehicle','=','vehicle_id')
            //         ->where('brand_name', 'LIKE', '%'.$term.'%')
            //         ->orWhere('item', 'LIKE', '%'.$term.'%')
            //         ->orWhere('vehicle_name', 'LIKE', '%'.$term.'%')
            //         ->orWhere('make', 'LIKE', '%'.$term.'%')
            //         ->orWhere('model', 'LIKE', '%'.$term.'%')
            //         ->orWhere('product_code', 'LIKE', '%'.$term.'%')
            //         ->orWhere('oem_part_no', 'LIKE', '%'.$term.'%')->paginate(20);  
        //}
            //dd($result);
        $i = 0;
        foreach($result as $item) {            
        $img_id = DB::table('item_images')->where('item_id',$item->item_id)->pluck('image_id')->toArray();        
        //dd($img_id);
        if($img_id != NULL) { 
            $img[$i] = DB::table('images')->where('image_id',$img_id)->pluck('image_url')->first();
            //$result[$i]->image = DB::table('images')->where('image_id',$img_id)->pluck('image_url')->first();
            $i++;
            }
            else
            {
                //$result[$i]->image = '/uploads/auto_kart.png';
                $img[$i] = "/uploads/auto_kart.png";
                $i++;

            }
        }
        
        //dd($result);
        $i = 0;
        return view('user.search', compact('result','q','img','i'));
    }

    public function show($id)
    {   
        //For displaying particular product details along with its image.
        $item = Item::findOrFail($id);
        $img_id = DB::table('item_images')->where('item_id',$id)->pluck('image_id')->toArray();
        
        if($img_id != NULL) {
            foreach ($img_id as $key) {
                $img[] = DB::table('images')->where('image_id',$key)->pluck('image_url')->first();
             }            
            }
            else
            {
                $img[] = "/uploads/auto_kart.png";
            }
        //dd($item,$img);    
        return view('user.prod_desc', compact('item','img'));
    }    
}
