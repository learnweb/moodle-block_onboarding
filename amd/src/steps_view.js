define(['jquery', 'core/ajax', 'core/notification', 'core/str'], function ($, ajax, notification, str) {

    var confetti_toogle = false;
    var step_string = 'Step ';
    var achievement_string = 'Achviement! ';
    var reset_message_string = 'Do you really want to reset your progress?\nAll progress will be lost permanently.'

    var init = function () {
        // TODO: Ladezeit dauert relativ lange :(
        var step_promise = str.get_string('step_step_js', 'block_onboarding');
        var achievement_promise = str.get_string('step_achievement_js', 'block_onboarding');
        var reset_message_promise = str.get_string('button_reset_message_js', 'block_onboarding');

        $.when(step_promise, achievement_promise, reset_message_promise)
            .done(function (step_promise_string, achievement_promise_string, reset_message_promise_string) {
                step_string = step_promise_string;
                achievement_string = achievement_promise_string;
                reset_message_string = reset_message_promise_string;

                var promises = ajax.call([{
                    methodname: 'block_onboarding_init_step',
                    args: {}
                }]);
                promises[0].done(function (response) {
                    if(response.visibility == 0){
                        $('.show-btn').css('display', 'inline');
                    } else if(response.visibility == 1){
                        $('.hide-btn').css('display', 'inline');
                        $('.steps-container').css('display', 'block');
                    } else {
                        $('.steps-container').css('display', 'block');
                    }
                    if (response.completed == 2) {
                        $('.step-completed').css('visibility', 'visible');
                        $('.next-btn').css('display', 'none');
                        $('.reset-btn').css('visibility', 'visible');
                    } else if (response.completed == 1) {
                        $('.step-completed').css('visibility', 'visible');
                    } else {
                        $('.step-completed').css('visibility', 'hidden');
                    }
                    if (response.achievement == 1) {
                        $('.step-title').text(achievement_string + response.name);
                        confetti_toogle = true;
                        confetti();
                        setTimeout(function () {
                            confetti_toogle = false;
                        }, 4000);
                    } else {
                        $('.step-title').text(step_string + response.position + ': ' + response.name);
                    }
                    $('.step_description').text(response.description);
                    $('.progress-bar-value').text(response.progress + '%');
                    $('.progress-bar-fill').css('width', (response.progress + '%'));
                }).fail(notification.exception);
            });
    };

    $('.next-btn').on('click', function () {
        var promises = ajax.call([{
            methodname: 'block_onboarding_next_step',
            args: {}
        }]);
        promises[0].done(function (response) {
            if (response.completed == 2) {
                $('.step-completed').css('visibility', 'visible');
                $('.next-btn').css('display', 'none');
                $('.reset-btn').css('visibility', 'visible');
            } else if (response.completed == 1) {
                $('.step-completed').css('visibility', 'visible');
            } else {
                $('.step-completed').css('visibility', 'hidden');
            }
            if (response.achievement == 1) {
                $('.step-title').text(achievement_string + response.name);
                confetti_toogle = true;
                confetti();
                setTimeout(function () {
                    confetti_toogle = false;
                }, 4000);
            } else {
                $('.step-title').text(step_string + response.position + ': ' + response.name);
                confetti_toogle = false;
            }
            $('.step_description').text(response.description);
            $('.progress-bar-value').text(response.progress + '%');
            $('.progress-bar-fill').css('width', (response.progress + '%'));
        }).fail(notification.exception);
    })

    $('.back-btn').on('click', function () {
        var promises = ajax.call([{
            methodname: 'block_onboarding_preceding_step',
            args: {}
        }]);
        promises[0].done(function (response) {
            $('.reset-btn').css('visibility', 'hidden');
            $('.next-btn').css('display', 'inline');
            if (response.completed == 1 || response.completed == 2) {
                $('.step-completed').css('visibility', 'visible');
            } else {
                $('.step-completed').css('visibility', 'hidden');
            }
            if (response.achievement == 1) {
                $('.step-title').text(achievement_string + response.name);
                confetti_toogle = true;
                confetti();
                setTimeout(function () {
                    confetti_toogle = false;
                }, 4000);
            } else {
                $('.step-title').text(step_string + response.position + ': ' + response.name);
                confetti_toogle = false;
            }
            $('.step_description').text(response.description);
        }).fail(notification.exception);
    })

    $('.reset-btn').on('click', function () {
        var reset_confirmation = confirm(reset_message_string);
        if (reset_confirmation == true) {
            var promises = ajax.call([{
                methodname: 'block_onboarding_reset_progress',
                args: {}
            }]);
            promises[0].done(function (response) {
                location.reload();
                return false;
            }).fail(notification.exception);
        }
    })

    $('.hide-btn').on('click', function () {
        var visibility = 0;
        var promises = ajax.call([{
            methodname: 'block_onboarding_toggle_visibility',
            args: {
                visibility: visibility
            }
        }]);
        promises[0].done(function (response) {
            $('.hide-btn').css('display', 'none');
            $('.show-btn').css('display', 'inline');
            $('.steps-container').css('display', 'none');
        }).fail(notification.exception);
    })

    $('.show-btn').on('click', function () {
        var visibility = 1;
        var promises = ajax.call([{
            methodname: 'block_onboarding_toggle_visibility',
            args: {
                visibility: visibility
            }
        }]);
        promises[0].done(function (response) {
            $('.hide-btn').css('display', 'inline');
            $('.show-btn').css('display', 'none');
            $('.steps-container').css('display', 'block');
        }).fail(notification.exception);
    })

    var confetti = function () {

        let achievement_canvas = document.getElementById('achievement-confetti');
        achievement_canvas.width = document.getElementById('achievement-confetti').offsetWidth;
        achievement_canvas.height = document.getElementById('achievement-confetti').offsetHeight;

        let context = achievement_canvas.getContext('2d');
        let particle = [];
        let number_of_particles = 45;
        let lastUpdateTime = Date.now();

        function randomColor() {
            let colors = ['#f00', '#0f0', '#00f', '#0ff', '#f0f', '#ff0'];
            return colors[Math.floor(Math.random() * colors.length)];
        }

        function update() {
            let now = Date.now(),
                dif = now - lastUpdateTime;

            for (let i = particle.length - 1; i >= 0; i--) {
                let p = particle[i];

                if (p.y > achievement_canvas.height) {
                    particle.splice(i, 1);
                    continue;
                }

                p.y += p.gravity * dif;
                p.rotation += p.rotationSpeed * dif;
            }


            while (particle.length < number_of_particles && confetti_toogle == true) {
                particle.push(new Particle(Math.random() * achievement_canvas.width, -20));
            }

            lastUpdateTime = now;

            setTimeout(update, 1);
        }

        function draw() {
            context.clearRect(0, 0, achievement_canvas.width, achievement_canvas.height);

            particle.forEach(function (p) {
                context.save();

                context.fillStyle = p.color;

                context.translate(p.x + p.size / 2, p.y + p.size / 2);
                context.rotate(p.rotation);

                context.fillRect(-p.size / 2, -p.size / 2, p.size, p.size);

                context.restore();
            });

            requestAnimationFrame(draw);
        }

        function Particle(x, y) {
            this.x = x;
            this.y = y;
            this.size = (Math.random() * 0.5 + 0.75) * 15;
            this.gravity = (Math.random() * 0.5 + 0.75) * 0.1;
            this.rotation = (Math.PI * 2) * Math.random();
            this.rotationSpeed = (Math.PI * 2) * (Math.random() - 0.5) * 0.001;
            this.color = randomColor();
        }


        while (particle.length < number_of_particles && confetti_toogle == true) {
            particle.push(new Particle(Math.random() * achievement_canvas.width, Math.random() * achievement_canvas.height));
        }
        update();
        draw();
    }

    return {
        init: init
    };
});