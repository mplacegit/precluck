<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WidgetEditor extends Model
{
   public function widgetSave($parameters){
   
   }
   public function render($parameters){
      
   		 $body=$this->getTemplate($parameters);
		 $style=$this->getStyle($parameters);
		 $script=$this->getScript($parameters);
   		 if (!isset($parameters["name"])){
		 $name="block-mono";
		}
		else{
			$name=$parameters["name"];
		}
		if (!isset($parameters["width"]) || !isset($parameters["height"])){
			$width="100px";
			$height="100px";
		}
		else{
			$width=$parameters["width"];
			$height=$parameters["height"];
		 }
		  
		 $res=["name"=>$name, "body"=>$body, "style"=>$style, "script"=>$script, "width"=>$width, "height"=>$height];
    return $res;
   }
   	public function getTemplate($parameters){
	if(!isset($parameters["name"])){
	$name="block-mono";
	}
	else{
	$name=$parameters["name"];
	}
    $b="/home/www/api.market-place.su/widget_product/templates/widget-".$name."/body.html";
	if(!is_file($b)){
	echo "незабуду !!!";  exit();
	}
	$body=file_get_contents($b);
	return $body;
	}
	
	public function getStyle($parameters){
	if(!isset($parameters["name"])){
	$name="block-mono";
	}
	else{
	$name=$parameters["name"];
	}
    $css="/home/www/api.market-place.su/widget_product/templates/widget-".$name."/css/widget-slider-big.css";
	//var_dump($css); die();
	if(!is_file($css)){
	echo "незабуду !!!";  exit();
	}
	$style=file_get_contents($css);
	return $style;
	}
	
	public function getScript($parameters){
	if(!isset($parameters["name"])){
	$name="block-mono";
	}
	else{
	$name=$parameters["name"];
	}
    $s="/home/www/api.market-place.su/widget_product/templates/widget-".$name."/js/~init.js";
	if(!is_file($s)){
	echo "незабуду !!!";  exit();
	}
	$script=file_get_contents($s);
	return $script;
	}
}
