<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\UserProfile;
use App\PartnerPad;
use Illuminate\Support\Facades\Validator;
class PartnerPadController extends Controller
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
		$stcurl=$request->input('stcurl');
		$stclogin=$request->input('stclogin');
		$stcpassword=$request->input('stcpassword');
		$parse = parse_url(trim($domain));
		$out = $domain;
		if (isset($parse['host'])) {
			if ($parse['host']){
				$out = $parse['host'];
			}
			else{
				$out = explode('/', $parse['path'], 2);
				$out = array_shift($out);
				$out = trim($out);
			}
		}
		else{
			$out = explode('/', $parse['path'], 2);
			$out = array_shift($out);
			$out = trim($out);
		}
		if ($out){
			$domain = $out;
		}
		$validator=Validator::make(
			array(
				'domain' => $domain
			),
			array(
				'domain' => 'required|string|max:255|unique:partner_pads',
			)
		);
		if ($validator->fails()){
			return back()->withErrors($validator)->withInput();
		}
		if ($type){
		$type=$this->settype($type);
		}
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
	public function setType($type){
		$cnt=count($type);
		if ($cnt>1){
			$ctype=3;
		}
		else{
			foreach ($type as $tp){
				$ctype=$tp;
			}
		}
		return $ctype;
	}
	
}
