<?php
// 从请求 URI 中提取目标 URL
$url = str_replace('/referer/file.php/', '', $_SERVER["REQUEST_URI"]);

// 还原 URL 中被转义的空格
$url = str_replace(" ", "%20", $url);

// 使用 parse_url 解析 URL，支持 http 和 https
$urlInfo = parse_url($url);
if (!$urlInfo || !isset($urlInfo['host'])) {
    die("<p>无效的 URL: $url</p>");
}

$scheme = isset($urlInfo['scheme']) ? strtolower($urlInfo['scheme']) : 'http';
$domain = $urlInfo['host'];
$port   = isset($urlInfo['port']) ? $urlInfo['port'] : ($scheme === 'https' ? 443 : 80);
$path   = isset($urlInfo['path']) ? $urlInfo['path'] : '/';
if (isset($urlInfo['query'])) {
    $path .= '?' . $urlInfo['query'];
}

// 连接目标主机（HTTPS 使用 ssl:// 前缀）
if ($scheme === 'https') {
    $content = @stream_socket_client("ssl://$domain:$port", $errno, $errstr, 12);
} else {
    $content = @fsockopen($domain, $port, $errno, $errstr, 12);
}

if (!$content) {
    die("<p>对不起，无法连接上 $domain ($errstr) 。</p>");
}

fputs($content, "GET $path HTTP/1.0\r\n");
fputs($content, "Host: $domain\r\n");
fputs($content, "Referer: $domain\r\n");
fputs($content, "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36\r\n\r\n");

$tp = '';
while (!feof($content)) {
    $tp .= fgets($content, 128);
    if (strstr($tp, "200 OK")) {
        // 提取文件名
        $filename = basename(parse_url($url, PHP_URL_PATH)) ?: 'download';
        $header = @get_headers($url, 1);
        if ($header !== false) {
            $contentLength = isset($header['Content-Length']) ? $header['Content-Length'] : null;
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            if ($contentLength) {
                header('Content-Length: ' . $contentLength);
            }
            readfile($url);
            die();
        }
    }
}

// 302 转向，防盗链系统先验证 referer，通过后返回真实地址
$arr1 = explode("Location: ", $tp);
if (isset($arr1[1])) {
    $arr2 = explode("\n", $arr1[1]);
    header('Content-Type: application/force-download');
    header("Location: " . trim($arr2[0]));
    die();
}

// 未找到 Location 头，可能是请求失败
die("<p>无法获取目标资源。</p>");
