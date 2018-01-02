<?php
class Functions{
	function curl_get_contents($url)
	{	
		 $options = array(
			CURLOPT_RETURNTRANSFER => true,     // return web page
			CURLOPT_HEADER         => false,    // don't return headers
			CURLOPT_FOLLOWLOCATION => false,     // follow redirects
			CURLOPT_ENCODING       => "",       // handle all encodings
			CURLOPT_USERAGENT      => "spider", // who am i
			CURLOPT_AUTOREFERER    => true,     // set referer on redirect
			CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
			CURLOPT_TIMEOUT        => 120,      // timeout on response
			CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
			CURLOPT_SSL_VERIFYPEER => false,     // Disabled SSL Cert checks
			CURLOPT_COOKIEFILE	   => "cookies.txt",
			CURLOPT_COOKIEJAR		=> "cookies.txt",
			CURLOPT_USERAGENT		=>	 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0'
		);
		echo $url;
		$ch = curl_init( $url );
		curl_setopt_array( $ch, $options );
		$content = curl_exec( $ch );
		$err     = curl_errno( $ch );
		$errmsg  = curl_error( $ch );
		$header  = curl_getinfo( $ch );
		curl_close( $ch );
	
		$header['errno']   = $err;
		$header['errmsg']  = $errmsg;
		$header['content'] = $content;
		return $header;
	}
	function convert_content_html($html1){
		while(preg_match("/> /i",$html1)){
			$html1 = preg_replace("/> /",">",$html1);
		}
		$html1 = str_replace('</td></td>','</td>',$html1);
		$html1= preg_replace("/<font[^>]+\>/i","",$html1);
		$html1= preg_replace("/<\/font\>/i","",$html1);
		
		$content='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Untitled Document</title></head><body>';
		$content.=$html1;
		$content.='</body></html>';
		$content = str_replace("&nbsp;"," ",$content);
		$content = str_replace("(N)"," ",$content);
		return $content;
	}
	function db(){
		$connect = @mysql_pconnect('localhost','root','vertrigo') or die("can not connect to server. may be server busy");
		@mysql_select_db('ciapp', $connect)  or die("can not select database now");
		@mysql_query("set names 'utf8'");	
	}
	function get_link2($url) {
		global $array;
		$array['home']=array();
		$array['away']=array();
		$array['time']=array();
		$array['score']=array();
		//$array['url']=array();
		$html = file_get_html($url);
		$i=0;
		foreach ($html->find(".league-table .fd") as $time){
				$array['time'][$i]=trim($time->plaintext);
				$i++;
		}
		
		$i=0;
		foreach ($html->find(".league-table .fh") as $home){
				$array['home'][$i]=trim($home->plaintext);
				$i++;
		}

		$i=0;
		foreach ($html->find(".league-table .fa") as $away){
				$array['away'][$i]=trim($away->plaintext);
				$i++;
		}

		$i=0;
		foreach ($html->find(".league-table .fs") as $score){
				$array['score'][$i]=trim($score->plaintext);
				$i++;
		}
		$html->clear();
		unset($html);
	}
	function get_link($url) {
		global $array;
		$array['home']=array();
		$array['away']=array();
		$array['time']=array();
		$array['score']=array();
		//$array['url']=array();
		$html = file_get_html($url);
		$i=0;
		foreach ($html->find("div.m_outer .m_date") as $time){
				$array['time'][$i]=trim($time->plaintext);
				$i++;
		}
		
		$i=0;
		foreach ($html->find("div.m_outer .m_home span") as $home){
				$array['home'][$i]=trim($home->plaintext);
				$i++;
		}
		
		$i=0;
		foreach ($html->find("div.m_outer .m_away span") as $away){
				$array['away'][$i]=trim($away->plaintext);
				$i++;
		}

		$i=0;
		//thay đổi cho euro
		foreach ($html->find("div.m_outer .m_score") as $score){
				$array['score'][$i]=str_replace('&#160;','',trim($score->plaintext));
				$i++;
		}
		//foreach ($html->find("div.content .scorelink") as $score){
//				$array['score'][$i]=trim($score->plaintext);
//				$i++;
//		}
		$html->clear();
		unset($html);
	}
}