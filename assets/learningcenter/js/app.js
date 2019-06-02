/*
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

//SCSS
require('../scss/app.scss');

//JS
const $ = require('jquery');
global.$ = global.jQuery = $;

require('materialize-css');
require('dropzone/dist/dropzone');
require('dropzone/dist/dropzone.css');
require('tui-calendar');
require('fullcalendar');

require('./modules/progressbar.js');
require('./modules/layout.js');
require('./modules/files.js');
require('./modules/members.js');
require('./modules/notifications.js');
require('./modules/calendar');



//Main
$(document).ready(function(){

    $(window).resize(function(){
        setSidebarHeight();
        centerElement('.login-box');
    });
    setSidebarHeight();
    centerElement('.login-box');

    $("#container").on('click-row.bs.table', function (e, row, $element) {
        window.location = $element.data('href');
    });

    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });

    $('.sidenav').sidenav();

    $('.sidenav.account').sidenav({
        edge: 'right'
    });

    $('.dropdown-trigger').dropdown({
        alignment:'right'
    });

    $('.modal').modal();

    $('select').formSelect();

    $('.current').addClass('active');

    //var height = $(window).height() - ($('.navbar-fixed').height() + $('.tabs').height() + $('.reply').height());
    //$('.chatlogs').css('height', height);

});

function setSidebarHeight(){
    let dynamic, sidebar;
    dynamic = $(document).height() - $('#lc_header').outerHeight();
    sidebar = $('#lc_sidebar');

    sidebar.css({
        'min-height': dynamic,
        'max-height': dynamic
    });
}

function centerElement(element){

    //Errechnete Werte anwenden
    $(element).css({
        'margin-top': ($(window).height() - $(element).outerHeight())/2
    });
}

//Font Awesome
import fontawesome from '@fortawesome/fontawesome';
import faUser from '@fortawesome/fontawesome-free-solid/faUser';
import faUsers from '@fortawesome/fontawesome-free-solid/faUsers';
import faKey from '@fortawesome/fontawesome-free-solid/faKey';
import faSignOutAlt from '@fortawesome/fontawesome-free-solid/faSignOutAlt';
import faFile from '@fortawesome/fontawesome-free-solid/faFile';
import faFileImage from '@fortawesome/fontawesome-free-solid/faFileImage';
import faFileWord from '@fortawesome/fontawesome-free-solid/faFileWord';
import faFilePowerpoint from '@fortawesome/fontawesome-free-solid/faFilePowerpoint';
import faFileExcel from '@fortawesome/fontawesome-free-solid/faFileExcel';
import faFilePdf from '@fortawesome/fontawesome-free-solid/faFilePdf';
import faFileArchive from '@fortawesome/fontawesome-free-solid/faFileArchive';
import faFileVideo from '@fortawesome/fontawesome-free-solid/faFileVideo';
import faFileAudio from '@fortawesome/fontawesome-free-solid/faFileAudio';
import faFileCode from '@fortawesome/fontawesome-free-solid/faFileCode';
import faFolder from '@fortawesome/fontawesome-free-solid/faFolder';
import faUpload from '@fortawesome/fontawesome-free-solid/faUpload';
import faDownload from '@fortawesome/fontawesome-free-solid/faDownload';
import faTrash from '@fortawesome/fontawesome-free-solid/faTrash';
import faClock from '@fortawesome/fontawesome-free-solid/faClock';
import faHome from '@fortawesome/fontawesome-free-solid/faHome';
import faBook from '@fortawesome/fontawesome-free-solid/faBook';
import faHdd from '@fortawesome/fontawesome-free-solid/faHdd';
import faProjectDiagram from '@fortawesome/fontawesome-free-solid/faProjectDiagram';
import faListUl from '@fortawesome/fontawesome-free-solid/faListUl';
import faPlus from '@fortawesome/fontawesome-free-solid/faPlus';
import faPlusCircle from '@fortawesome/fontawesome-free-solid/faPlusCircle';
import faCheck from '@fortawesome/fontawesome-free-solid/faCheck';
import faCheckCircle from '@fortawesome/fontawesome-free-solid/faCheckCircle';
import faTimes from '@fortawesome/fontawesome-free-solid/faTimes';
import faTimesCircle from '@fortawesome/fontawesome-free-solid/faTimesCircle';
import faCrown from '@fortawesome/fontawesome-free-solid/faCrown';
import faBell from '@fortawesome/fontawesome-free-solid/faBell';
import faEnvelope from '@fortawesome/fontawesome-free-solid/faEnvelope';
import faEnvelopeOpen from '@fortawesome/fontawesome-free-solid/faEnvelopeOpen';
import faCog from '@fortawesome/fontawesome-free-solid/faCog';
import faSpinner from '@fortawesome/fontawesome-free-solid/faSpinner';
import faChalboardTeacher from '@fortawesome/fontawesome-free-solid/faChalkboardTeacher';
import faComments from '@fortawesome/fontawesome-free-solid/faComments';
import faChevronLeft from '@fortawesome/fontawesome-free-solid/faChevronLeft';
import faChevronRight from '@fortawesome/fontawesome-free-solid/faChevronRight';
import faAngleDoubleLeft from '@fortawesome/fontawesome-free-solid/faAngleDoubleLeft';
import faAngleDoubleRight from '@fortawesome/fontawesome-free-solid/faAngleDoubleRight';

import faAngular from '@fortawesome/fontawesome-free-brands/faAngular';


//Users
fontawesome.library.add(faUser);
fontawesome.library.add(faUsers);
fontawesome.library.add(faKey);
fontawesome.library.add(faSignOutAlt);
//Files
fontawesome.library.add(faFile);
fontawesome.library.add(faFileImage);
fontawesome.library.add(faFileWord);
fontawesome.library.add(faFileExcel);
fontawesome.library.add(faFilePowerpoint);
fontawesome.library.add(faFilePdf);
fontawesome.library.add(faFileArchive);
fontawesome.library.add(faFileVideo);
fontawesome.library.add(faFileAudio);
fontawesome.library.add(faFileCode);
fontawesome.library.add(faFolder);

fontawesome.library.add(faUpload);
fontawesome.library.add(faDownload);
fontawesome.library.add(faTrash);

fontawesome.library.add(faClock);

fontawesome.library.add(faHome);
fontawesome.library.add(faBook);
fontawesome.library.add(faHdd);
fontawesome.library.add(faProjectDiagram);
fontawesome.library.add(faListUl);

fontawesome.library.add(faPlus);
fontawesome.library.add(faPlusCircle);

fontawesome.library.add(faCheck);
fontawesome.library.add(faCheckCircle);
fontawesome.library.add(faTimes);
fontawesome.library.add(faTimesCircle);

fontawesome.library.add(faBell);
fontawesome.library.add(faEnvelope);
fontawesome.library.add(faEnvelopeOpen);
fontawesome.library.add(faCog);
fontawesome.library.add(faSpinner);

fontawesome.library.add(faChalboardTeacher);
fontawesome.library.add(faComments);

fontawesome.library.add(faChevronLeft);
fontawesome.library.add(faChevronRight);
fontawesome.library.add(faAngleDoubleLeft);
fontawesome.library.add(faAngleDoubleRight);

fontawesome.library.add(faCrown);
fontawesome.library.add(faAngular);

