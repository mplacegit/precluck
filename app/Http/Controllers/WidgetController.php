<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\User;
use Auth;
class WidgetController extends Controller
{
	public function edit($id=0){
		$templateTypes=\DB::table('widget_product_types')->whereNotIn('name', array('mobile'))->get();
		$widgetTemplates=\DB::table('widget_product_templates')->get();
		return view('widget.editor', ['templateTypes'=>$templateTypes, 'widgetTemplates'=>$widgetTemplates,"id_widget"=>$id]);
	}
	public function render(Request $request){
	    $data=$request->input('data');
		if($data){
		$parameters=json_decode($data,true);
		}else{
		$parameters=[];
		}
		$wid=new \App\WidgetEditor();
		$args=$wid->render($parameters);
		return view('widget.render',$args);
		
	}
	public function saveWidget(Request $request){
	//$wid=new \App\MPW\Widgets\Widget;
	    $id_widget=$request->input('id_widget');
		$wid=\App\MPW\Widgets\Product::findOrNew($id_widget);
		//if(!$id_widget){
		//$wid=new \App\MPW\Widgets\Product;
		$wid->user_id=Auth::user()->id;
		$wid->type=1;
		//}else{
		//$wid=\App\MPW\Widgets\Product::findOrNew($id_widget);
		//}
		# ->withErrors($validator)->withInput();
        $wid->save();
		return redirect()->route('admin.widget.editor',['id' => $wid->id])->withInput();

		
		
		
	 print "<pre>"; print_r($wid);  print "</pre>"; die();
	 print "<pre>"; print_r($request->toArray());  print "</pre>";
		
	}
}
