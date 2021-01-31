define(['jquery', 'core/ajax', 'core/notification'], function ($, ajax, notification) {

    // TODO: Randfälle behandeln, z.B. letzter Schritt in Liste usw.
    // TODO: clean code


    var init = function () {


        var promises = ajax.call([{
            methodname: 'block_onboarding_init_step',
            args: {
            }
        }
        ]);
        promises[0].done(function (response) {
            var html1 = '<div class=\"step_description\">' + response.description + '</div>';
            $('.step_description').replaceWith(html1);
            var html2 = '<h5 class=\"step_title\"><b>Step #' + response.position + ': ' + response.name + '</b></h5>';
            $('.step_title').replaceWith(html2);
        }).fail(notification.exception);


    };


    var next_step = function () {

        $('.done_btn').on('click', function () {
            var promises = ajax.call([{
                methodname: 'block_onboarding_next_step',
                args: {
                }
            }
            ]);
            promises[0].done(function (response) {
                var html1 = '<div class=\"step_description\">' + response.description + '</div>';
                $('.step_description').replaceWith(html1);
                var html2 = '<h5 class=\"step_title\"><b>Step #' + response.position + ': ' + response.name + '</b></h5>';
                $('.step_title').replaceWith(html2);
            }).fail(notification.exception);
        })
    };

    var skip_step = function () {

        $('.skip_btn').on('click', function () {
            var promises = ajax.call([{
                methodname: 'block_onboarding_skip_step',
                args: {
                }
            }
            ]);
            promises[0].done(function (response) {
                var html1 = '<div class=\"step_description\">' + response.description + '</div>';
                $('.step_description').replaceWith(html1);
                var html2 = '<h5 class=\"step_title\"><b>Step #' + response.position + ': ' + response.name + '</b></h5>';
                $('.step_title').replaceWith(html2);
            }).fail(notification.exception);
        })
    };

    var back_step = function () {

        $('.back_btn').on('click', function () {
            var promises = ajax.call([{
                methodname: 'block_onboarding_back_step',
                args: {
                }
            }
            ]);
            promises[0].done(function (response) {
                var html1 = '<div class=\"step_description\">' + response.description + '</div>';
                $('.step_description').replaceWith(html1);
                var html2 = '<h5 class=\"step_title\"><b>Step #' + response.position + ': ' + response.name + '</b></h5>';
                $('.step_title').replaceWith(html2);
            }).fail(notification.exception);
        })
    };


    return {
        init: init,
        next_step: next_step(),
        skip_step: skip_step(),
        back_step: back_step(),
    };
});