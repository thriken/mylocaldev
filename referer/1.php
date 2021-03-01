<?php
$host = 'localhost';
$target = '/referer/2.php';
$referer = '//www.jb51.net';
//伪造HTTP_REFERER地址
$fp = fsockopen($host, 80, $errno, $errstr, 30);
if (!$fp) {
	echo "$errstr($errno)<br />\n";
} else {
	$out = "
GET $target HTTP/1.1
Host: $host
Referer: $referer
Connection: Close\r\n\r\n";
	fwrite($fp, $out);
	while (!feof($fp)) {
		echo fgets($fp, 1024);
	}
	fclose($fp);
}
?>