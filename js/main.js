$(function() {
    $('#get_proxy').on('click', function (e) {
        e.preventDefault();
        jQuery.ajax({
            type: 'POST',
            url: 'app/requests.php',
            data: 'request=getProxy',
            success: function(data) {

            },
            error: function(xhr, str) {
                console.info('Возникла ошибка');
            }
        });
    });
    $('#print_proxy').on('click', function (e) {
        e.preventDefault();
        jQuery.ajax({
            type: 'POST',
            url: 'app/requests.php',
            data: 'request=print_proxy',
            success: function(data) {
                $('.container').append(data);
            },
            error: function(xhr, str) {
                console.info('Возникла ошибка');
            }
        });
    });
    $('#cURL_request').on('click', function (e) {
        e.preventDefault();
        jQuery.ajax({
            type: 'POST',
            url: 'app/requests.php',
            data: {
                'request': 'request',
                'url': 'http://belby.ru',
                'CURLOPT_NOBODY': true,
                'CURLOPT_HEADER': true,
                'proxy': true,
                'cnt': 3
            },
            success: function(data) {
                $('.container').append(data);
            },
            error: function(xhr, str) {
                console.info('Возникла ошибка');
            }
        });
    });
});
