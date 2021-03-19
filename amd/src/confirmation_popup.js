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
 * Java Script functions for dynamically displaying confirmation popups for certain actions.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/ajax', 'core/notification'], function ($, ajax, notification) {

    /**
     * Initializes 'Confirm'-button click event listener.
     * Confirmation popups are triggered for HTML tags implementing the 'block-onboarding-confirm-btn' CSS class.
     * These HTML tags also contain HTML variables which are then passed on to the externallib.php methods to determine
     * the specific object and the type of action to be performed with the object.
     *
     * @returns {Boolean} false
     */
    var init = function () {
        // JQuery click event listener for 'Confirm'-buttons.
        $('.block-onboarding-confirm-btn').on('click', function () {
            // Gets the HTML variables of the HTML tag implementing the 'block-onboarding-confirm-btn' class.
            var type = $(this).attr('onb-data-context');
            var id = $(this).attr('onb-data-id');
            // AJAX call to externallib.php method to generate popup prompt.
            var promises = ajax.call([{
                methodname: 'block_onboarding_generate_confirmation',
                args: {
                    type: type,
                    id: id
                }
            }]);
            // First promise displays a confirmation popup with the returned string and waits for user confirmation.
            promises[0].done(function (response) {
                var deleteConfirmation = confirm(response.text);
                // Checks whether user confirms popup prompt.
                if (deleteConfirmation == true) {
                    // AJAX call to externallib.php method to execute type of action for object.
                    var promises = ajax.call([{
                        methodname: 'block_onboarding_execute_confirmation',
                        args: {
                            type: type,
                            id: id
                        }
                    }]);
                    // Second chained promise redirects user to a certain page after executing the passed action.
                    promises[0].done(function (response) {
                        if (response.redirect == "reload"){
                            location.reload();
                        } else {
                            window.location.href = response.redirect;
                        }
                        return false;
                    }).fail(notification.exception);
                    return false;
                }
            }).fail(notification.exception);
            return false;
        })
    }

    // Returns init method to be called by PHP page implementing the function.
    return {
        init: init
    };
});