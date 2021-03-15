define(['jquery', 'core/ajax', 'core/notification', 'core/str'], function ($, ajax, notification, str) {

    var not_string = 'edit_category.php?category_id=2';

    var init = function () {
        // var reset_message_promise = str.get_string('button_reset_message_js', 'block_onboarding');
        // $.when(step_promise, achievement_promise, reset_message_promise)
        //     .done(function (step_promise_string, achievement_promise_string, reset_message_promise_string) {
        //         step_string = step_promise_string;
        //         achievement_string = achievement_promise_string;
        //         reset_message_string = reset_message_promise_string;


        $('.confirm-btn').on('click', function () {
            // holt Attribute von delete button
            var context = $(this).attr('onb-data-context');
            var id = $(this).attr('onb-data-id');

            // fragt ab wv Einträge betroffen wären und zeigt confirmation box an
            var promises = ajax.call([{
                methodname: 'block_onboarding_delete_confirmation',
                args: {
                    context: context,
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
                            context: context,
                            id: id
                        }
                    }]);
                    promises[0].done(function (response) {
                        location.reload();
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