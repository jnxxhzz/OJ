<?php
  /**
   * This file is modified
   * by yybird
   * @2016.03.21
  **/
?>

<html>
<head>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Content-Language" content="zh-cn">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>New Problem</title>
</head>
<body leftmargin="30">
<center>
<?php require_once("../include/db_info.inc.php");?>

<?php require_once("admin-header.php");
if (!HAS_PRI("inner_function")){
  echo "You are not allowed to view this page!";
  exit(1);
}
?>
<?php
include_once("../fckeditor/fckeditor.php") ;
?>

<table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse"  width="100%" height="50">
<tr>
<td width="100"></td>
<td>
<p align="center"><font color="#333399" size="4">Welcome To Administrator's Page of Judge Online of ACM ICPC,<?php echo $OJ_NAME?>.</font></td>
<td width="100"></td>
</tr>
</table>
</center>
<hr>
<h1>Add New problem</h1>
<?php require_once("../include/simple_html_dom.php");
  $url=$_POST ['url'];

  if (!$url) $url=$_GET['url'];
  if (strpos($url, "http") === false){
	echo "Please Input like http://plg1.cs.uwaterloo.ca/~acm00/020921/A.html";
	exit(1);
  }   
    
  if (get_magic_quotes_gpc ()) {
	$url = stripslashes ( $url);
  }
  $baseurl=substr($url,0,strrpos($url,"/")+1);
//  echo $baseurl;
  $html = file_get_html($url);
  foreach($html->find('img') as $element)
        $element->src=$baseurl.$element->src;
  $element=$html->find('h2',0);
  $title=$element->plaintext;
  $i=1;
  $sample_output=$sample_input=$descriptionHTML="";
  
  $html=$html->innertext;
  $i=strpos($html,"<h3>");
 // echo $i."-".strlen($html);
  $descriptionHTML=substr($html,0,$i-1);
 // echo $i."-".strlen($descriptionHTML);
  $i=strpos($html,"<pre>",$i);
  $j=strpos($html,"</pre>",$i);
  $sample_input=substr($html,$i+5,$j-$i-5);
  $i=strpos($html,"<pre>",$j);
  $j=strpos($html,"</pre>",$i);
  $sample_output=substr($html,$i+5,$j-$i-5);

?>
<form method=POST action=problem_add.php>
<p align=center><font size=4 color=#333399>Add a Problem</font></p>
<input type=hidden name=problem_id value=New Problem>
<p align=left>Problem Id:&nbsp;&nbsp;New Problem</p>
<p align=left>Title:<input type=text name=title size=71 value="<?php echo $title?>"></p>
<p align=left>Time Limit:<input type=text name=time_limit size=20 value=1>S</p>
<p align=left>Memory Limit:<input type=text name=memory_limit size=20 value=128>MByte</p>
<p align=left>Description:<br><!--<textarea rows=13 name=description cols=80></textarea>-->

<?php
$description = new FCKeditor('description') ;
$description->BasePath = '../fckeditor/' ;
$description->Height = 300 ;
$description->Width=600;

$description->Value ="<p></p>".$descriptionHTML;
$description->Create() ;
?>
</p>

<p align=left>Input:<br><!--<textarea rows=13 name=input cols=80></textarea>-->

<?php
$input = new FCKeditor('input') ;
$input->BasePath = '../fckeditor/' ;
$input->Height = 300 ;
$input->Width=600;

$input->Value = '<p></p>' ;
$input->Create() ;
?>
</p>

</p>
<p align=left>Output:<br><!--<textarea rows=13 name=output cols=80></textarea>-->


<?php
$output = new FCKeditor('output') ;
$output->BasePath = '../fckeditor/' ;
$output->Height = 300 ;
$output->Width=600;

$output->Value = '<p></p>' ;
$output->Create() ;
?>

</p>
<p align=left>Sample Input:<br><textarea rows=13 name=sample_input cols=80><?php echo $sample_input?></textarea></p>
<p align=left>Sample Output:<br><textarea rows=13 name=sample_output cols=80><?php echo $sample_output?></textarea></p>
<p align=left>Test Input:<br><textarea rows=13 name=test_input cols=80></textarea></p>
<p align=left>Test Output:<br><textarea rows=13 name=test_output cols=80></textarea></p>
<p align=left>Hint:<br>
<?php
$output = new FCKeditor('hint') ;
$output->BasePath = '../fckeditor/' ;
$output->Height = 300 ;
$output->Width=600;

$output->Value = '<p></p>' ;
$output->Create() ;
?>
</p>
<p>SpecialJudge: N<input type=radio name=spj value='0' checked>Y<input type=radio name=spj value='1'></p>
<p align=left>Source:<br><textarea name=source rows=1 cols=70></textarea></p>
<p align=left>contest:
	<select  name=contest_id>
<?php $sql="SELECT `contest_id`,`title` FROM `contest` WHERE `start_time`>NOW() order by `contest_id`";
$result=$mysqli->query($sql);
echo "<option value=''>none</option>";
if ($result->num_rows==0){
}else{
	for (;$row=$result->fetch_object();)
		echo "<option value='$row->contest_id'>$row->contest_id $row->title</option>";
}
?>
	</select>
</p>
<div align=center>
<?php require_once("../include/set_post_key.php");?>
<input type=submit value=Submit name=submit>
</div></form>
<p>
<?php require_once("../oj-footer.php");?>
</body></html>

