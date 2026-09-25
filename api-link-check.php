<?php
/**
 * 友情链接状态检测 API
 * POST: urls[]=url1&urls[]=url2...
 * 返回: { "https://example.com": { "ok": true, "status": 200, "time": 320 }, ... }
 */
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'POST only']);
    exit;
}

$urls = isset($_POST['urls']) ? $_POST['urls'] : array();
if (!is_array($urls) || empty($urls)) {
    echo json_encode(['error' => 'no urls']);
    exit;
}

// 限制每次最多 50 个
$urls = array_slice(array_values(array_unique(array_filter($urls, function($u) {
    return filter_var($u, FILTER_VALIDATE_URL);
}))), 0, 50);

$results = array();

foreach ($urls as $url) {
    $results[$url] = _checkUrl($url);
}

echo json_encode($results, JSON_UNESCAPED_UNICODE);
exit;

function _checkUrl($url, $timeout = 10) {
    $start = microtime(true);

    // 尝试 2 次
    for ($attempt = 0; $attempt < 2; $attempt++) {
        $result = _doRequest($url, $timeout);
        if ($result['ok']) {
            $result['time'] = round((microtime(true) - $start) * 1000);
            return $result;
        }
        // 第一次失败，等 1 秒后重试
        if ($attempt === 0) sleep(1);
    }

    $result['time'] = round((microtime(true) - $start) * 1000);
    return $result;
}

function _doRequest($url, $timeout) {
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 3,
            CURLOPT_TIMEOUT        => $timeout,
            CURLOPT_CONNECTTIMEOUT => 8,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_NOBODY         => true,       // HEAD 请求，省带宽
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (compatible; LinkChecker/1.0)',
            CURLOPT_HTTPHEADER     => array('Accept: text/html,*/*'),
        ));
        curl_exec($ch);
        $errno = curl_errno($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        // 没有 curl 错误且 HTTP 状态码合理 → 在线
        if ($errno === 0 && $httpCode > 0 && $httpCode < 500) {
            return array('ok' => true, 'status' => $httpCode, 'error' => '');
        }
        return array('ok' => false, 'status' => $httpCode, 'error' => $err ?: 'curl_errno:'.$errno);
    }

    // fallback: file_get_contents
    if (ini_get('allow_url_fopen')) {
        $ctx = stream_context_create(array(
            'http' => array(
                'method'        => 'HEAD',
                'timeout'       => $timeout,
                'user_agent'    => 'Mozilla/5.0 (compatible; LinkChecker/1.0)',
                'ignore_errors' => true,
            ),
            'ssl' => array(
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ),
        ));
        $data = @file_get_contents($url, false, $ctx);
        $code = 0;
        if (isset($http_response_header)) {
            foreach ($http_response_header as $h) {
                if (preg_match('#^HTTP/\S+\s+(\d{3})#', $h, $m)) { $code = (int)$m[1]; }
            }
        }
        if ($code > 0 && $code < 500) {
            return array('ok' => true, 'status' => $code, 'error' => '');
        }
        return array('ok' => false, 'status' => $code, 'error' => 'http_status_'.$code);
    }

    return array('ok' => false, 'status' => 0, 'error' => 'no_http_lib');
}
