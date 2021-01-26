define(['jquery', 'core/ajax', 'core/notification'], function ($, ajax, notification) {

    // TODO: Randfälle behandeln, z.B. letzter Schritt in Liste usw.
    // TODO: Fortschritt speichern -> set_current_step
    // TODO: Erledigt, Überspringen und Zurück mit verschiedenen Aufurfparametern für get_cur, set_cur und get_info impl.
    // TODO: clean code


    var init = function (userid) {

        // TODO: Return-Wert von AJAX erforderlich, Code läuft aber weiter durch und wartet nicht auf AJAX Antwort (weil asychron)

        // get_current_user_step(userid);
        // var cur_stepid = get_current_user_step(userid);
        // alert("return ajax: " + cur_stepid);
        // var cur_step = get_step_info(cur_stepid, -1);
        // -> replace



        // TODO: Auslagern bzw. umschreiben
        // Funktion für Erledigt Button, aktuell auskommentiert und wird noch in andere Methode ausgelagert
        // später soll neben der init Methode jeweils eine Methode für die jeweiligen Knöpfe (Erledigt, Überspr., Zurück)
        // bestehen, die dann mit den jeweiligen Werten get_current_user_step, set_current_user_step, get_step_info und
        // replace_html aufrufen (ggf. noch weitere)
        $('.done_btn').on('click', function () {
            // alert("hallo! :)")
            // GGF. mit 2 Promises probieren?
            var promises = ajax.call([{
                methodname: 'block_onboarding_get_step_info',
                args: {
                    stepid: stepid,
                    position: position
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


    var get_current_user_step = function (userid) {

        /*
         TODO: Problem - AJAX ist selbstverständlich asynchron, soll aber eigentlich nach Auslesen aus der Datenbank einen return-Wert zurück geben
         */

        // ajax.call([{
        //     methodname: 'block_onboarding_get_current_user_step',
        //     args: {userid: userid},
        //     success: ,
        //     fail: notification.exception
        // }]);

        var promises = ajax.call([{
            methodname: 'block_onboarding_get_current_user_step',
            args: {
                userid: userid
            }
        }
        ]);
        promises[0].done(function (response) {
            let stepid = response.stepid;
            // alert("in ajax: " + stepid);
            // !!! Methodenaufruf hier falsch - sollte eigentlich auch von Aufrufer von get_current_user_step gecalled werden, Problem s.o.
            cur_step = get_step_info(stepid, -1);
            // return stepid;
        }).fail(notification.exception);
    };


    // wenn einer dieser Übergabeparameter -1 ist, entspricht dies quasi einem nicht vorhandenen Paramter
    // Übergabe von booleschen Werten oder partieller Aufruf nicht möglich wegen strengen Vorgaben für externallib
    // Funktionsdefintionen :( -> -1 wird dort jedoch enstprechend weiter behandelt :)
    var get_step_info = function (stepid, position) {

        var promises = ajax.call([{
            methodname: 'block_onboarding_get_step_info',
            args: {
                stepid: stepid,
                position: position
            }
        }
        ]);
        promises[0].done(function (response) {
            let step = {
                name: response.name,
                description: response.description,
                position: response.position
            };
            return step;
        }).fail(notification.exception);
    };


    return {
        init: init

    };
});