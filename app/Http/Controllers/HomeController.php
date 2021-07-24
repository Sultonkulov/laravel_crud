<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\User;

class HomeController extends Controller
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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function html()
    {
        $pageElements = 10;
        $user = User::join('user_types', 'user_types.id', '=', 'users.type_id','left')
        ->select('users.*','user_types.name AS uTypeName');

        $searchText = trim(\request()->get('search'));

        $clear = (int)\request()->get('clear'); 
        if(empty($searchText)){
            if($clear === 1){
                Session::put('search', null);
            }else{
                $searchText = Session::get('search');
            }
        }else{
             Session::put('search', "$searchText");
        }
        if(!empty($searchText)){
            
            $user->where('users.name','like', '%'.$searchText.'%');
            
        }

        $uType = (int)\request()->get('uType');
 
        if($uType === 0){
            if($clear === 1){
                Session::put('uType', '');
            }else{
                $uType = Session::get('uType');
            }
        }else{
             Session::put('uType', "$uType");
        }
        if($uType > 0 ){
            $user->where('type_id', '=', $uType);
        }

       

       $fix = $user->paginate( $pageElements);
       $page = (int)\request()->get('page');
       if($page === 0 ) $page = 1;
       $start =  $pageElements * ($page-1);

       $uTypes = DB::table('user_types')-> get();

       return view('list', [
        'users'=>$fix,
        'start'=>$start, 
        'searchText'=>$searchText, 
        'uTypes'=>$uTypes, 
        'uTypeId'=>$uType, 
        'pageId'=>'$page'
        ]);
    }
    public function delete($id){
        DB::delete('DELETE FROM users WHERE id = ? ', [$id]);
        $page = (int)\request()->get('page');
        if($page === 0 ) $page = 1;

        return redirect('/html?page='.$page);
    }
    public function edit($id){
       $record = DB::table('users')->where('id', $id)->first();

       return view('edit', ['data'=>$record]);
    }
}

