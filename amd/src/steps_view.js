// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Java Script functions for dynamically displaying steps within the First Steps section.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/ajax', 'core/notification', 'core/str'], function ($, ajax, notification, str) {

    // Toggles confetti - confetti is only displayed for achievement steps.
    var confetti_toogle = false;
    // Default backup strings.
    var step_string = 'Step ';
    var achievement_string = 'Achviement! ';
    var reset_message_string = 'Do you really want to reset your progress?\nAll progress will be lost permanently.'

    /**
     * Provides the First Steps section with content upon loading the Guide page and gets the required language strings.
     *
     * @returns {Boolean} false
     */
    var init = function () {
        // Gets language strings from block_onboarding language PHP file.
        var step_promise = str.get_string('step_step_js', 'block_onboarding');
        var achievement_promise = str.get_string('step_achievement_js', 'block_onboarding');
        var reset_message_promise = str.get_string('button_reset_message_js', 'block_onboarding');
        $.when(step_promise, achievement_promise, reset_message_promise)
            .done(function (step_promise_string, achievement_promise_string, reset_message_promise_string) {
                // Replaces default backup strings with retrieved language strings.
                step_string = step_promise_string;
                achievement_string = achievement_promise_string;
                reset_message_string = reset_message_promise_string;
                // AJAX call to externallib.php method to get initialize First Steps section with current user step.
                var promises = ajax.call([{
                    methodname: 'block_onboarding_init_step',
                    args: {}
                }]);
                promises[0].done(function (response) {
                    // Toggles visibility of First Steps section. Visibility is 'hidden' by default.
                    if(response.visibility == 0){
                        $('.block-onboarding-steps-show-btn').css('display', 'inline');
                    } else if(response.visibility == 1){
                        $('.block-onboarding-steps-hide-btn').css('display', 'inline');
                        $('.block-onboarding-steps-container').css('display', 'block');
                    } else {
                        $('.block-onboarding-steps-container').css('display', 'block');
                    }
                    // Toggles 'completed'-indicator of First Steps section. When all steps are completed and the
                    // user has reached the max step, a 'Reset progress'-button is displayed.
                    if (response.completed == 2) {
                        $('.block-onboarding-steps-step-completed').css('visibility', 'visible');
                        $('.block-onboarding-steps-next-btn').css('display', 'none');
                        $('.block-onboarding-steps-reset-btn').css('visibility', 'visible');
                    } else if (response.completed == 1) {
                        $('.block-onboarding-steps-step-completed').css('visibility', 'visible');
                    } else {
                        $('.block-onboarding-steps-step-completed').css('visibility', 'hidden');
                    }
                    // Determines whether step is a regular step or an achievement step. Displays confetti animation
                    // for achievement steps.
                    if (response.achievement == 1) {
                        $('.block-onboarding-steps-step-title').text(achievement_string + response.name);
                        confetti_toogle = true;
                        confetti();
                        setTimeout(function () {
                            confetti_toogle = false;
                        }, 4000);
                    } else {
                        $('.block-onboarding-steps-step-title').text(step_string + response.position + ': '
                            + response.name);
                    }
                    // Fills First Steps section with retrieved content.
                    $('.block-onboarding-steps-step-description').text(response.description);
                    $('.block-onboarding-steps-progress-bar-value').text(response.progress + '%');
                    $('.block-onboarding-steps-progress-bar-fill').css('width', (response.progress + '%'));
                }).fail(notification.exception);
                return false;
            });
    };

    /**
     * JQuery click event listener for 'Next'-button clicks in the First Steps section.
     *
     * @returns {Boolean} false
     */
    $('.block-onboarding-steps-next-btn').on('click', function () {
        // AJAX call to externallib.php method to get next step.
        var promises = ajax.call([{
            methodname: 'block_onboarding_next_step',
            args: {}
        }]);
        promises[0].done(function (response) {
            // Toggles 'completed'-indicator of First Steps section. When all steps are completed and the
            // user has reached the max step, a 'Reset progress'-button is displayed.
            if (response.completed == 2) {
                $('.block-onboarding-steps-step-completed').css('visibility', 'visible');
                $('.block-onboarding-steps-next-btn').css('display', 'none');
                $('.block-onboarding-steps-reset-btn').css('visibility', 'visible');
            } else if (response.completed == 1) {
                $('.block-onboarding-steps-step-completed').css('visibility', 'visible');
            } else {
                $('.block-onboarding-steps-step-completed').css('visibility', 'hidden');
            }
            // Determines whether next step is a regular step or an achievement step. Displays confetti animation
            // for achievement steps.
            if (response.achievement == 1) {
                $('.block-onboarding-steps-step-title').text(achievement_string + response.name);
                confetti_toogle = true;
                confetti();
                setTimeout(function () {
                    confetti_toogle = false;
                }, 4000);
            } else {
                $('.block-onboarding-steps-step-title').text(step_string + response.position + ': ' + response.name);
                confetti_toogle = false;
            }
            // Fills First Steps section with retrieved content of next step.
            $('.block-onboarding-steps-step-description').text(response.description);
            $('.block-onboarding-steps-progress-bar-value').text(response.progress + '%');
            $('.block-onboarding-steps-progress-bar-fill').css('width', (response.progress + '%'));
        }).fail(notification.exception);
        return false;
    })

    /**
     * JQuery click event listener for 'Back'-button clicks in the First Steps section.
     *
     * @returns {Boolean} false
     */
    $('.block-onboarding-steps-back-btn').on('click', function () {
        // AJAX call to externallib.php method to get preceding step.
        var promises = ajax.call([{
            methodname: 'block_onboarding_preceding_step',
            args: {}
        }]);
        promises[0].done(function (response) {
            // Hides 'Back'-button and displays 'Next'-button by default.
            $('.block-onboarding-steps-reset-btn').css('visibility', 'hidden');
            $('.block-onboarding-steps-next-btn').css('display', 'inline');
            // Toggles 'completed'-indicator of First Steps section.
            if (response.completed == 1 || response.completed == 2) {
                $('.block-onboarding-steps-step-completed').css('visibility', 'visible');
            } else {
                $('.block-onboarding-steps-step-completed').css('visibility', 'hidden');
            }
            // Determines whether preceding step is a regular step or an achievement step. Displays confetti animation
            // for achievement steps.
            if (response.achievement == 1) {
                $('.block-onboarding-steps-step-title').text(achievement_string + response.name);
                confetti_toogle = true;
                confetti();
                setTimeout(function () {
                    confetti_toogle = false;
                }, 4000);
            } else {
                $('.block-onboarding-steps-step-title').text(step_string + response.position + ': ' + response.name);
                confetti_toogle = false;
            }
            // Updated First Steps description box with retrieved information.
            $('.block-onboarding-steps-step-description').text(response.description);
        }).fail(notification.exception);
        return false;
    })

    /**
     * JQuery click event listener for 'Reset'-button clicks in the First Steps section.
     * The 'Reset'-button will only be visible, when the user has completed all steps and is currently at
     * the max step position.
     *
     * @returns {Boolean} false
     */
    $('.block-onboarding-steps-reset-btn').on('click', function () {
        // Displays confirmation popup asking the user to confirm the reset process.
        var reset_confirmation = confirm(reset_message_string);
        if (reset_confirmation == true) {
            // AJAX call to externallib.php method to reset user progress.
            var promises = ajax.call([{
                methodname: 'block_onboarding_reset_progress',
                args: {}
            }]);
            // Reloads current page upon progress deletion to initialize new records for user's First Steps section.
            promises[0].done(function (response) {
                location.reload();
                return false;
            }).fail(notification.exception);
            return false;
        }
    })

    /**
     * JQuery click event listener for 'Hide'-button clicks in the First Steps section.
     * Clicking the 'Hide'-button will make the whole First Steps section invisible.
     *
     * @returns {Boolean} false
     */
    $('.block-onboarding-steps-hide-btn').on('click', function () {
        var visibility = 0;
        // AJAX call to externallib.php method to update visibility value.
        var promises = ajax.call([{
            methodname: 'block_onboarding_toggle_visibility',
            args: {
                visibility: visibility
            }
        }]);
        // Hides First Steps section and toggles 'Hide'-/'Show'-buttons.
        promises[0].done(function (response) {
            $('.block-onboarding-steps-hide-btn').css('display', 'none');
            $('.block-onboarding-steps-show-btn').css('display', 'inline');
            $('.block-onboarding-steps-container').css('display', 'none');
        }).fail(notification.exception);
        return false;
    })

    /**
     * JQuery click event listener for 'Show'-button clicks in the First Steps section.
     * Clicking the 'Show'-button will make the First Steps reappear.
     *
     * @returns {Boolean} false
     */
    $('.block-onboarding-steps-show-btn').on('click', function () {
        var visibility = 1;
        // AJAX call to externallib.php method to update visibility value.
        var promises = ajax.call([{
            methodname: 'block_onboarding_toggle_visibility',
            args: {
                visibility: visibility
            }
        }]);
        // Displays First Steps section and toggles 'Hide'-/'Show'-buttons.
        promises[0].done(function (response) {
            $('.block-onboarding-steps-hide-btn').css('display', 'inline');
            $('.block-onboarding-steps-show-btn').css('display', 'none');
            $('.block-onboarding-steps-container').css('display', 'block');
        }).fail(notification.exception);
        return false;
    })

    /**
     * Confetti function to display confetti animation within the First Steps section.
     * The confetti animation will only be played for a brief period when the user has reached an achievement step.
     */
    var confetti = function () {
        // The confetti function utilizes an HTML canvas which is present as an overlay over the First Steps functions.
        let achievement_canvas = document.getElementById('block-onboarding-steps-achievement-confetti');
        achievement_canvas.width = document
            .getElementById('block-onboarding-steps-achievement-confetti').offsetWidth;
        achievement_canvas.height = document
            .getElementById('block-onboarding-steps-achievement-confetti').offsetHeight;

        // Initialization of base parameters.
        let context = achievement_canvas.getContext('2d');
        let particle = [];
        let number_of_particles = 45;
        let lastUpdateTime = Date.now();

        /**
         * Color randomizer function.
         * Randomizes the color of the generated confetti particles.
         *
         * @returns {(string|Array.)} generated colors
         */
        function randomColor() {
            let colors = ['#f00', '#0f0', '#00f', '#0ff', '#f0f', '#ff0'];
            return colors[Math.floor(Math.random() * colors.length)];
        }

        /**
         * Canvas update function.
         * Updates particle positions and rotation of confetti particles.
         */
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

        /**
         * Canvas draws function.
         * Draws generated confetti particles on the HTML canvas.
         */
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

        /**
         * Particle function.
         * Randomly generates confetti particle parameters.
         *
         * @param {int} x x-coordinate
         * @param {int} y y-coordinate
         */
        function Particle(x, y) {
            this.x = x;
            this.y = y;
            this.size = (Math.random() * 0.5 + 0.75) * 15;
            this.gravity = (Math.random() * 0.5 + 0.75) * 0.1;
            this.rotation = (Math.PI * 2) * Math.random();
            this.rotationSpeed = (Math.PI * 2) * (Math.random() - 0.5) * 0.001;
            this.color = randomColor();
        }

        //  Updates and draws confetti particles on HTML canvas as long as confetti toggle is set to true.
        while (particle.length < number_of_particles && confetti_toogle == true) {
            particle.push(new Particle(Math.random() * achievement_canvas.width,
                Math.random() * achievement_canvas.height));
        }
        update();
        draw();
    }

    // Returns init method to be called by PHP page implementing the functions.
    return {
        init: init
    };
});