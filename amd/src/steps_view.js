define(['jquery', 'core/ajax', 'core/notification'], function ($, ajax, notification) {

    // beinahltet später mind. 3 Schritte: Progress speichern, nächsten Schritt anzeigen und Progressbar updaten
    // Fälle: kein Schritt und alle Schritte durchlaufen abdecken!
    var next_step = function (stepid_init, position_init) {
        // alert("hallo :)");
        var stepid = stepid_init;
        var position = position_init;
        $('.next_btn').on('click', function () {
            // alert("hallo! :)")
            var promises = ajax.call([{
                methodname: 'block_onboarding_get_step',
                args: {
                    stepid: stepid,
                    position: position
                }
            }
            ]);
            promises[0].done(function (response) {
                //alert(response.id)
                var html1 = '<div class=\"step_description\">' + response.description + '</div>';
                $('.step_description').replaceWith(html1);
                var html2 = '<h5 class=\"step_title\"><b>Step #' + response.position + ': ' + response.name + '</b></h5>';
                $('.step_title').replaceWith(html2);
                stepid = response.id + 1
                position = response.position + 1
            }).fail(notification.exception);
        })
    }

    return {
        next_step: next_step

    };
});