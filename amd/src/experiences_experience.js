define(['jquery', 'core/ajax', 'core/notification'], function ($, ajax, notification) {

    // TODO: Randfälle behandeln, z.B. letzter Schritt in Liste usw.
    // TODO: clean code
    
    var init = function (experience_id) {

        var promises = ajax.call([{
            methodname: 'block_onboarding_init_helpful',
            args: {experience_id: 'experience_id'}
        }
        ]);
        promises[0].done(function (response) {
            if(response.exists == 1) {
                $('.helpful_btn').css('font-weight: bold');
            }
        }).fail(notification.exception);


        $('.helpful_btn').on('click', function () {
            alert(typeof experience_id);
            var promises = ajax.call([{
                methodname: 'block_onboarding_click_helpful',
                args: {experience_id: 'experience_id'}
            }
            ]);
            promises[0].done(function (response) {
                alert(response.exists);
                if(response.exists == 1) {
                    $('.helpful_btn').css('font-weight: bold');
                }
            }).fail(notification.exception);
        })
    };
    return {
        init: init
    };
})
;