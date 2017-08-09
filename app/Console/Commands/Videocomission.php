<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Videocomission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'videocomission';

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
	 $from=$to="CURRENT_DATE";
     $pdo = \DB::connection("pgstatistic")->getPdo();
	 $sql="select country,day,pid
	 ,sum(fplays_user) as fplays_user
     ,sum(fplays_all) as fplays_all
	 ,avg(deep) as deep
	 FROM videostatistic_pids WHERE day BETWEEN $from and $to group by country,day,pid";
	 $result = $pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
	  $summary=array();
      $summary_f=array();
	 foreach($result as $row){
		    if($row["pid"]==409)continue;
			 $widget=\App\Models\Widgets\VideoWidget::find($row['pid']);
            if(!$widget){
                continue;
            }
			$comgr=$widget->CommissionGroup();
			$fcomgr=$widget->ForeignCommissionGroup();
			 if($row['country']=='RU'){
		    $val=round($comgr->value*$row['fplays_user']/1000,4);
			}else{
            $val=round($fcomgr->value*$row['fplays_user']/1000,4);
			}
			
		    #print "<pre>"; print_r($comgr->toArray()); print "</pre>";
			if($val>100){
			#print "\n"; print_r([$val,$row["fplays_user"],$row["fplays_all"]]); print "\n";
			}
			 if(!isset($summary[$row['pid']])){
                $summary[$row['pid']]=array(
                    "plays"=>0,
                    "requests"=>0,
                    "summa"=>0,
                    "deep"=>[],
                    "deepViews"=>0,
                    "datetime"=>date('Y-m-d')
                );
            }
            if(!isset($summary_f[$row['pid']])){
                $summary_f[$row['pid']]=array(
                    "plays"=>0,
                    "requests"=>0,
                    "summa"=>0,
                    "datetime"=>date('Y-m-d')
                );
            }
			
			if($row['fplays_user']){
			#print "\n"; print_r($row); print "\n";
			array_push($summary[$row['pid']]['deep'],$row['deep']);
			}
			$summary[$row['pid']]['plays']+=$row['fplays_user'];
			$summary[$row['pid']]['datetime']=$row['day'];
            $summary[$row['pid']]['summa']+=$val;
            #$summary[$row['pid']]['requests']+=$row['requests'];
            
            $summary[$row['pid']]['deepViews']+=$row['deep']?$row['fplays_user']:0;
			
     }
	  foreach($summary as $k=>$row){

	  if($row["deep"])
	  $average = array_sum($row["deep"]) / count($row["deep"]);
	  else
	  $average=0;
	 if($average && $row["summa"]>100){
	  print "\n"; print_r([$row["summa"],$row["plays"], $average]); print "\n";
	  }
	   }
	  
        print "привет из новошахтинска\n";
    }
}
