<?php

require_once 'vendor/autoload.php';
require_once 'functions.php';


// ---------------------------------------------------------------------
// --[ cURL Работа с прокси ]-------------------------------------------
// ---------------------------------------------------------------------



file_put_contents( 'tmp/cookie.txt', '' );

$html = cURL_request( 'http://httpbin.org/ip' );

// Получаем свежанькие proxy
// $proxy = get_proxy_hidemyass();
//
// $i = 0;
// foreach ( $proxy as $value) {
//     if ( $i == 2) break;
//     $html = cURL_request( 'http://httpbin.org/ip', array(
//         'proxy'=> true,
//         'CURLOPT_PROXY' => $value['ip'] . ':' . $value['port'],
//         'CURLOPT_PROXYTYPE' => $value['type'],
//     ));
//     print_r($html);
//     $i++;
// }

$html = cURL_request( 'http://httpbin.org/ip', array(
    'proxy' => true,
    
));
xd( $html );

//
