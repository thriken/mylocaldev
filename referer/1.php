<?php
$host   = 'music.local';
$target = '/referer/2.php';
$referer = '//www.jb51.net';

// 伪造 HTTP_REFERER 地址
$fp = @fsockopen($host, 80, $errno, $errstr, 30);
if (!$fp) {
    echo "$errstr ($errno)<br />\n";
} else {
    $out  = "GET $target HTTP/1.1\r\n";
    $out .= "Host: $host\r\n";
    $out .= "Referer: $referer\r\n";
    $out .= "Connection: Close\r\n\r\n";
    fwrite($fp, $out);
    while (!feof($fp)) {
        echo fgets($fp, 1024);
    }
    fclose($fp);
}
