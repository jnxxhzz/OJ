<?php
////////////////////////////Common head
	$cache_time=90;
	$OJ_CACHE_SHARE=false;
	require_once('./include/cache_start.php');
	require_once('./include/db_info.inc.php');
	require_once('./include/setlang.php');
	$view_title= "Welcome To Online Judge";

/////////////////////////Template
require("template/".$OJ_TEMPLATE."/faqs.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>
