<?php

echox(0,"Time Table MGGS Housing Board Bikaner\n");

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
		echox(1,"\n<table><tr><th>Day\Period</th><th>1</th><th>2</th><th>3</th><th>4</th><th>Break</th><th>5</th><th>6</th><th>7</th><th>8</th></tr><tr>\n");
		echox(1,"\n");
		
	for( $i=0 ; $i < $dmap['teacher']['count'] ; $i++)
	{
		echox(1,"<tr>");
		echox(1,"<th>".$dmap['teacher']['define'][$i]['fullname']."</th>");
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
		echox(1,"\n<table><tr><th>Day\Period</th><th>1</th><th>2</th><th>3</th><th>4</th><th>Break</th><th>5</th><th>6</th><th>7</th><th>8</th></tr><tr>\n");
		echox(1,"\n");
		
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
foreach(file("mggs2024.txt") as $line) 
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

print_style();

//print_table_html_class();
if($_GET['type']=="")
{
	print_table_html_class2();
	print_table_html_teacher2();
}
if($_GET['type']=="class")
{
	print_table_html_class2();
}
if($_GET['type']=="teacher")
{
	print_table_html_teacher2();
}



?>
