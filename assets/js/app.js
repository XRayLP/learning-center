/*
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

//SCSS
require('../scss/app.scss');

//JS
const $ = require('jquery');
require('bootstrap');
require('./modules/progressbar.js');
require('./modules/layout.js');
require('./modules/files.js');

//Main
$(document).ready(function(){
    $(window).resize(function(){
        setSidebarHeight();
    });
    setSidebarHeight();

    $("#container").on('click-row.bs.table', function (e, row, $element) {
        window.location = $element.data('href');
    });

    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
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

//Font Awesome
import fontawesome from '@fortawesome/fontawesome';
import faUser from '@fortawesome/fontawesome-free-solid/faUser';
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


//Users
fontawesome.library.add(faUser);
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

