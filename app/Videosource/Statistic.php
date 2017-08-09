<?php

namespace App\Videosource;

use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    private $eventsHeader=[
	"filterPlayMedia"=>1
	,"clickThrough"=>1];
    private $list=[];
    public function copyData(&$data){
	switch($data["event"]){
	case "filterPlayMedia":
	break;
	case "clickThrough":
	break;
	default:
	return;
	break;
	}
	
	#print $data["host"]." ".$data["event"]." ".$data["day"]."\n";
	if(!isset($this->list[$data["id_src"]])){
	   $this->list[$data["id_src"]]=[];
	  }	
	
	if(!isset($this->list[$data["id_src"]][$data["host"]])){
	   $this->list[$data["id_src"]][$data["host"]]=[];
	  }	
	
	if(!isset($this->list[$data["id_src"]][$data["host"]][$data["day"]])){
	   $this->list[$data["id_src"]][$data["host"]][$data["day"]]=[];
	  }
	  
	if(!isset($this->list[$data["id_src"]][$data["host"]][$data["day"]][$data["event"]])){
	   $this->list[$data["id_src"]][$data["host"]][$data["day"]][$data["event"]]=["cnt"=>0];
	 } 
	  
	$this->list[$data["id_src"]][$data["host"]][$data["day"]][$data["event"]]["cnt"]++;
	}
	public function checkData(){
	//return;
	$pdo = \DB::connection("pgstatistic")->getPdo();
	$sql="insert into videostatistic_cmpclicks (id_src,host,day,deep,played,clicked)
	select ?,?,?,?,?,?
	WHERE NOT EXISTS (SELECT 1 FROM videostatistic_cmpclicks WHERE id_src=? and host=? and day=?) 
	";
	$sthInsert=$pdo->prepare($sql);
	$sql="update videostatistic_cmpclicks set deep=(deep+?)/2,played=played+?,clicked=clicked+?
	 WHERE id_src=?  and host=? and day=?
	";
	$sthUpdate=$pdo->prepare($sql);
	 foreach($this->list as $id_src=>$hosts){
	 foreach($hosts as $host=>$days){
	 
	    foreach($days as $day=>$events){
		  
			$capsul=[];
			foreach($this->eventsHeader as $e=>$chota){
			   if(isset($events[$e])) {
			   $capsul[$e]=$events[$e]["cnt"];
			   
			   }else{
			   $capsul[$e]=0;
			   }
			}
			 if($capsul["filterPlayMedia"])
			   $secund=$capsul["clickThrough"]/$capsul["filterPlayMedia"];
			   else{
			   $secund=$capsul["clickThrough"];
			   }
			   $secund=0;
			   $sthUpdate->execute([$secund,$capsul["filterPlayMedia"],$capsul["clickThrough"],$id_src,$host,$day]);
			   $sthInsert->execute([$id_src,$host,$day,$secund,$capsul["filterPlayMedia"],$capsul["clickThrough"],$id_src,$host,$day,]);
			   
			   #print $id_src." ".$host." $day ".$capsul["clickThrough"]." / ".$capsul["filterPlayMedia"]." = $secund \n";
			}
		}
	  }
	}
	public function DeepUtils(){
	$date=date("Y-m-d");
	$pdo = \DB::connection("pgstatistic")->getPdo();
		
	$sql="update videostatistic_pids set deep=?
	,util=?
	 WHERE pid=? and day=? and country=?
	";
	$sthUpdatePids=$pdo->prepare($sql);
	
	$sql="select pid
		,country
		,pad_id
		,type
		,plays_all
        ,fplays_all
        ,firstplays_all
        ,midplays_all
        ,thirdplays_all
        ,completeplays_all
		from videostatistic_pids
		where day ='".$date."'
		";
		#echo $sql; 
		#return;
		$result = $pdo->query($sql)->fetchAll(\PDO::FETCH_CLASS);
		foreach($result as $r){
		$depp=$this->funcMakeDeep($r);
		$util=$this->funcMakeUtil($r);
		$sthUpdatePids->execute([$depp,$util,$r->pid,$date,$r->country]);
		#print $r->pid." :: ".$r->country." $depp $util\n";
		}
	print "привет из саратова $date\n";
	}
  private function funcMakeDeep(&$obj){

  	   if($obj->fplays_all){
  $x1=$obj->firstplays_all*100/$obj->fplays_all;
  
  $x2=$obj->midplays_all*100/$obj->fplays_all;
  $x3=$obj->thirdplays_all*100/$obj->fplays_all;
  $x4=$obj->completeplays_all*100/$obj->fplays_all;
  //thirdplays_all int
      //  ,completeplays_all int
  }
  else{
  $x1=0;
  $x2=0;
  $x3=0;
  $x4=0;  
  }
  $x=0;
  #$x+=$x1/16+$x2/8+$x3/4+$x4/2;
  $x=$x4;
         #print $obj->fplays_all." от  ".$obj->firstplays_all." = $x1 <hr>";
         
  return round($x,4);
  } 
  private function funcMakeUtil(&$obj){
  if($obj->plays_all){
  $x1=$obj->fplays_all*100/$obj->plays_all;
  //thirdplays_all int
      //  ,completeplays_all int
  }
  else{
  $x1=0;
 
  }
   $x=$x1;
	 return round($x,4);
	  } 
}
