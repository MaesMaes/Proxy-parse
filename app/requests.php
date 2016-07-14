<?php

    // Подключаем файл, в котором будем объявлять пользовательские функции
    require_once( '../functions.php' );

    /**
     *  Данные передаются методом POST
     *  Если массив POST, пустой, что-то идет не так
     *  Более того, переменная $_POST[ 'request' ] пустая, или её не существует,
     *  значит тоже что-то не так
     */
    if ( empty( $_POST ) ) {

    	die( "Массив \$_POST пустой" );

    }
    elseif ( empty( $_POST[ 'request' ] ) ) {

    	die( "Не передан запрос" );

    }
    else {

    	// Очищаем строку с типом запроса от лишних пробелов и защищаемся от возможных SQL-инъекций
    	$request = htmlspecialchars( trim( $_POST[ 'request' ] ) );

    	// Убираем тип запроса из массива $_POST
    	unset( $_POST[ 'request' ] );

    }

    // В переменной $response будем возвращать данные AJAX-запросу
    $response = NULL;

    switch ( $request ) {
    	case "getProxy":

    		$response = get_proxy_hidemyass( $_POST );

    	break;

    	case "print_proxy":

    		$response = print_proxy( $_POST );

    	break;

    	case "request":

    		$response = request( $_POST );

    	break;
    }
    echo $response;
