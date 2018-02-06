/*
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

$(document).ready(function(){
	//delete files
	$(".btn-delete").click(function(){
		var checks = getCheckedBoxes("checks-files", "id");
		var button = $("[name='files-delete']");
		button.val(checks);

	});

	//share files
	$(".btn-share").click(function(){
		var checks = getCheckedBoxes("checks-files", "id");
		var button = $("[name='files-share']");
		button.val(checks);

	});

	//download files
	$(".btn-download").click(function(){
		var checks = getCheckedBoxes("checks-files", "data-href");
		window.location = checks;

	});

	//toolbar
	if (getCheckedBoxes("checks-files") != null) {
		$('.btn-group.edit').removeClass('sr-only');
	}
	$(".form-check-input").click(function () {
        if(this.checked == true) {
            $('.btn-group.edit').removeClass('sr-only');
        } else {
            $('.btn-group.edit').addClass('sr-only');
        }
	});
});

//Pass the checkbox name to the function
function getCheckedBoxes(chkboxName, attribute) {
  var checkboxes = document.getElementsByName(chkboxName);
  var checkboxesChecked = [];
  // loop over them all
  for (var i=0; i<checkboxes.length; i++) {
     // And stick the checked ones onto an array...
     if (checkboxes[i].checked) {
        checkboxesChecked.push(checkboxes[i].getAttribute(attribute));
     }
  }
  // Return the array if it is non-empty, or null
  return checkboxesChecked.length > 0 ? checkboxesChecked : null;
}

function cbChange(obj) {
    var cbs = document.getElementsByName("checks-files");
    for (var i = 0; i < cbs.length; i++) {
        cbs[i].checked = false;
    }
    obj.checked = true;
}
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
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm1vZHVsZXMvZmlsZXMuanMiLCJtb2R1bGVzL2xheW91dC5qcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FDaEVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBIiwiZmlsZSI6ImJ1bmRsZS5qcyIsInNvdXJjZXNDb250ZW50IjpbIi8qXHJcbiAqIEBsaW5rIGh0dHBzOi8vZ2l0aHViLmNvbS9YUmF5TFAvbGVhcm5pbmctY2VudGVyLWJ1bmRsZVxyXG4gKiBAY29weXJpZ2h0IENvcHlyaWdodCAoYykgMjAxOCBOaWtsYXMgTG9vcyA8aHR0cHM6Ly9naXRodWIuY29tL1hSYXlMUD5cclxuICogQGxpY2Vuc2UgR1BMLTMuMCA8aHR0cHM6Ly9naXRodWIuY29tL1hSYXlMUC9sZWFybmluZy1jZW50ZXItYnVuZGxlL2Jsb2IvbWFzdGVyL0xJQ0VOU0U+XHJcbiAqL1xyXG5cclxuJChkb2N1bWVudCkucmVhZHkoZnVuY3Rpb24oKXtcclxuXHQvL2RlbGV0ZSBmaWxlc1xyXG5cdCQoXCIuYnRuLWRlbGV0ZVwiKS5jbGljayhmdW5jdGlvbigpe1xyXG5cdFx0dmFyIGNoZWNrcyA9IGdldENoZWNrZWRCb3hlcyhcImNoZWNrcy1maWxlc1wiLCBcImlkXCIpO1xyXG5cdFx0dmFyIGJ1dHRvbiA9ICQoXCJbbmFtZT0nZmlsZXMtZGVsZXRlJ11cIik7XHJcblx0XHRidXR0b24udmFsKGNoZWNrcyk7XHJcblxyXG5cdH0pO1xyXG5cclxuXHQvL3NoYXJlIGZpbGVzXHJcblx0JChcIi5idG4tc2hhcmVcIikuY2xpY2soZnVuY3Rpb24oKXtcclxuXHRcdHZhciBjaGVja3MgPSBnZXRDaGVja2VkQm94ZXMoXCJjaGVja3MtZmlsZXNcIiwgXCJpZFwiKTtcclxuXHRcdHZhciBidXR0b24gPSAkKFwiW25hbWU9J2ZpbGVzLXNoYXJlJ11cIik7XHJcblx0XHRidXR0b24udmFsKGNoZWNrcyk7XHJcblxyXG5cdH0pO1xyXG5cclxuXHQvL2Rvd25sb2FkIGZpbGVzXHJcblx0JChcIi5idG4tZG93bmxvYWRcIikuY2xpY2soZnVuY3Rpb24oKXtcclxuXHRcdHZhciBjaGVja3MgPSBnZXRDaGVja2VkQm94ZXMoXCJjaGVja3MtZmlsZXNcIiwgXCJkYXRhLWhyZWZcIik7XHJcblx0XHR3aW5kb3cubG9jYXRpb24gPSBjaGVja3M7XHJcblxyXG5cdH0pO1xyXG5cclxuXHQvL3Rvb2xiYXJcclxuXHRpZiAoZ2V0Q2hlY2tlZEJveGVzKFwiY2hlY2tzLWZpbGVzXCIpICE9IG51bGwpIHtcclxuXHRcdCQoJy5idG4tZ3JvdXAuZWRpdCcpLnJlbW92ZUNsYXNzKCdzci1vbmx5Jyk7XHJcblx0fVxyXG5cdCQoXCIuZm9ybS1jaGVjay1pbnB1dFwiKS5jbGljayhmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgaWYodGhpcy5jaGVja2VkID09IHRydWUpIHtcclxuICAgICAgICAgICAgJCgnLmJ0bi1ncm91cC5lZGl0JykucmVtb3ZlQ2xhc3MoJ3NyLW9ubHknKTtcclxuICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAkKCcuYnRuLWdyb3VwLmVkaXQnKS5hZGRDbGFzcygnc3Itb25seScpO1xyXG4gICAgICAgIH1cclxuXHR9KTtcclxufSk7XHJcblxyXG4vL1Bhc3MgdGhlIGNoZWNrYm94IG5hbWUgdG8gdGhlIGZ1bmN0aW9uXHJcbmZ1bmN0aW9uIGdldENoZWNrZWRCb3hlcyhjaGtib3hOYW1lLCBhdHRyaWJ1dGUpIHtcclxuICB2YXIgY2hlY2tib3hlcyA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlOYW1lKGNoa2JveE5hbWUpO1xyXG4gIHZhciBjaGVja2JveGVzQ2hlY2tlZCA9IFtdO1xyXG4gIC8vIGxvb3Agb3ZlciB0aGVtIGFsbFxyXG4gIGZvciAodmFyIGk9MDsgaTxjaGVja2JveGVzLmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgLy8gQW5kIHN0aWNrIHRoZSBjaGVja2VkIG9uZXMgb250byBhbiBhcnJheS4uLlxyXG4gICAgIGlmIChjaGVja2JveGVzW2ldLmNoZWNrZWQpIHtcclxuICAgICAgICBjaGVja2JveGVzQ2hlY2tlZC5wdXNoKGNoZWNrYm94ZXNbaV0uZ2V0QXR0cmlidXRlKGF0dHJpYnV0ZSkpO1xyXG4gICAgIH1cclxuICB9XHJcbiAgLy8gUmV0dXJuIHRoZSBhcnJheSBpZiBpdCBpcyBub24tZW1wdHksIG9yIG51bGxcclxuICByZXR1cm4gY2hlY2tib3hlc0NoZWNrZWQubGVuZ3RoID4gMCA/IGNoZWNrYm94ZXNDaGVja2VkIDogbnVsbDtcclxufVxyXG5cclxuZnVuY3Rpb24gY2JDaGFuZ2Uob2JqKSB7XHJcbiAgICB2YXIgY2JzID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeU5hbWUoXCJjaGVja3MtZmlsZXNcIik7XHJcbiAgICBmb3IgKHZhciBpID0gMDsgaSA8IGNicy5sZW5ndGg7IGkrKykge1xyXG4gICAgICAgIGNic1tpXS5jaGVja2VkID0gZmFsc2U7XHJcbiAgICB9XHJcbiAgICBvYmouY2hlY2tlZCA9IHRydWU7XHJcbn0iLCIvKlxyXG4gKiBAbGluayBodHRwczovL2dpdGh1Yi5jb20vWFJheUxQL2xlYXJuaW5nLWNlbnRlci1idW5kbGVcclxuICogQGNvcHlyaWdodCBDb3B5cmlnaHQgKGMpIDIwMTggTmlrbGFzIExvb3MgPGh0dHBzOi8vZ2l0aHViLmNvbS9YUmF5TFA+XHJcbiAqIEBsaWNlbnNlIEdQTC0zLjAgPGh0dHBzOi8vZ2l0aHViLmNvbS9YUmF5TFAvbGVhcm5pbmctY2VudGVyLWJ1bmRsZS9ibG9iL21hc3Rlci9MSUNFTlNFPlxyXG4gKi9cclxuXHJcbiQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCl7XHJcblx0XHJcblx0c2V0U2lkZWJhckhlaWdodCgpO1xyXG5cdFxyXG5cclxuXHQkKHdpbmRvdykucmVzaXplKGZ1bmN0aW9uKCl7XHJcblx0XHRzZXRTaWRlYmFySGVpZ2h0KCk7XHJcblx0XHRjZW50ZXJFbGVtZW50KCcjbG9naW5Cb3gnKTtcclxuXHR9KTtcclxuXHRcclxuXHRjZW50ZXJFbGVtZW50KCcjbG9naW5Cb3gnKTtcclxuXHRcclxuICAgICQoJyNzaWRlYmFyQ29sbGFwc2UnKS5vbignY2xpY2snLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgJCgnI25hdi1zaWRlYmFyJykudG9nZ2xlQ2xhc3MoJ2Qtbm9uZScpO1xyXG4gICAgICAgICQoJyNtYWluJykudG9nZ2xlQ2xhc3MoJ2Qtbm9uZScpO1xyXG4gICAgfSk7XHJcbiAgICBcclxuICAgICQoJy5tb2RfcGVyc29uYWxEYXRhIC53aWRnZXQtdGV4dCcpLmFkZENsYXNzKFwiZm9ybS1ncm91cFwiKTtcclxuICAgICQoJy5tb2RfcGVyc29uYWxEYXRhIC53aWRnZXQtcGFzc3dvcmQnKS5hZGRDbGFzcyhcImZvcm0tZ3JvdXBcIik7XHJcbiAgICAkKCcubW9kX3BlcnNvbmFsRGF0YSBpbnB1dCcpLmFkZENsYXNzKFwiZm9ybS1jb250cm9sXCIpO1xyXG4gICAgJCgnLm1vZF9wZXJzb25hbERhdGEgc2VsZWN0JykuYWRkQ2xhc3MoXCJmb3JtLWNvbnRyb2xcIik7XHJcblx0XHJcblx0XHJcbn0pO1xyXG5cclxuZnVuY3Rpb24gc2V0U2lkZWJhckhlaWdodCgpe1xyXG5cdHZhciBkeW5hbWljID0gJChkb2N1bWVudCkuaGVpZ2h0KCkgLSAkKCcjbmF2LWludGVybicpLm91dGVySGVpZ2h0KCk7XHJcblx0ICAgXHJcblx0dmFyIHNpZGViYXIgPSAkKCcjbmF2LXNpZGViYXInKTsgICAgXHJcblx0ICAgICAgXHJcblx0c2lkZWJhci5jc3Moe1xyXG5cdFx0J21pbi1oZWlnaHQnOiBkeW5hbWljLFxyXG5cdCAgICAnbWF4LWhlaWdodCc6IGR5bmFtaWNcclxuXHR9KTtcclxufVxyXG5cclxuZnVuY3Rpb24gY2VudGVyRWxlbWVudChlbGVtZW50KXsgXHJcblxyXG5cdC8vRXJyZWNobmV0ZSBXZXJ0ZSBhbndlbmRlblxyXG5cdCQoZWxlbWVudCkuY3NzKHtcclxuICAgICAgICAnbWFyZ2luLXRvcCc6ICgkKHdpbmRvdykuaGVpZ2h0KCkgLSAkKGVsZW1lbnQpLm91dGVySGVpZ2h0KCkpLzJcclxuXHR9KTtcclxufVxyXG5qUXVlcnkoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCQpIHtcdFx0XHJcblx0JChcInRyXCIpLmNsaWNrKGZ1bmN0aW9uKCkge1xyXG5cdFx0XHJcblx0XHRpZiAoJCh0aGlzKS5kYXRhKFwiaHJlZlwiKSl7XHJcblx0XHRcdHdpbmRvdy5sb2NhdGlvbiA9ICQodGhpcykuZGF0YShcImhyZWZcIik7XHJcblx0XHR9XHJcblx0XHRcclxuXHR9KTtcclxufSk7Il19
