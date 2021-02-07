define(['jquery', 'core/ajax', 'core/notification'], function ($, ajax, notification) {

    // TODO: Randfälle behandeln, z.B. letzter Schritt in Liste usw.
    // TODO: clean code
    
    var init = function () {


        var promises = ajax.call([{
            methodname: 'block_onboarding_init_step',
            args: {}
        }
        ]);
        promises[0].done(function (response) {
            $('.step_description').text(response.description);
            $('.step_title').text('Step #' + response.position + ': ' + response.name);
            $('.progress_bar_value').text(response.progress + '%');
            $('.progress_bar_fill').css('width', (response.progress + '%'));
        }).fail(notification.exception);
    };


    $('.done_btn').on('click', function () {
        var promises = ajax.call([{
            methodname: 'block_onboarding_next_step',
            args: {}
        }
        ]);
        promises[0].done(function (response) {
            $('.step_description').text(response.description);
            $('.step_title').text('Step #' + response.position + ': ' + response.name);
            $('.progress_bar_value').text(response.progress + '%');
            $('.progress_bar_fill').css('width', (response.progress + '%'));
        }).fail(notification.exception);
    })


    $('.skip_btn').on('click', function () {
        var promises = ajax.call([{
            methodname: 'block_onboarding_skip_step',
            args: {}
        }
        ]);
        promises[0].done(function (response) {
            $('.step_description').text(response.description);
            $('.step_title').text('Step #' + response.position + ': ' + response.name);
        }).fail(notification.exception);
    })


    $('.back_btn').on('click', function () {
        var promises = ajax.call([{
            methodname: 'block_onboarding_back_step',
            args: {}
        }
        ]);
        promises[0].done(function (response) {
            $('.step_description').text(response.description);
            $('.step_title').text('Step #' + response.position + ': ' + response.name);
        }).fail(notification.exception);
    })


    return {
        init: init
    };
})
;