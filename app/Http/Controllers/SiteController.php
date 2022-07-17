<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site;
use Illuminate\Support\Facades\DB;

class SiteController extends Controller
{
    public function homepage(Request $request)
    {
        if($request->session()->get("select")){
            $limit=$request->session()->get("select");
        }else{
            $limit="25";
        }
        $products = DB::table("sites")->orderBy("id","asc")->limit($limit)->offset(0)->get();

        return view("welcome",[
            "datas"=> $products,
            "prevpage" => -1,
            "nextpage" => 1,
            "maxpage" => ceil(count(Site::all())/$limit)
        ]);
    }

    public function page(Request $request, $page){
        
        if($request->session()->get("select")){
            $limit=$request->session()->get("select");
        }else{
            $limit="25";
        }

        $products = DB::table("sites")->orderBy("id","asc")->limit($limit)->offset($page*$limit)->get();

        return view( "welcome", [
            "datas" => $products,
            "prevpage" => $page -1,
            "nextpage" => $page+1,
            "maxpage" => ceil(count(Site::all())/$limit)
        ] );
    }

    public function create(Request $request){
        $request->session()->put("url" , url()->previous());
        return view("create");
    }
    public function edit( Request $request ,$id){
        $request->session()->put("url" , url()->previous());
        return view("create",[
            "data"=> Site::find($id)
        ]);
    }
    public function delete($id){
        $product = Site::find( $id );
        $product->delete();
        return redirect( url()->previous() )->with('deletemsg',"Az adat törlésre került")->with('deletedproduct',$product);
    }
    public function update(Request $request){
        
        Site::updateOrCreate(
            ['id' => $request->id],
            ['name' => $request->name, 'email' => $request->email]
        );
        return redirect( $request->session()->get("url") );
    }

    public function deleteundo($data){

        $manage = json_decode($data, true);

        DB::table('sites')->insert([
            'id' => $manage["id"],
            'name' => $manage["name"],
            'email' => $manage["email"],
            'created_at' => DB::raw('NOW()'),
            'updated_at' => DB::raw('NOW()'),

        ]);

        return redirect( url()->previous() )->with('alert',"Termék visszahelyezésre került")->with('deletedproductid', $manage["id"]);
    }
    public function search(Request $request){
        if ($request->searchBy == "All"){
            return redirect("/");
        }
        if($request->session()->get("select")){
            $limit=$request->session()->get("select");
        }else{
            $limit="25";
        }
        $products = DB::table("sites")->where("$request->searchBy","Like", '%'.$request->search.'%')->get();

        return view("welcome",[
            "datas"=> $products,
            "prevpage" => -1,
            "nextpage" => 1,
            "maxpage" => 1,
            "counter" => count($products)
        ]);

    }
    public function select(Request $request){
        $request->session()->put("select", $request->select);
        return redirect( "/");
    }

    public function getall(){
        return Site::all(); 
    }

    public function import($data){
        //$list = [];
        foreach ( explode("|",$data) as $key => $value){
            $sublist=[];
            foreach ( explode(",",$value) as $k => $v){
                if($v != ""){
                    array_push($sublist,$v);
                }
            }
            //array_push($list,$sublist);

            Site::updateOrCreate(
                ['id' => $sublist["0"]],
                ['name' => $sublist["1"], 'email' => $sublist["2"]]
            );
        }
        
    }
    
}
