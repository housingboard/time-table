<?php

require("lib_tt.php");

echox(0,"Time Table MGGS Housing Board Bikaner\n");

require("read_tt.php");

print_style();

//print_table_html_class();
$req_type="";
if(isset($_GET['type']))
	$req_type=$_GET['type'];

if($req_type=="")
{
	print_table_html_class2();
	print_table_html_teacher2();
}
if($req_type=="class")
{
	print_table_html_class2();
}
if($req_type=="teacher")
{
	print_table_html_teacher2();
}
if($req_type=="stats")
{
	stats_binded_classes(0);
	echox(1,"<br><br>");
	stats_binded_classes(1);
	echox(1,"<br><br>");
	stats_binded_classes(2);
}


?>
