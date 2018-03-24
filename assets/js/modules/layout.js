/*
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

function setSidebarHeight(){
    let dynamic, sidebar;
    dynamic = $(document).height() - $('#lc_header').outerHeight();
    sidebar = $('#lc_sidebar');

    sidebar.css({
        'min-height': dynamic,
        'max-height': dynamic
    });
}