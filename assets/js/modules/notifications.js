/*
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */
notifications = {
    refresh: function (user) {
    },
    load: function (url) {
        $.ajax({
            url: url,
            data: {},
            type: 'POST',
            dataType: 'json',
            async: true,

            success: function (data, status) {
                notifications.visualize(data);
            },
            error: function (xhr, textStatus, errorThrown) {
                alert('Ajax request failed.');
            }
        });
    },
    visualize: function (notifications) {
        $('#notifications-dropdown').html('');

        for (i = 0; i < notifications.length; i++) {
            notification = notifications[i];
            let e = '<a class="dropdown-item notification" href="#" data-id="' + notification['id'] + '"><i class="fas fa-' + notification['icon'] + ' fa-fw"></i><span> ' + notification['message'] + '</span></a>';
            $('#notifications-dropdown').append(e);
        }
    }
};
