<?php

require("lib_tt.php");

echox(0,"Time Table MGGS Housing Board Bikaner\n");

read_cmds("mggs.txt");
read_cmds("saved_tt.txt");

print_style();

//print_table_html_class();
if($_GET['type']=="")
{
	print_table_html_class2_edit();
	//print_table_html_class2();
	//print_table_html_teacher2();
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
