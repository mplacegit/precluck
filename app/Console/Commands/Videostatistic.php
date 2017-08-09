<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminated\Console\WithoutOverlapping;
class Videostatistic extends Command
{
use WithoutOverlapping;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'videostatistic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	$myvideogets= new \App\Videosource\Statistic;
	$mypdo = \DB::connection("mysqlapi")->getPdo(); 
    $sql="select * from video_widgets where id =? ";
	$mysthWid=$mypdo->prepare($sql);
	$pdo = \DB::connection("pgstatistic")->getPdo();
   
	$sql="insert into videostatistic_loadwidget (event_name,datetime,cnt)
	select ?,?,?
	WHERE NOT EXISTS (SELECT 1 FROM videostatistic_loadwidget WHERE event_name=? and datetime=?) 
	";
	$sthInsert=$pdo->prepare($sql);
	$sql="update videostatistic_loadwidget set cnt=cnt+?
	WHERE event_name=? and datetime=?
	";
	$sthUpdate=$pdo->prepare($sql);
	$sql="insert into videostatistic_source_events (id_src,event_name,datetime,cnt)
	select ?,?,?,?
	WHERE NOT EXISTS (SELECT 1 FROM videostatistic_source_events WHERE id_src=? and event_name=? and datetime=?) 
	";
	$sthInsertSource=$pdo->prepare($sql);
	$sql="update videostatistic_source_events set cnt=cnt+?
	WHERE id_src=? and event_name=? and datetime=?
	";
	$sthUpdateSource=$pdo->prepare($sql);
	$sql="insert into videostatistic_pids (pid,day,country,pad_id,type,deep,plays_all,fplays_all,firstplays_all,midplays_all,thirdplays_all,completeplays_all,plays_user,fplays_user)
	select ?,?,?,?,?,?,?,?,?,?,?,?,?,?
	WHERE NOT EXISTS (SELECT 1 FROM videostatistic_pids WHERE pid=? and day=? and country=?) 
	";
	$sthInsertPids=$pdo->prepare($sql);
	$sql="update videostatistic_pids set deep=(deep+?)/2
	,plays_all=plays_all+?
	,fplays_all=fplays_all+?
	,firstplays_all=firstplays_all+?
	,midplays_all=midplays_all+?
	,thirdplays_all=thirdplays_all+?
	,completeplays_all=completeplays_all+?
	,plays_user=plays_user+?
	,fplays_user=fplays_user+?
	 WHERE pid=? and day=? and country=?
	";
	$sthUpdatePids=$pdo->prepare($sql);
	file_put_contents("/var/log/nginx/api.market-place.su-predvideostat.log",date("Y-m-d H:i:s")."\n",\FILE_APPEND);
    $tmp_file="/var/log/nginx/preparevideostat_".time()."_.log";
    $cmd ="cp -p /var/log/nginx/api.market-place.su-videostat.log $tmp_file && cat /dev/null > /var/log/nginx/api.market-place.su-videostat.log";
	`$cmd`;
	$padsEvents=["errorPlayMedia"=>1
	,"srcRequest"=>1
	,"srcLoadError"=>1
	,"errorPlayMedia"=>1
	,"startPlayMedia"=>1
	,"filterPlayMedia"=>1
	,"start"=>1
	,"firstQuartile"=>1
	,"midpoint"=>1
	,"thirdQuartile"=>1
	,"complete"=>1
	];
	$events=[];
	$affiliates=[];
	$cachpdoi=[];
	$cach=[];
	#$list=file($tmp_file);
	$handle = @fopen($tmp_file, "r");
if ($handle) {
    while (($buffer = fgets($handle, 4096)) !== false) {
      
		$l = str_replace("\n", "", $buffer);
		#echo $line."--->\n";
		$graphes=0;
	$tmp=preg_split("/\s+\:\s+/",$l);
	$req=preg_split("/\s+/",$tmp[5]);
    parse_str($req[1], $arr);
	if(!isset($arr["data"])){
	continue;
	}
	$data=json_decode($arr["data"],true);
    $id_src=$data["id_src"];
	$eventName=$data["event"];
		switch($eventName){
	case "loadWidget":
	case "clickThrough":
	case "filterPlayMedia":
	case "srcRequest":
	$graphes=1;
	break;
	default:
	
	#continue 2;
	break;
	}	
	$url=urldecode($data["fromUrl"]);
	$key=urldecode($data["key"]);
	$tdu=parse_url($url);
	if(isset($tdu["host"]))
	$host=$tdu["host"];
	else
	$host="";
	$tmp[0]=preg_replace("/^\[|\]$/","",$tmp[0]);
	$time = strtotime($tmp[0]);
	$time=$time-($time%180);
	$datetime=date("Y-m-d H:i:00",$time);
	$day=date("Y-m-d",$time);
	$ip=$tmp[1];
	
	if($id_src!=0){
	$packet=["host"=>$host,"event"=>$eventName,"day"=>$day,"id_src"=>$id_src];
	$myvideogets->copyData($packet);
	}
	if($host && $graphes==1){
	
	if(!isset($events[$eventName])){
	$events[$eventName]=[];
	}
	if(!isset($events[$eventName][$datetime])){
	$events[$eventName][$datetime]=[];
	}
	if(!isset($events[$eventName][$datetime][$id_src])){
	$events[$eventName][$datetime][$id_src]=["cnt"=>0];
	}
	$events[$eventName][$datetime][$id_src]["cnt"]++;
    }
	$pid=$data["pid"];	
	$country=trim($tmp[2]);
	$affiliate_id=$data["affiliate_id"];
	$affiliate_id="1111";
	if(!isset($affiliates[$affiliate_id])){
	$affiliates[$affiliate_id]=[];
	}
	if(!isset($affiliates[$affiliate_id][$pid])){
	$affiliates[$affiliate_id][$pid]=[];
	}
	if(!isset($affiliates[$affiliate_id][$pid][$day])){
	$affiliates[$affiliate_id][$pid][$day]=[];
	}
	if(!isset($affiliates[$affiliate_id][$pid][$day][$country])){
	$affiliates[$affiliate_id][$pid][$day][$country]=[];
	}
	if(!isset($affiliates[$affiliate_id][$pid][$day][$country][$eventName])){
	$affiliates[$affiliate_id][$pid][$day][$country][$eventName]=[];
	}
	
	if(!isset($affiliates[$affiliate_id][$pid][$day][$country][$eventName][$key])){
	$affiliates[$affiliate_id][$pid][$day][$country][$eventName][$key]=0;
	}
	$affiliates[$affiliate_id][$pid][$day][$country][$eventName][$key]++;	
		
    }
    fclose($handle);
   }
	/*
	foreach($list as $l){
	$graphes=0;
	$tmp=preg_split("/\s+\:\s+/",$l);
	$req=preg_split("/\s+/",$tmp[5]);
    parse_str($req[1], $arr);
	if(!isset($arr["data"])){
	continue;
	}
	$data=json_decode($arr["data"],true);
    $id_src=$data["id_src"];
	$eventName=$data["event"];
	switch($eventName){
	case "loadWidget":
	case "clickThrough":
	case "filterPlayMedia":
	case "srcRequest":
	$graphes=1;
	break;
	default:
	
	#continue 2;
	break;
	}
	
	
	$url=urldecode($data["fromUrl"]);
	$key=urldecode($data["key"]);
	#print $key."\n";
	$tdu=parse_url($url);
	if(isset($tdu["host"]))
	$host=$tdu["host"];
	else
	$host="";
	$tmp[0]=preg_replace("/^\[|\]$/","",$tmp[0]);
	$time = strtotime($tmp[0]);
	$time=$time-($time%180);
	$datetime=date("Y-m-d H:i:00",$time);
	$day=date("Y-m-d",$time);
	$ip=$tmp[1];
	
	if($id_src!=0){
	$packet=["host"=>$host,"event"=>$eventName,"day"=>$day,"id_src"=>$id_src];
	$myvideogets->copyData($packet);
	}
	if($host && $graphes==1){
	
	if(!isset($events[$eventName])){
	$events[$eventName]=[];
	}
	if(!isset($events[$eventName][$datetime])){
	$events[$eventName][$datetime]=[];
	}
	if(!isset($events[$eventName][$datetime][$id_src])){
	$events[$eventName][$datetime][$id_src]=["cnt"=>0];
	}
	$events[$eventName][$datetime][$id_src]["cnt"]++;
    }
	$pid=$data["pid"];
	#if($pid=="41"){
	#print_r($data);
	#}
	$country=trim($tmp[2]);
	$affiliate_id=$data["affiliate_id"];
	#$affiliate_id=$key;
	if(!isset($affiliates[$affiliate_id])){
	$affiliates[$affiliate_id]=[];
	}
	if(!isset($affiliates[$affiliate_id][$pid])){
	$affiliates[$affiliate_id][$pid]=[];
	}
	if(!isset($affiliates[$affiliate_id][$pid][$day])){
	$affiliates[$affiliate_id][$pid][$day]=[];
	}
	if(!isset($affiliates[$affiliate_id][$pid][$day][$country])){
	$affiliates[$affiliate_id][$pid][$day][$country]=[];
	}
	if(!isset($affiliates[$affiliate_id][$pid][$day][$country][$eventName])){
	$affiliates[$affiliate_id][$pid][$day][$country][$eventName]=[];
	}
	
	if(!isset($affiliates[$affiliate_id][$pid][$day][$country][$eventName][$key])){
	$affiliates[$affiliate_id][$pid][$day][$country][$eventName][$key]=0;
	}
	$affiliates[$affiliate_id][$pid][$day][$country][$eventName][$key]++;
	#print_r($data);
	#print "$affiliate_id / $pid\n";
	
	
	}
	*/
	foreach($events as $event=>$sck){
	foreach($sck as $date=>$clients){
	foreach($clients as $id_src=>$obj){
	switch($event){
	case "loadWidget":
	$sthUpdate->execute([$obj["cnt"],$event,$date]);
	$sthInsert->execute([$event,$date,$obj["cnt"],$event,$date]);
	
	break;
	default:
	$sthUpdateSource->execute([$obj["cnt"],$id_src,$event,$date]);
	$sthInsertSource->execute([$id_src,$event,$date,$obj["cnt"],$id_src,$event,$date]);
	break;
	}
	}
	}
	}
	unset($events);
	foreach($affiliates as $aff_id=>$pids){
	#print $aff_id."\n";
	  foreach($pids as $pid_id=>$days){
	  if(!isset($cachpdoi[$pid_id])){
	  $mysthWid->execute([$pid_id]);
	  $result = $mysthWid->fetchAll(\PDO::FETCH_ASSOC);
      if(count($result)!=1){
	 # print "говно закралось в подземелье $pid_id / ".count($result)."\n"; 
	  continue;
	  }
	  $cachpdoi[$pid_id]=$result[0];
	  }
	  
	  foreach($days as $day=>$countries){
	  
	  foreach($countries as $country=>$evs){
	  $mokasin=[];
	   $usersin=[];
	  foreach($padsEvents as $eventKey=>$obj){
	  if(isset($evs[$eventKey])){
	  $mokasin[$eventKey]=array_sum($evs[$eventKey]);
	  $usersin[$eventKey]=count($evs[$eventKey]);
	 
	  }else{
	  $mokasin[$eventKey]=0;
	  $usersin[$eventKey]=0;
	  }
	 
	  }
	  
	 # print "start:".$mokasin['start']."|firstQuartile:".$mokasin['firstQuartile']."\n";
   # $deep=($mokasin['start']-$mokasin['firstQuartile'])*0
    #            +($mokasin['firstQuartile']-$mokasin['midpoint'])*25
     #           +($mokasin['midpoint']-$mokasin['thirdQuartile'])*50
      #          +($mokasin['thirdQuartile']-$mokasin['complete'])*75
       #         +$mokasin['complete']*100;        
      $count=$mokasin["filterPlayMedia"];
      #$resDeep=$count?$deep/$count:0;
	  if($mokasin["startPlayMedia"])
	  $resDeep=$mokasin["filterPlayMedia"]/$mokasin["startPlayMedia"];
	  # $resDeep=$mokasin["filterPlayMedia"]-$mokasin["startPlayMedia"];
	  else
	  $resDeep=$mokasin["filterPlayMedia"];
	  $resDeep=0;
	   #print $pid_id." $day : $country : ".$mokasin["startPlayMedia"]." / ".$mokasin["filterPlayMedia"]."\n";
	  #if($mokasin["startPlayMedia"])#
      $sthUpdatePids->execute([$resDeep,$mokasin["startPlayMedia"],$mokasin["filterPlayMedia"],$mokasin["firstQuartile"],$mokasin['midpoint'],$mokasin['thirdQuartile'],$mokasin['complete'],$usersin["startPlayMedia"],$usersin["filterPlayMedia"],$pid_id,$day,$country]);	 
	  $sthInsertPids->execute([$pid_id,$day,$country,$cachpdoi[$pid_id]["pad_id"],$cachpdoi[$pid_id]["type"],$resDeep,$mokasin["startPlayMedia"],$mokasin["filterPlayMedia"],$mokasin["firstQuartile"],$mokasin['midpoint'],$mokasin['thirdQuartile'],$mokasin['complete'],$usersin["startPlayMedia"],$usersin["filterPlayMedia"],$pid_id,$day,$country]);
		  }
	    }
	  }
	}
	 $myvideogets->checkData();
	 $myvideogets->DeepUtils();
	 $cmd ="rm  -f  $tmp_file";
	`$cmd`;
	
    print "привет из кудымкара\n";
    }
}
