<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\UserProfile;
use Charts;
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$user=Auth::user();
		$userProf=UserProfile::where('user_id', $user->id)->first();
		if ($userProf->manager){
		$manager=UserProfile::where('user_id', $userProf->manager)->first();
		}
		else{
		$manager=0;
		}
		$partnerPads=\App\PartnerPad::where('user_id', $userProf->user_id)->get();
		$doups=\DB::table('test_doup')->get();
		$name=[];
		$summa=[];
		foreach ($doups as $doup){
			$name[]=$doup->domain;
			$summa[]=$doup->summa;
		}
		$chart=Charts::create('donut', 'c3')
  ->labels($name)
  ->values($summa)
  ->dimensions(0,0)
  ->height(257)
  ->title('')
  ->legend(false)
  ->responsive(false);
        return view('home', ['user'=>$user, 'manager'=>$manager, 'userProf'=>$userProf, 'partnerPads'=>$partnerPads, 'chart'=>$chart]);
    }
}
