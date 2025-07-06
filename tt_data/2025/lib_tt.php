<?php



//global $dmap;
$dmap['class']['count'] = 0 ;
$dmap['teacher']['count'] = 0 ;

function echox($action,$msg)
{
	if( $action == 1 )
		echo($msg);
}

function newdatamap()
{
	global $dmap ;
	$dmap['class']['count'] = 0 ;
	$dmap['teacher']['count'] = 0 ;
	
	echox(0,"creating new data \n");
	
}
newdatamap() ;

function newclass($name,$fullname)
{
	global $dmap ;
	
	$dmap['class']['define'][$dmap['class']['count']]['name']=$name ;
	$dmap['class']['define'][$dmap['class']['count']]['fullname']=$fullname ;
	$dmap['class']['define'][$dmap['class']['count']]['subjects']['count'] = 0 ;
	
	$dmap['class']['count']++ ;
	
	echox(0,"creating new class $name :".$dmap['class']['count']."\n");
	
	return ;
}
function newteacher($name,$fullname,$limit)
{
	global $dmap ;
	
	$dmap['teacher']['define'][$dmap['teacher']['count']]['name']=$name ;
	$dmap['teacher']['define'][$dmap['teacher']['count']]['fullname']=$fullname ;
	$dmap['teacher']['define'][$dmap['teacher']['count']]['subjects']['count'] = 0 ;
	
	$dmap['teacher']['define'][$dmap['teacher']['count']]['limit']=$limit ;
	$dmap['teacher']['define'][$dmap['teacher']['count']]['bind']=0 ;
	
	$dmap['teacher']['count']++ ;
	
	echox(0,"creating new teacher $name :".$dmap['teacher']['count']."\n");
	
	return ;
}

function find_teacher($name)
{
	global $dmap ;
	$ret['state']=0;
	$ret['value']="NULL";
	for( $i=0 ; $i < $dmap['teacher']['count'] ; $i++)
	{
		if($dmap['teacher']['define'][$i]['name'] == $name)
		{
			$ret['state']=1;
			$ret['value']=$i;
			return $ret ;
		}
	}
	return $ret ;
}
function find_class($name)
{
	global $dmap ;
	$ret['state']=0;
	$ret['value']="NULL";
	for( $i=0 ; $i < $dmap['class']['count'] ; $i++)
	{
		if($dmap['class']['define'][$i]['name'] == $name)
		{
			$ret['state']=1;
			$ret['value']=$i;
			return $ret ;
		}
	}
	return $ret ;
}

function check_teacher($name,$day,$period)
{
	global $dmap ;
	$ccdata=find_teacher($name) ;
	if($ccdata['state']==0)
	{
		return 1 ;
	}
	$ccode=$ccdata['value'];
	if( isset( $dmap['teacher']['define'][$ccode][$day][$period]['state'] ) )
	{
		return 0 ;
	}
	else
	{
		return 1 ;
	}
}
function check_class($name,$day,$period)
{
	global $dmap ;
	$ccdata=find_class($name) ;
	if($ccdata['state']==0)
	{
		return 1 ;
	}
	$ccode=$ccdata['value'];
	
	if( isset( $dmap['class']['define'][$ccode][$day][$period]['state'] ))
	{
		return 0 ;
	
	}
	else
	{
		return 1 ;
	}
}
function set_subject_teacher($name,$subject,$class)
{
	global $dmap ;
	$ccdata=find_teacher($name) ;
	if($ccdata['state']==0)
	{
		return 0 ;
	}
	$ccode=$ccdata['value'];
	//$dmap['teacher']['define'][$ccode][$day][$period]['state']
	if(! isset( $dmap['teacher']['define'][$ccode]['subjects']['count'] ) )
	{
		$dmap['teacher']['define'][$ccode]['subjects']['count'] = 0 ;
	}
	$dmap['teacher']['define'][$ccode]['subjects'][$dmap['teacher']['define'][$ccode]['subjects']['count']]['subject']=$subject ;
	$dmap['teacher']['define'][$ccode]['subjects'][$dmap['teacher']['define'][$ccode]['subjects']['count']]['class']=$class ;
	
	$dmap['teacher']['define'][$ccode]['subjects']['count']++ ;
}
function set_subject_class($name,$subject,$count)
{
	global $dmap ;
	$ccdata=find_class($name) ;
	if($ccdata['state']==0)
	{
		return 0 ;
	}
	$ccode=$ccdata['value'];
	//$dmap['teacher']['define'][$ccode][$day][$period]['state']
	if(! isset( $dmap['class']['define'][$ccode]['subjects']['count'] ) )
	{
		$dmap['class']['define'][$ccode]['subjects']['count'] = 0 ;
	}
	$dmap['class']['define'][$ccode]['subjects'][$dmap['class']['define'][$ccode]['subjects']['count']]['subject']=$subject ;
	$dmap['class']['define'][$ccode]['subjects'][$dmap['class']['define'][$ccode]['subjects']['count']]['count']=$count ;
	
	$dmap['class']['define'][$ccode]['subjects']['map'][$subject]['count']=$count ;
	
	$dmap['class']['define'][$ccode]['subjects']['count']++ ;
}
function bind_period($class,$teacher,$subject,$day,$period)
{
	global $dmap ;
	
	
	if( check_class($class,$day,$period) == 1 && ( $class=="null" || check_teacher($teacher,$day,$period) == 1 ))
	{
		$ccode_t=find_teacher($teacher)['value'] ;
		
		$dmap['teacher']['define'][$ccode_t][$day][$period]['state']=1 ;
		$dmap['teacher']['define'][$ccode_t][$day][$period]['teacher']=$teacher ;
		$dmap['teacher']['define'][$ccode_t][$day][$period]['class']=$class ;
		$dmap['teacher']['define'][$ccode_t][$day][$period]['subject']=$subject ;
		$dmap['teacher']['define'][$ccode_t]['bind']++ ;
		
		
		if($class!="null")
		{
		$ccode_c=find_class($class)['value'] ;
		
		$dmap['class']['define'][$ccode_c][$day][$period]['state']=1 ;
		$dmap['class']['define'][$ccode_c][$day][$period]['teacher']=$teacher ;
		$dmap['class']['define'][$ccode_c][$day][$period]['class']=$class ;
		$dmap['class']['define'][$ccode_c][$day][$period]['subject']=$subject ;
		
		
		if(! isset( $dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['bind'] ) )
		{
			$dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['bind'] = 0 ;
		}
		$dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['bind']++ ;
		//echox(0,"done ".$ccode_c.":".$ccode_t." ".$class.":".$teacher."\n");
		
		}
		
		return 1;
	
	}
	else
	{
		echox(0,"slot is not empty\n");
		return 0;
	}

}

require("lib_tt_ai.php");

function print_table_class()
{
	global $dmap ;
	echox(0,"Printing Class Table\t".$dmap['class']['count'] ."\n\n");
	for( $i=0 ; $i < $dmap['class']['count'] ; $i++)
	{
		echox(0,$dmap['class']['define'][$i]['fullname']."\t");
		for($i2=0;$i2<$dmap['class']['define'][$i]['subjects']['count'];$i2++)
		{
			echox(0,$dmap['class']['define'][$i]['subjects'][$i2]['subject'].":".$dmap['class']['define'][$i]['subjects'][$i2]['count']." ");
		}
		echox(0,"\n");
		
		for( $id=1 ; $id <= 6 ; $id++ )
		{
			echox(0,"Day ".$id." :\t");
			for( $ip=1 ; $ip<= 8 ; $ip++ )
			{
				if(isset($dmap['class']['define'][$i][$id][$ip]['teacher']))
					echox(0,$dmap['class']['define'][$i][$id][$ip]['subject']."(".$dmap['class']['define'][$i][$id][$ip]['teacher'].")\t");
				else
					echox(0,"null\t");
			}
			echox(0,"\n");
		}
		echox(0,"\n");
	
	}

}

function print_table_teacher()
{
	global $dmap ;
	echox(0,"Printing Teacher Table\t".$dmap['teacher']['count'] ."\n\n");
	for( $i=0 ; $i < $dmap['teacher']['count'] ; $i++)
	{
		echox(0,$dmap['teacher']['define'][$i]['fullname']."\t");
		for($i2=0;$i2<$dmap['teacher']['define'][$i]['subjects']['count'];$i2++)
		{
			echox(0,$dmap['teacher']['define'][$i]['subjects'][$i2]['subject'].":".$dmap['teacher']['define'][$i]['subjects'][$i2]['class']." ");
		}
		echox(0,"\n");
		for( $id=1 ; $id <= 6 ; $id++ )
		{
			echox(0,"Day ".$id." :\t");
			for( $ip=1 ; $ip<= 8 ; $ip++ )
			{
				if(isset($dmap['teacher']['define'][$i][$id][$ip]['class']))
					echox(0,$dmap['teacher']['define'][$i][$id][$ip]['class']."(".$dmap['teacher']['define'][$i][$id][$ip]['subject'].")\t");
				else
					echox(0,"null\t");
			}
			echox(0,"\n");
		}
		echox(0,"\n");
	
	}

}

function fullfill_bind($action,$subject,$class,$ccode_c)
{
	global $dmap ;
	
	if(! isset( $dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['bind'] ) )
	{
		$dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['bind'] = 0 ;
	}
			
	if($dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['bind'] < $dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['count'] )
	{
		echox(0,"need mount ".$class.":".$subject."\n");
		
		
			
			for( $ip=1 ; $ip<= 8 ; $ip++ )
			{
				for( $id=1 ; $id <= 6 ; $id++ )
				{
					for( $j=0 ; $j < $dmap['teacher']['count'] ; $j++)
					{
						$teacher=$dmap['teacher']['define'][$j]['name'] ;
						
						$teacher_match=0;
						for($j2=0;$j2<$dmap['teacher']['define'][$j]['subjects']['count'];$j2++)
						{
							if(($action=="anyclass" || $dmap['teacher']['define'][$j]['subjects'][$j2]['subject'] == $subject) && $dmap['teacher']['define'][$j]['subjects'][$j2]['class'] == $class)
							{
								$teacher_match=1;
							}
						}
						
						if( ($action=="any" || $teacher_match == 1 ) && $dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['bind'] < $dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['count'] && $dmap['teacher']['define'][$j]['bind'] < $dmap['teacher']['define'][$j]['limit'] )
						{
							bind_period($class,$teacher,$subject,$id,$ip) ;
						
						}
					}
				}
				
			}
				
				
				
	}
}

function fullfill_bind3($action,$subject,$class,$ccode_c)
{
	global $dmap ;
	
	if(! isset( $dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['bind'] ) )
	{
		$dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['bind'] = 0 ;
	}
			
	if($dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['bind'] < $dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['count'] )
	{
		echox(0,"need mount ".$class.":".$subject."\n");
		
		
			
			for( $ip=1 ; $ip<= 8 ; $ip++ )
			{
				for( $id=1 ; $id <= 6 ; $id++ )
				{
					for( $j=0 ; $j < $dmap['teacher']['count'] ; $j++)
					{
						$teacher=$dmap['teacher']['define'][$j]['name'] ;
						
						$teacher_match=0;
						for($j2=0;$j2<$dmap['teacher']['define'][$j]['subjects']['count'];$j2++)
						{
							if(($action=="anyclass" || $dmap['teacher']['define'][$j]['subjects'][$j2]['subject'] == $subject) && $dmap['teacher']['define'][$j]['subjects'][$j2]['class'] == $class)
							{
								$teacher_match=1;
							}
						}
						
						if( ($action=="any" || $teacher_match == 1 ) && $dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['bind'] < $dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['count'] && $dmap['teacher']['define'][$j]['bind'] < $dmap['teacher']['define'][$j]['limit'] )
						{
							bind_period($class,$teacher,$subject,$id,$ip) ;
						
						}
					}
				}
				
			}
				
				
				
	}
}

function stats_bind($action,$subject,$class,$ccode_c)
{
	global $dmap ;
	
	if(! isset( $dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['bind'] ) )
	{
		$dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['bind'] = 0 ;
	}
	$ret['vacant']=$dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['count'] - $dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['bind'] ;
	$ret['count']=$dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['count'] ;
	$ret['bind']=$dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['bind'] ;
	
	return $ret ;
	//if($vacant)
		//echox(1,"class=".$class." ".$subject."\nreq=".$dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['count']." binded=".$dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['bind']." vacant=".$vacant);
	
}


function week_day($x)
{
	$ret="";
	switch($x)
	{
		
		case 0:
			$ret="Sun";
			break;
		
		case 1:
			$ret="Mon";
			break;
		
		case 2:
			$ret="Tue";
			break;
		
		case 3:
			$ret="Wed";
			break;
		
		case 4:
			$ret="Thu";
			break;
		
		case 5:
			$ret="Fri";
			break;
		
		case 6:
			$ret="Sat";
			break;
		
		
	}
	return $ret ;
}

function print_style()
{
	global $dmap ;
	
	echox(1,"<style>
	
	table
	{
		border:1px solid black ;
		border-collapse: collapse ;
		
	}
	th,td
	{
		border:1px solid black ;
		border-collapse: collapse ;
		padding: 5px;
		text-align: center ;
	}
	.td_pp
	{
		width:100px;
	}
	.td_pp2
	{
		width : 100 ;
	}
	tr:nth-child(even)
	{
		background-color: #dddddd ;
	}
	.tt_edit_one_subject
	{
		width:40%;
	}
	.tt_edit_one_teacher
	{
		width:60%;
	}
	
	</style>
	
	<script>
     function printDiv(divId) {
     var printContents = document.getElementById(divId).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
	</script>
	
");



?>


<script>

function save_json_data()
{
	
	var class_info={};
	class_info['total']=<?php echox(1,$dmap['class']['count']) ?>;
<?php
		for($i=0;$i< $dmap['class']['count'] ;$i++)
		{
			echox(1,"class_info[".$i."]=".$dmap['class']['define'][$i]['name'].";\n");
		}
	?>
	
	var obj={};
	obj['total']=0;
	var errored=0;
	for(var i=0; i < class_info['total'] ; i++)
	{
		//obj.total++;
		for(var k=1; k <= 8 ; k++)
		for(var j=1; j <= 6 ; j++)
		{
			
			var ele_subject = document.getElementById("tt_edit_one_subject_"+class_info[i]+"_"+k+"_"+j);
			var ele_teacher = document.getElementById("tt_edit_one_teacher_"+class_info[i]+"_"+k+"_"+j);
			if(ele_subject.value && ele_teacher.value)
			{
				obj[obj['total']]={};
				//obj[obj['total']]['value']=class_info[i]+"_"+ele.value+"_"+k+"_"+j;
				
				obj[obj['total']]['class']=class_info[i];
				obj[obj['total']]['day']=j;
				obj[obj['total']]['period']=k;
				obj[obj['total']]['subject']=ele_subject.value;
				obj[obj['total']]['teacher']=ele_teacher.value;
				
				obj['total']++;
			}
			
			if(ele_subject.value || ele_teacher.value)
			if(ele_subject.value && ele_teacher.value)	
			{
			}
			else
			{
				if( errored==0)
					alert("data err class:"+class_info[i]+" period:"+k+" day:"+j);
				 errored=1;
			}
		}
	}

	var json_string=JSON.stringify(obj);
	
	fetch('save_tt.php', {
	  method: 'POST',
	  headers: {
	    'Content-Type': 'application/json'
	  },
	  body: json_string
	})
	.then(response => response.text()) // or response.json() if the server returns JSON
	.then(data => {
	  console.log('Server response:', data);
	})
	.catch(error => {
	  console.error('Error:', error);
	});


}


function copy_tt_data_single(w_class, w_period)
{
	//console.log(w_class+":"+w_period);
	//return;
	var class_info={};
	class_info['total']=<?php echox(1,$dmap['class']['count']) ?>;
<?php
		for($i=0;$i< $dmap['class']['count'] ;$i++)
		{
			echox(1,"class_info[".$i."]=".$dmap['class']['define'][$i]['name'].";\n");
		}
	?>
	
	var obj={};
	obj['total']=0;
	var errored=0;
	//for(var i=0; i < class_info['total'] ; i++)
	//{
		//obj.total++;
		//for(var k=1; k <= 8 ; k++)
		
		var ele_subject = document.getElementById("tt_edit_one_subject_"+w_class+"_"+w_period+"_"+1);
		var ele_teacher = document.getElementById("tt_edit_one_teacher_"+w_class+"_"+w_period+"_"+1);
		
		var cp_subject="";
		var cp_teacher="";
		if(ele_subject && ele_teacher && ele_subject.value && ele_teacher.value)
		{
			cp_subject=ele_subject.value;
			cp_teacher=ele_teacher.value;
		}
		for(var j=1; j <= 6 ; j++)
		{
			document.getElementById("tt_edit_one_subject_"+w_class+"_"+w_period+"_"+j).value=cp_subject;
			document.getElementById("tt_edit_one_teacher_"+w_class+"_"+w_period+"_"+j).value=cp_teacher;
			
		}
		/*
		if(ele_subject.value && ele_teacher.value)
		for(var j=1; j <= 6 ; j++)
		{
			//document.getElementById("tt_edit_one_subject_"+class_info[w_class]+"_"+w_period+"_"+j)="khdka";
			
		}
		*/
	//}


}
function clean_tt_data_single(w_class, w_period)
{
	//console.log(w_class+":"+w_period);
	//return;
	var class_info={};
	class_info['total']=<?php echox(1,$dmap['class']['count']) ?>;
<?php
		for($i=0;$i< $dmap['class']['count'] ;$i++)
		{
			echox(1,"class_info[".$i."]=".$dmap['class']['define'][$i]['name'].";\n");
		}
	?>
	
	var obj={};
	obj['total']=0;
	var errored=0;
	//for(var i=0; i < class_info['total'] ; i++)
	//{
		//obj.total++;
		//for(var k=1; k <= 8 ; k++)
		
		
		var cp_subject="";
		var cp_teacher="";
		
		for(var j=1; j <= 6 ; j++)
		{
			document.getElementById("tt_edit_one_subject_"+w_class+"_"+w_period+"_"+j).value=cp_subject;
			document.getElementById("tt_edit_one_teacher_"+w_class+"_"+w_period+"_"+j).value=cp_teacher;
			
		}
		/*
		if(ele_subject.value && ele_teacher.value)
		for(var j=1; j <= 6 ; j++)
		{
			//document.getElementById("tt_edit_one_subject_"+class_info[w_class]+"_"+w_period+"_"+j)="khdka";
			
		}
		*/
	//}


}
function clean_tt_data()
{
	//console.log(w_class+":"+w_period);
	//return;
	var class_info={};
	class_info['total']=<?php echox(1,$dmap['class']['count']) ?>;
<?php
		for($i=0;$i< $dmap['class']['count'] ;$i++)
		{
			echox(1,"class_info[".$i."]=".$dmap['class']['define'][$i]['name'].";\n");
		}
	?>
	
	var obj={};
	obj['total']=0;
	var errored=0;
	for(var i=0; i < class_info['total'] ; i++)
	{
		//obj.total++;
		for(var k=1; k <= 8 ; k++)
		for(var j=1; j <= 6 ; j++)
		{
			document.getElementById("tt_edit_one_subject_"+class_info[i]+"_"+k+"_"+j).value="";
			document.getElementById("tt_edit_one_teacher_"+class_info[i]+"_"+k+"_"+j).value="";
			
		}
	}


}

</script>


<?php

}
function print_table_html_class()
{
	global $dmap ;
	
	echox(1,"Printing Class Table\n<br>\n");
	for( $i=0 ; $i < $dmap['class']['count'] ; $i++)
	{
		echox(1,"Class : ".$dmap['class']['define'][$i]['fullname']."\t<table>");
		
		echox(1,"\n<tr><th>Day\Period</th><th>1</th><th>2</th><th>3</th><th>4</th><th>Break</th><th>5</th><th>6</th><th>7</th><th>8</th></tr>\n");
		
		for( $id=1 ; $id <= 6 ; $id++ )
		{
			echox(1,"<tr><th>".week_day($id)."</th>");
			for( $ip=1 ; $ip<= 8 ; $ip++ )
			{
				echox(1,"<td class=\"td_pp\">");
				if(isset($dmap['class']['define'][$i][$id][$ip]['teacher']))
					echox(1,$dmap['class']['define'][$i][$id][$ip]['subject']."(".$dmap['class']['define'][$i][$id][$ip]['teacher'].")");
				else
					echox(1,"null");
				echox(1,"</td>");
				if($ip==4)
				{
					echox(1,"<td>---</td>");
				}
			}
			echox(1,"</tr>\n");
		}
		echox(1,"</table><br>\n");
	
	}

}


function print_table_html_periods_header()
{
	echox(1,"<th>");
	?>
 		<span>Summer</span><br>
  		<span style="display:block; border-top: 2px solid black; width: 4em; margin: 2px auto;"></span>
  		<span>Winter</span>
	<?php
	echox(1,"</th>");
	
	
	echox(1,"<th>12:50 - 13:30<br>1<br>12:55 - 13:30</th>");
	echox(1,"<th>13:30 - 14:05<br>2<br>13:30 - 14:00</th>");
	echox(1,"<th>14:05 - 14:40<br>3<br>14:00 - 14:30</th>");
	echox(1,"<th>14:40 - 15:15<br>4<br>14:30 - 15:00</th>");
	
	echox(1,"<th>15:15 - 15:40<br>Break<br>15:00 - 15:25</th>");
	
	echox(1,"<th>15:40 - 16:15<br>5<br>15:25 - 16:00</th>");
	echox(1,"<th>16:15 - 16:50<br>6<br>16:00 - 16:30</th>");
	echox(1,"<th>16:50 - 17:25<br>7<br>16:30 - 17:00</th>");
	echox(1,"<th>17:25 - 17:00<br>8<br>17:00 - 17:30</th>");
	
	return;
	
	$school_timings_summer== array("12:30","12:50","13:30","14:05","14:40","15:15","15:40","16:15","15:50","17:25","18:00");
	
	for( $ip=1 ; $ip<= 9 ; $ip++ )
	{
			echox(1,"<th>".$school_timings_summer[$ip]."</th>");
			
			/*
				if($ip==4)
				{
					echox(1,"<th>Break</th>");
				}
			*/
	}
}
function print_table_html_teacher()
{
	global $dmap ;
	
	echox(1,"Printing Teacher Table\n<br>\n");
	for( $i=0 ; $i < $dmap['teacher']['count'] ; $i++)
	{
		echox(1,"<div>\n");
		echox(1,"Teacher : ".$dmap['teacher']['define'][$i]['fullname']."\t");
		echox(1,"\n<table><tr><th>Day\Period</th><th>1</th><th>2</th><th>3</th><th>4</th><th>Break</th><th>5</th><th>6</th><th>7</th><th>8</th></tr>\n");
		echox(1,"\n");
		for( $id=1 ; $id <= 6 ; $id++ )
		{
				echox(1,"<tr><th>".week_day($id)."</th>");
			for( $ip=1 ; $ip<= 8 ; $ip++ )
			{
				echox(1,"<td class=\"td_pp\">");
				if(isset($dmap['teacher']['define'][$i][$id][$ip]['class']))
					echox(1,$dmap['teacher']['define'][$i][$id][$ip]['class']."(".$dmap['teacher']['define'][$i][$id][$ip]['subject'].")");
				else
					echox(1,"null");
				echox(1,"</td>");
				if($ip==4)
				{
					echox(1,"<td>---</td>");
				}
			}
			echox(1,"</tr>\n");
		}
		echox(1,"</table>\n<br>\n");
		echox(1,"</div>\n");
	
	}

}


function print_table_html_teacher2()
{
	global $dmap ;
	echox(1,"<div id=\"mg_teacher\" >\n");
	echox(1,"MGGS Housing Board : Printing Teacher Table : <button onclick=printDiv('mg_teacher') >View/Print</button>\n<br>\n");
	
		//echox(1,"Teacher : ".$dmap['teacher']['define'][$i]['fullname']."\t");
		echox(1,"\n<table><tr>");
		print_table_html_periods_header();
		echox(1,"</tr><tr>\n");
		echox(1,"\n");
		
	for( $i=0 ; $i < $dmap['teacher']['count'] ; $i++)
	{
		echox(1,"<tr>");
		echox(1,"<th>".$dmap['teacher']['define'][$i]['fullname']."<br>(".$dmap['teacher']['define'][$i]['name'].")</th>");
		for( $ip=1 ; $ip<= 8 ; $ip++ )
		{
			
			echox(1,"<td class=\"td_pp2\">");
			
			$last_class="";
			$last_subject="";
			$last_match=0;
			
			for( $id=1 ; $id <= 6 ; $id++ )
			{
				
				//echox(1,"<td class=\"td_pp\">");
				if(isset($dmap['teacher']['define'][$i][$id][$ip]['class']))
				{
				if($dmap['teacher']['define'][$i][$id][$ip]['class'] != "null" || $dmap['teacher']['define'][$i][$id][$ip]['subject'] != "null" )
				{
					//echox(1,$dmap['teacher']['define'][$i][$id][$ip]['class']."(".$dmap['teacher']['define'][$i][$id][$ip]['subject'].")<br>");
					$cur_class=$dmap['teacher']['define'][$i][$id][$ip]['class'] ;
					$cur_subject=$dmap['teacher']['define'][$i][$id][$ip]['subject'];
					
					if( $last_class==$cur_class && $last_subject==$cur_subject )
					{
						echox(1,$id);
						
					}
					else
					{
						if($id!=1 && $last_match == 1 )
						{
							echox(1,")<br>");
						}
						echox(1,$dmap['teacher']['define'][$i][$id][$ip]['class']."(".$dmap['teacher']['define'][$i][$id][$ip]['subject'].")(");
						echox(1,$id);
						
						
					}
					
						/*
						if($id==6 )
						{
							echox(1,")");
						}
						*/	
					
					$last_match=1;
					$last_class=$cur_class;
					$last_subject=$cur_subject;
					
				}
				}		
				//else
				//	echox(1,"null<br>");
				//echox(1,"</td>");
				
			}
						if( $last_match == 1 )
						{
							echox(1,")");
						}
				if($ip==4)
				{
					echox(1,"<td>-/-</td>");
				}
			echox(1,"</td>");
			//echox(1,"</tr>\n");
		}
		echox(1,"</tr>\n");
	
	}
		echox(1,"</table>\n<br>\n");
		echox(1,"</div>\n");

}



function print_table_html_class2()
{
	global $dmap ;
	echox(1,"<div id=\"mg_class\" >\n");
	echox(1,"MGGS Housing Board : Printing Class Table : <button onclick=printDiv('mg_class') >View/Print</button>\n<br>\n");
		//echox(1,"Teacher : ".$dmap['teacher']['define'][$i]['fullname']."\t");
		echox(1,"\n<table><tr>");
		print_table_html_periods_header();
		echox(1,"</tr><tr>\n");
		
	for( $i=0 ; $i < $dmap['class']['count'] ; $i++)
	{
		echox(1,"<tr>");
		echox(1,"<th>".$dmap['class']['define'][$i]['fullname']."</th>");
		for( $ip=1 ; $ip<= 8 ; $ip++ )
		{
			
			echox(1,"<td class=\"td_pp2\">");
			
			$last_teacher="";
			$last_subject="";
			$last_match=0;
			
			for( $id=1 ; $id <= 6 ; $id++ )
			{
				
				//echox(1,"<td class=\"td_pp\">");
				if(isset($dmap['class']['define'][$i][$id][$ip]['teacher']))
				{
					//echox(1,$dmap['teacher']['define'][$i][$id][$ip]['class']."(".$dmap['teacher']['define'][$i][$id][$ip]['subject'].")<br>");
					$cur_teacher=$dmap['class']['define'][$i][$id][$ip]['class'] ;
					$cur_subject=$dmap['class']['define'][$i][$id][$ip]['subject'];
					
					if( $last_teacher==$cur_teacher && $last_subject==$cur_subject )
					{
						echox(1,$id);
						
					}
					else
					{
						if($id!=1 && $last_match == 1 )
						{
							echox(1,")<br>");
						}
						echox(1,$dmap['class']['define'][$i][$id][$ip]['subject']."(".$dmap['class']['define'][$i][$id][$ip]['teacher'].")(");
						echox(1,$id);
						
						
					}
					
						/*
						if($id==6 )
						{
							echox(1,")");
						}
						*/	
					
					$last_match=1;
					$last_teacher=$cur_teacher;
					$last_subject=$cur_subject;
					
				}
						
				//else
				//	echox(1,"null<br>");
				//echox(1,"</td>");
				
			}
						if( $last_match == 1 )
						{
							echox(1,")");
						}
				if($ip==4)
				{
					echox(1,"<td>-/-</td>");
				}
			echox(1,"</td>");
			//echox(1,"</tr>\n");
		}
		echox(1,"</tr>\n");
	
	}
		echox(1,"</table>\n<br>\n");
		echox(1,"</div>\n");

}

function print_table_html_class2_edit()
{
	global $dmap ;
	echox(1,"<div id=\"mg_class\" >\n");
	echox(1,"MGGS Housing Board : Printing Class Table : <button onclick='save_json_data()' >Save</button>  <button onclick='clean_tt_data()' >Clean All</button>\n<br>\n");
		//echox(1,"Teacher : ".$dmap['teacher']['define'][$i]['fullname']."\t");
		echox(1,"\n<table><tr>");
		
		print_table_html_periods_header();
		
		echox(1,"</tr><tr>\n");
		
	for( $i=0 ; $i < $dmap['class']['count'] ; $i++)
	{
		echox(1,"<tr>");
		echox(1,"<th>".$dmap['class']['define'][$i]['fullname']."<br><button onclick='save_json_data()' >Save</button></th>");
		for( $ip=1 ; $ip<= 8 ; $ip++ )
		{
			
			echox(1,"<td class=\"td_pp2\">");
			
			echox(1,"<button onclick='copy_tt_data_single(\"".$dmap['class']['define'][$i]['name']."\",\"".$ip."\");' >copy</button>");
			echox(1,"<button onclick='clean_tt_data_single(\"".$dmap['class']['define'][$i]['name']."\",\"".$ip."\");' >clean</button><br>");
			
			$last_teacher="";
			$last_subject="";
			$last_match=0;
			
			for( $id=1 ; $id <= 6 ; $id++ )
			{
				
				//echox(1,"<td class=\"td_pp\">");
				$cur_teacher="";
				$cur_subject="";
				if(isset($dmap['class']['define'][$i][$id][$ip]['teacher']))
				{
					//echox(1,$dmap['teacher']['define'][$i][$id][$ip]['class']."(".$dmap['teacher']['define'][$i][$id][$ip]['subject'].")<br>");
					$cur_teacher=$dmap['class']['define'][$i][$id][$ip]['teacher'] ;
					$cur_subject=$dmap['class']['define'][$i][$id][$ip]['subject'];
					
					
				
						/*
						if($id==6 )
						{
							echox(1,")");
						}
						*/	
					
					$last_match=1;
					$last_teacher=$cur_teacher;
					$last_subject=$cur_subject;
					
				}
				
					echox(1,"<input class=\"tt_edit_one_subject\" id=\"tt_edit_one_subject_".$dmap['class']['define'][$i]['name']."_".$ip."_".$id."\" type=\"text\" value=\"".$cur_subject."\">");
					echox(1,"<input class=\"tt_edit_one_teacher\" id=\"tt_edit_one_teacher_".$dmap['class']['define'][$i]['name']."_".$ip."_".$id."\" type=\"text\" value=\"".$cur_teacher."\">");
						
				//else
				//	echox(1,"null<br>");
				//echox(1,"</td>");
				
			}
			
				if($ip==4)
				{
					echox(1,"<td>-/-</td>");
				}
			echox(1,"</td>");
			//echox(1,"</tr>\n");
		}
		echox(1,"</tr>\n");
	
	}
		echox(1,"</table>\n<br>\n");
		echox(1,"</div>\n");

}



function auto_bind_1($action)
{

	global $dmap ;
	
	echox(0,"autobind_1\n");
	
	for( $i=0 ; $i < $dmap['class']['count'] ; $i++)
	{
		$class=$dmap['class']['define'][$i]['name'] ;
		
		for($i2=0;$i2<$dmap['class']['define'][$i]['subjects']['count'];$i2++)
		{
			$subject=$dmap['class']['define'][$i]['subjects'][$i2]['subject'];
			//$subject_count=$dmap['class']['define'][$i]['subjects'][$i2]['count'];
			
			fullfill_bind($action,$subject,$class,$i);
			
			
			
		}
		
	
	}

}
function stats_binded_classes($action)
{

	global $dmap ;
	
	
	
	echox(1,"<pre>");
	echox(1,"STATS :".$action."\n");
	
	for( $i=0 ; $i < $dmap['class']['count'] ; $i++)
	{
		$class=$dmap['class']['define'][$i]['name'] ;
		
		for($i2=0;$i2<$dmap['class']['define'][$i]['subjects']['count'];$i2++)
		{
			$subject=$dmap['class']['define'][$i]['subjects'][$i2]['subject'];
			//$subject_count=$dmap['class']['define'][$i]['subjects'][$i2]['count'];
			
			$res=stats_bind($action,$subject,$class,$i);
			$need_print=0;
			if( $action == 0 && $res['vacant'] < 0 )
				$need_print=1;
			if( $action == 1 && $res['vacant'] > 0 )
				$need_print=1;
			if( $action == 2 )
				$need_print=1;
			
			if($need_print)
			{
				$sub_space=str_repeat(" ", 8 - strlen($subject));
				$class_space=str_repeat(" ", 4 - strlen($class));
				$count_space=str_repeat(" ", 4 - strlen($res['count']));
				$bind_space=str_repeat(" ", 4 - strlen($res['bind']));
				$vacant_space=str_repeat(" ", 4 - strlen($res['vacant']));
				echox(1,"class=".$class.$class_space."sub=".$subject.$sub_space."count=".$res['count'].$count_space."bind=".$res['bind'].$bind_space."vacant=".$res['vacant']);
				echox(1,"\n");
			}
			
			
		}
		
	
	}
	echox(1,"</pre>\n");

}
function read_cmds($filename)
{
	foreach(file($filename) as $line) 
	{
		read_cmd($line);
	}
}
function read_cmd($line)
{
	//foreach(file($filename) as $line) 
	{
		$fline = str_replace("\n","",$line) ;
		if($fline != "" )
		{
			
			$ftag=explode("\t",$fline);
			$ftags=count($ftag);
			if($ftag[0]!="#")
			{
				echox(0,">> ".$fline."\n");
				
				if($ftag[0]=="new")
				{
					if($ftag[1]=="teacher")
					{
						newteacher($ftag[2],$ftag[3],$ftag[4]);
					}
					if($ftag[1]=="class")
					{
						newclass($ftag[2],$ftag[3]);
					}
				
				}
				if($ftag[0]=="set")
				{
					if($ftag[1]=="bind")
					{
					
						$days=str_split($ftag[5]);
						foreach($days as $day)
						{
							bind_period($ftag[2],$ftag[3],$ftag[4],$day,$ftag[6]);
						}
					
					
					}
					if($ftag[1]=="subject")
					{
						if($ftag[2]=="teacher")
						{
							set_subject_teacher($ftag[3],$ftag[4],$ftag[5]);
						}
						if($ftag[2]=="class")
						{
							set_subject_class($ftag[3],$ftag[4],$ftag[5]);
						}
						
						
					}
				
				}
				if($ftag[0]=="autobind_1")
				{
					auto_bind_1(0);
				}
				if($ftag[0]=="autobind_ai")
				{
					auto_bind_ai(0);
				}
				if($ftag[0]=="autobind_any_ai")
				{
					auto_bind_ai("any");
				}
				if($ftag[0]=="autobind_any_1")
				{
					auto_bind_1("any");
				}
					if($ftag[0]=="autobind_any_byclass_1")
				{
					auto_bind_1("anyclass");
				}
				
			}
			
			
		}
	}
}

function load_json_data($json_data)
{
	$obj=json_decode($json_data);
	
	$total=$obj->total;
	
	for( $i=0 ; $i < $total ; $i++ )
	{
		$class=$obj->{$i}->w_class ;
		$class=$obj->{$i}->w_teacher ;
		$class=$obj->{$i}->w_subject ;
		$class=$obj->{$i}->w_day ;
		$class=$obj->{$i}->w_period ;
		bind_period($class,$teacher,$subject,$day,$period);
	}
	
	
	
}


?>
