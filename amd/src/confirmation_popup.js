define(['jquery', 'core/ajax', 'core/notification', 'core/str'], function ($, ajax, notification, str) {

    var init = function () {
        $('.confirm-btn').on('click', function () {
            // holt Attribute von delete button
            var type = $(this).attr('onb-data-context');
            var id = $(this).attr('onb-data-id');

            // fragt ab wv Einträge betroffen wären und zeigt confirmation box an
            var promises = ajax.call([{
                methodname: 'block_onboarding_delete_confirmation',
                args: {
                    type: type,
                    id: id
                }
            }]);
            promises[0].done(function (response) {
                // confirmation popup
                var delete_confirmation = confirm(response.text);
                // wenn bestätigt, rufe Lösch-Funktion auf
                if (delete_confirmation == true) {
                    var promises = ajax.call([{
                        methodname: 'block_onboarding_delete_entry',
                        args: {
                            type: type,
                            id: id
                        }
                    }]);
                    promises[0].done(function (response) {
                        if (response.redirect == "reload"){
                            location.reload();
                        } else {
                            window.location.href = response.redirect;
                        }
                        return false;
                    }).fail(notification.exception);
                }
            }).fail(notification.exception);
        })
    }

    return {
        init: init
    };
});