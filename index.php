<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>gf_parse</title>
    <link rel="stylesheet" href="css/style.min.css">
</head>
<body>
    <div class="container">
        <a href="" id="get_proxy">Получить прокси</a>
        <a href="" id="print_proxy">Распечатать прокси</a>
        <a href="" id="cURL_request">Запрос</a>
    </div>
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>

<?php

require_once 'vendor/autoload.php';
require_once 'functions.php';


// ---------------------------------------------------------------------
// --[ cURL Работа с прокси ]-------------------------------------------
// ---------------------------------------------------------------------


// $html = cURL_request( 'http://httpbin.org/ip', array(
//     'proxy' => true,
//     // 'CURLOPT_PROXY' => '118.161.73.188:8888',
//     // 'CURLOPT_PROXYTYPE' => 'CURLPROXY_HTTP',
// ));

?>
