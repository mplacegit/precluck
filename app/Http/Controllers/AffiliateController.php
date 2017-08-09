<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\UserProfile;
use App\PartnerPad;
class AffiliateController extends Controller
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
    public function addPads(Request $request){
		
		$user_id=$request->input('user_id');
		$domain=$request->input('domain');
		$type=$request->input('type');
		$cnt=count($type);
		if ($cnt>1){
			$type=3;
		}
		else{
			$type=$type;
		}
		$stcurl=$request->input('stcurl');
		$stclogin=$request->input('stclogin');
		$stcpassword=$request->input('stcpassword');
		$partnerPad= new PartnerPad;
		$partnerPad->user_id=$user_id;
		$partnerPad->domain=$domain;
		$partnerPad->type=$type;
		$partnerPad->stcurl=$stcurl;
		$partnerPad->stclogin=$stclogin;
		$partnerPad->stcpassword=$stcpassword;
		$partnerPad->status=0;
		$partnerPad->save();
		return back()->with('message_success','Площадка успешно добавлена на модерацию.');
    }
}
