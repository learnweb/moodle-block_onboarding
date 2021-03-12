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
                $('.experiences-experience-helpful-btn').css({'background-color': '#32cd32', 'color': '#ffffff'});
            } else {
                $('.block-experiences-experience-popularity').text(response.popularity);
                $('.experiences-experience-helpful-btn').css({'background': 'rgba(128, 128, 128, 0.8)', 'color': '#ffffff'});
            }
        }).fail(notification.exception);


        $('.experiences-experience-helpful-btn').on('click', function () {
            var promises = ajax.call([{
                methodname: 'block_onboarding_click_helpful',
                args: {experience_id: experience_id}
            }
            ]);
            promises[0].done(function (response) {
                if(response.exists === 1) {
                    $('.block-experiences-experience-popularity').text(response.popularity);
                    $('.experiences-experience-helpful-btn').css({'background-color': '#32cd32', 'color': '#ffffff'});
                } else {
                    $('.block-experiences-experience-popularity').text(response.popularity);
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