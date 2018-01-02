<?php
	include("simple_html_dom.php");
	include_once("function.php");
	$f = new Functions();
	$f->db();
	if(isset($_GET['id']))
		$id=$_GET['id'];
	else
		$id=1;
	settype($id,'int');
	$rs=@mysql_query("select url from cronjobs_asia_euro  where id = $id") or die(@mysql_error());
	$data = @mysql_fetch_assoc($rs);
	if(empty($data)) exit();
	$content = "";
	$content = $f->curl_get_contents($data['url']);
	$html = str_get_html(str_replace('height="9"></i></font>','height="9"></i></font></td>',$content['content']));
	$array = array();
	foreach ($html->find("#masterdiv") as $element){
			$array[] = $element->outertext;
	}
	$html->clear();
	unset($html);
	
	$html1=$array[0];
	$content = $f->convert_content_html($html1);
	$time = time();
	$content = htmlentities($content,ENT_QUOTES);
	echo $content;
	mysql_query("UPDATE cronjobs_asia_euro SET content = '$content',time = $time WHERE id = $id") or die(mysql_error()); //or die("co loi");;
?>
