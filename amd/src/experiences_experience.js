define(['jquery', 'core/ajax', 'core/notification'], function ($, ajax, notification) {

    // TODO: clean code
    
    var init = function (experience_id) {

        var promises = ajax.call([{
            methodname: 'block_onboarding_init_helpful',
            args: {experience_id: experience_id}
        }
        ]);
        promises[0].done(function (response) {
            if(response.exists === 1) {
                $('.block-experiences-experience-popularity').text(response.popularity);
                $('.block-experiences-experience-helpful_btn').css('background-color' , '#20ab63');
            } else {
                $('.block-experiences-experience-popularity').text(response.popularity);
                $('.block-experiences-experience-helpful_btn').css('background-color' , '#c8d1db');
            }
        }).fail(notification.exception);


        $('.block-experiences-experience-helpful_btn').on('click', function () {
            var promises = ajax.call([{
                methodname: 'block_onboarding_click_helpful',
                args: {experience_id: experience_id}
            }
            ]);
            promises[0].done(function (response) {
                if(response.exists === 1) {
                    $('.block-experiences-experience-popularity').text(response.popularity);
                    $('.block-experiences-experience-helpful_btn').css('background-color' , '#20ab63');
                } else {
                    $('.block-experiences-experience-popularity').text(response.popularity);
                    $('.block-experiences-experience-helpful_btn').css('background-color' , '#c8d1db');
                }
            }).fail(notification.exception);
        })
    };
    return {
        init: init
    };
})
;