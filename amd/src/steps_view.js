define(['jquery', 'core/ajax', 'core/notification'], function($, ajax, notification) {

    // TODO: Language Strings übergeben anstatt Step etc.!
    // TODO: clean code

    var init = function() {


        var promises = ajax.call([{
            methodname: 'block_onboarding_init_step',
            args: {}
        }]);
        promises[0].done(function(response) {
            if(response.completed == 1){
                $('.step_completed').css('visibility', 'visible');
            } else{
                $('.step_completed').css('visibility', 'hidden');
            }
            if(response.achievement == 1){
                $('.step_container').css('background-color', '#009933');
                $('.step_title').text('Achievement! ' + response.name);
            } else {
                $('.step_container').css('background-color', '');
                $('.step_title').text('Step ' + response.position + ': ' + response.name);
            }
            $('.step_description').text(response.description);
            $('.progress_bar_value').text(response.progress + '%');
            $('.progress_bar_fill').css('width', (response.progress + '%'));
        }).fail(notification.exception);
    };


    $('.next_btn').on('click', function() {
        var promises = ajax.call([{
            methodname: 'block_onboarding_next_step',
            args: {}
        }]);
        promises[0].done(function(response) {
            if(response.completed == 1){
                $('.step_completed').css('visibility', 'visible');
            } else{
                $('.step_completed').css('visibility', 'hidden');
            }
            if(response.achievement == 1){
                $('.step_container').css('background-color', '#009933');
                $('.step_title').text('Achievement! ' + response.name);
            } else {
                $('.step_container').css('background-color', '');
                $('.step_title').text('Step ' + response.position + ': ' + response.name);
            }
            $('.step_description').text(response.description);
            $('.progress_bar_value').text(response.progress + '%');
            $('.progress_bar_fill').css('width', (response.progress + '%'));
        }).fail(notification.exception);
    })


    $('.skip_btn').on('click', function() {
        var promises = ajax.call([{
            methodname: 'block_onboarding_skip_step',
            args: {}
        }]);
        promises[0].done(function(response) {
            if(response.completed == 1){
                $('.step_completed').css('visibility', 'visible');
            } else{
                $('.step_completed').css('visibility', 'hidden');
            }
            if(response.achievement == 1){
                $('.step_container').css('background-color', '#009933');
                $('.step_title').text('Achievement! ' + response.name);
            } else {
                $('.step_container').css('background-color', '');
                $('.step_title').text('Step ' + response.position + ': ' + response.name);
            }
            $('.step_description').text(response.description);
        }).fail(notification.exception);
    })


    $('.back_btn').on('click', function() {
        var promises = ajax.call([{
            methodname: 'block_onboarding_back_step',
            args: {}
        }]);
        promises[0].done(function(response) {
            if(response.completed == 1){
                $('.step_completed').css('visibility', 'visible');
            } else{
                $('.step_completed').css('visibility', 'hidden');
            }
            if(response.achievement == 1){
                $('.step_container').css('background-color', '#009933');
                $('.step_title').text('Achievement! ' + response.name);
            } else {
                $('.step_container').css('background-color', '');
                $('.step_title').text('Step ' + response.position + ': ' + response.name);
            }
            $('.step_description').text(response.description);
        }).fail(notification.exception);
    })


    return {
        init: init
    };
});