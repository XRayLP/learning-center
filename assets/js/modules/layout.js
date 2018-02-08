/*
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

$(document).ready(function(){
	
	setSidebarHeight();
	

	$(window).resize(function(){
		setSidebarHeight();
		centerElement('#loginBox');
	});
	
	centerElement('#loginBox');
	
    $('#sidebarCollapse').on('click', function () {
        $('#nav-sidebar').toggleClass('d-none');
        $('#main').toggleClass('d-none');
    });
    
    $('.mod_personalData .widget-text').addClass("form-group");
    $('.mod_personalData .widget-password').addClass("form-group");
    $('.mod_personalData input').addClass("form-control");
    $('.mod_personalData select').addClass("form-control");
	
	
});

function setSidebarHeight(){
	var dynamic = $(document).height() - $('#nav-intern').outerHeight();
	   
	var sidebar = $('#nav-sidebar');    
	      
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
jQuery(document).ready(function($) {		
	$("tr").click(function() {
		
		if ($(this).data("href")){
			window.location = $(this).data("href");
		}
		
	});
});