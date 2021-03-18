define(['jquery', 'core/ajax', 'core/notification'], function ($, ajax, notification) {
    
    var init = function (experienceid) {

        var promises = ajax.call([{
            methodname: 'block_onboarding_init_helpful',
            args: {experienceid: experienceid}
        }
        ]);
        promises[0].done(function (response) {
            if(response.alreadyhelpful === 1) {
                $('.experiences-experience-helpful-btn').css({'background-color': '#32cd32', 'color': '#ffffff'});
            } else {
                $('.experiences-experience-helpful-btn').css({'background': 'rgba(128, 128, 128, 0.8)', 'color': '#ffffff'});
            }
        }).fail(notification.exception);


        $('.experiences-experience-helpful-btn').on('click', function () {
            var promises = ajax.call([{
                methodname: 'block_onboarding_click_helpful',
                args: {experienceid: experienceid}
            }
            ]);
            promises[0].done(function (response) {
                if(response.alreadyhelpful === 1) {
                    $('.experiences-experience-helpful-btn').css({'background-color': '#32cd32', 'color': '#ffffff'});
                } else {
                    $('.experiences-experience-helpful-btn').css({'background': 'rgba(128, 128, 128, 0.8)', 'color': '#ffffff'});
                }
            }).fail(notification.exception);
        })
    };
    return {
        init: init
    };
})
;