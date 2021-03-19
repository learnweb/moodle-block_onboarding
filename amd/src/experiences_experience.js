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
 * Java Script functions for interacting with 'Helpful'-button when viewing an experience report.
 *
 * @package    block_onboarding
 * @copyright  2021 Westfälische Wilhelms-Universität Münster
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/ajax', 'core/notification'], function ($, ajax, notification) {

    // Variable for storing experienceid which is initialized through init method upon loading the page.
    var experienceidInit;

    /**
     * Initializes 'Helpful'-button upon loading experience report page.
     *
     * @param {int} experienceid
     * @returns {Boolean} false
     */
    var init = function (experienceid) {
        // Sets experienceid.
        experienceidInit = experienceid;
        // AJAX call to externallib.php method to get current helpfulness rating.
        var promises = ajax.call([{
            methodname: 'block_onboarding_init_helpful',
            args: {experienceid: experienceid}
        }
        ]);
        // Changes color of 'Helpful'-button depending on whether user has declared experience report as helpful.
        promises[0].done(function (response) {
            if(response.alreadyhelpful === 1) {
                $('.block-onboarding-experiences-experience-helpful-btn')
                    .css({'background-color': '#32cd32', 'color': '#ffffff'});
            } else {
                $('.block-onboarding-experiences-experience-helpful-btn')
                    .css({'background': 'rgba(128, 128, 128, 0.8)',
                    'color': '#ffffff'});
            }
        }).fail(notification.exception);
        return false;
    };

    /**
     * JQuery click event listener for concurrent 'Helpful'-button clicks.
     *
     * @param {int} experienceid
     * @returns {Boolean} false
     */
    $('.block-onboarding-experiences-experience-helpful-btn').on('click', function () {
        // AJAX call to externallib.php method to get current helpfulness rating.
        var promises = ajax.call([{
            methodname: 'block_onboarding_click_helpful',
            args: {experienceid: experienceidInit}
        }]);
        // Toggles color of 'Helpful'-button depending on users current helpfulness rating.
        promises[0].done(function (response) {
            if(response.alreadyhelpful === 1) {
                $('.block-onboarding-experiences-experience-helpful-btn')
                    .css({'background-color': '#32cd32', 'color': '#ffffff'});
            } else {
                $('.block-onboarding-experiences-experience-helpful-btn')
                    .css({'background': 'rgba(128, 128, 128, 0.8)', 'color': '#ffffff'});
            }
        }).fail(notification.exception);
        return false;
    })

    // Returns init method to be called by PHP page implementing the functions.
    return {
        init: init
    };
});