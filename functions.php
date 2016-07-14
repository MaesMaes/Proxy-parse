<?php

// Подключаем автозагрузчик composer
require_once 'vendor/autoload.php';
require_once 'simple_html_dom.php';

function cURL_request( $url = 'http://httpbin.org/ip', $options = array() )
{

    // Значения аргументов по умолчанию
    $default_options = array(
        'postdata'   => null,
        'cookiefile' => realpath( 'tmp/cookie.txt' ),
        'proxy'      => false,
        'CURLOPT_PROXY' => '118.161.73.188:8888',
        'CURLOPT_PROXYTYPE' => 'CURLPROXY_HTTP',
        'CURLOPT_USERAGENT' => 'Mozilla',
        'CURLOPT_NOBODY' => true,
        'CURLOPT_HEADER' => true,
    );

    // Подставляем нужного юзерагента
    switch ( $default_options['CURLOPT_USERAGENT'] ) {
        case 'Mozilla':
            $default_options['CURLOPT_USERAGENT'] = 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:47.0) Gecko/20100101 Firefox/47.0';
            break;

        default:
            $default_options['CURLOPT_USERAGENT'] = 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:47.0) Gecko/20100101 Firefox/47.0';
            break;
    }

    // Рекурсивное слияние двух или более массивов
    $options = array_merge_recursive($default_options, $options);

    // Получаем дескриптор потока ресурса (url handle)
    $ch = curl_init( $url );

    // Данные полученные в результате запроса будут сохраняться в переменные
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    // Следуем за редиректом если он есть
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    // Притворяемся браузером
    curl_setopt( $ch, CURLOPT_USERAGENT, $default_options['CURLOPT_USERAGENT'] );
    // Не получать тело
    curl_setopt( $ch, CURLOPT_NOBODY, $default_options['CURLOPT_NOBODY'] );
    // Получить заголовки (HEADER)
    curl_setopt( $ch, CURLOPT_HEADER, $default_options['CURLOPT_HEADER'] );

    // Записываем cookie в файл
    curl_setopt( $ch, CURLOPT_COOKIEJAR, $options['cookiefile'] );
    // Читаем из файла и передаем cookie обратно
    curl_setopt( $ch, CURLOPT_COOKIEFILE, $options['cookiefile'] );

    // Отключаем проверки для загрузки страниц по HTTPS без боли
    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
    // Остановки cURL от проверки сертификата узла сети.
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );

    if ( $options['proxy'] ) {

        // Будем использовать прокси
        curl_setopt( $ch, CURLOPT_PROXY, $options['CURLOPT_PROXY'] );
        // Указываем тип прокси: CURLPROXY_HTTP, CURLPROXY_SOCKS4
        curl_setopt( $ch, CURLOPT_PROXYTYPE, $options['CURLOPT_PROXYTYPE'] );

        /**
         * Устанавливаем timout-ы. Указываем время после которого
         * будет считаться что запрос не дошел.
         */
        // Timeout на загрузку данных, сек.
        curl_setopt( $ch, CURLOPT_TIMEOUT, 9 );
        // Время в течение которого соединение должно быть установлено, сек.
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 6 );

    }

    if ( $options['postdata'] ) {
        /**
         * Прикрепляем POST поля
         * Принимает либо массив ключ-значение или строку GET
         */
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $postdata );
    }

    $html = curl_exec( $ch );
    curl_close( $ch );

    return $html;
}

/**
 * Получение массива proxy
 */
function get_proxy_hidemyass()
{
    // Читает содержимое файла в строку
    $html = file_get_contents( 'http://proxylist.hidemyass.com/search-1305531#listable' );

    // Создаем объект pq из строки
    $proxylist = phpQuery::newDocument( $html );

    // Получаем таблицу proxy
    $proxytable = pq( 'table#listable tbody tr' );

    $proxy = array();

    foreach ($proxytable as $tr) {

        // Переводим DOMElement Object в phpQueryObject Object
        $tr = pq( $tr );

        $ip = $tr->find('td:nth-child(2)');
        // $ip = str_get_html( $ip )->plaintext;

        // Ищем классы прячущие мусор
        $none_display = array();
        $style = $ip->find('style');
        $style = explode('.', $style);
        foreach ($style as $value) {
            $chunk = explode( '{', $value );
            $class = $chunk[0];
            if ( strpos( $chunk[1], 'none' ) ) {
                array_push($none_display, $class);
            }
        }

        // Удаляем мусор
        foreach ($ip as $el) {
            $el = pq( $el );
            $el->find('*[style="display:none"]')->remove();
            foreach ($none_display as $class)
                $el->find("*[class=$class]")->remove();
        }

        $ip = str_get_html( $ip )->plaintext;
        $ip = str_replace( ' ', '', $ip );

        $port = str_get_html( $tr->find('td:nth-child(3)') )->plaintext;
        $port = str_replace( ' ', '', $port );

        $type = str_get_html( $tr->find('td:nth-child(7)') )->plaintext;
        $type = str_replace( ' ', '', $type );

        switch ( $type ) {
            case 'HTTP':
                $type = 'CURLPROXY_HTTP';
                break;
            case 'HTTPS':
                $type = 'CURLPROXY_HTTPS';
                break;
            case 'socks4/5':
                $type = 'CURLPROXY_SOCKS4';
                break;

            default:
                $type = 'CURLPROXY_HTTP';
                break;
        }

        array_push($proxy, array(
            'ip'   => $ip,
            'port' => $port,
            'type' => $type
        ));

    }

    // Очищаем помять от документа $proxylist
    phpQuery::unloadDocuments( $proxylist );

    $proxy = json_encode( $proxy );

    $proxylist_file = 'tmp/proxylist.json';
    file_put_contents( $proxylist_file, $proxy );

    return true;
}

function print_proxy()
{
    $current = file_get_contents('tmp/proxylist.json');
    $current = json_decode( $current );
    if ( !$current )
        return 'Список прокси не получен!';

    $table = "<table><tr><td>Proxy</td><td>Port</td><td>Type</td></tr>";

    foreach ( $current as $value )
        $table .= "<tr><td>{$value->ip}</td><td>{$value->port}</td><td>{$value->type}</td></tr>";

    $table .= '</table>';

    return $table;
}

function request( $args )
{
    $current = file_get_contents('tmp/proxylist.json');
    $current = json_decode( $current );
    if ( !$current )
        return 'Список прокси не получен!';

    $i = 0;
    foreach ( $current as $value ) {
        if ( $i == $args['cnt'] ) break;
        $args['proxy'] = true;
        $args['CURLOPT_PROXY'] = $value->ip . ':' . $value->port;
        $args['CURLOPT_PROXYTYPE'] = $value->type;
        $header = cURL_request( $args['url'], $args );
        $headers .= $header . '<br/>';
        $i++;
        break;
    }

    return $headers;
}







//
