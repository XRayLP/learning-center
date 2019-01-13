/*
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */
const $ = require('jquery');

import Calendar from 'tui-calendar'; /* ES6 */

import FullCalendar from 'fullcalendar';

let Routing = require('../../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router');
let Routes = require('../fos_js_routes.json');
var xhr = null;
Routing.setRoutingData(Routes);


document.addEventListener("DOMContentLoaded", function(event) {

    let calendar = $('#calendar').attr('data-id');

    // Your code to run since DOM is loaded and ready
    $('#calendar').fullCalendar({
        // put your options and callbacks here
        themeSystem: "bootstrap4",
        locale: "de",
        eventSources: [
            {
                url: Routing.generate('lc_calendar_get', {id: calendar}),
                color: 'yellow',
                textColor: 'black',
                startParam: 'startTime',
                endParam: 'endTime',
                timezoneParam: 'local',
            }
        ]
    })
});
