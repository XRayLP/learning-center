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
    if(isOneCheckboxChecked("checks-files")){
        $('.btn-group.edit').removeClass('sr-only');
    } else {
        $('.btn-group.edit').addClass('sr-only');
    }

    $('input.form-check-input').on('change', function() {
        $('input.form-check-input').not(this).prop('checked', false);
        if(isOneCheckboxChecked("checks-files")){
            $('.btn-group.edit').removeClass('sr-only');
		} else {
            $('.btn-group.edit').addClass('sr-only');
		}
    });
    $('p.error').addClass('alert alert-danger').prependTo('div.mod_files');
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

function isOneCheckboxChecked(chkboxName) {
    var checkboxes = document.getElementsByName(chkboxName);
    var checkboxesChecked = [];
    // loop over them all
    for (var i=0; i<checkboxes.length; i++) {
        // And stick the checked ones onto an array...
        if (checkboxes[i].checked) {
            return true;
        }
    }
    return false;
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

$(function() {

	$(window).resize(function(){
		setSidebarHeight();
		centerElement('#loginBox');
	});
	$(document).scroll(function(){
        setSidebarHeight();
	});
	
	centerElement('#loginBox');
    setSidebarHeight();

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
$(document).ready(function(){
    var percent = $('.progress-bar').attr("aria-valuenow");
    var degree = $('.progress-circle').attr("aria-degree");
    if (degree < 180) {
        $('.progress-right .progress-bar ').css("transform", "rotate(" + (degree) +"deg)");
    } else {
        $('.progress-right .progress-bar ').css("transform", "rotate(180deg)");
        $('.progress-left .progress-bar ').css("transform", "rotate(" + (degree - 180) + "deg)");
    }
});

//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm1vZHVsZXMvZmlsZXMuanMiLCJtb2R1bGVzL2xheW91dC5qcyIsIm1vZHVsZXMvcHJvZ3Jlc3NiYXIuanMiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQ2xGQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQ3pEQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBIiwiZmlsZSI6ImJ1bmRsZS5qcyIsInNvdXJjZXNDb250ZW50IjpbIi8qXHJcbiAqIEBsaW5rIGh0dHBzOi8vZ2l0aHViLmNvbS9YUmF5TFAvbGVhcm5pbmctY2VudGVyLWJ1bmRsZVxyXG4gKiBAY29weXJpZ2h0IENvcHlyaWdodCAoYykgMjAxOCBOaWtsYXMgTG9vcyA8aHR0cHM6Ly9naXRodWIuY29tL1hSYXlMUD5cclxuICogQGxpY2Vuc2UgR1BMLTMuMCA8aHR0cHM6Ly9naXRodWIuY29tL1hSYXlMUC9sZWFybmluZy1jZW50ZXItYnVuZGxlL2Jsb2IvbWFzdGVyL0xJQ0VOU0U+XHJcbiAqL1xyXG5cclxuJChkb2N1bWVudCkucmVhZHkoZnVuY3Rpb24oKXtcclxuXHQvL2RlbGV0ZSBmaWxlc1xyXG5cdCQoXCIuYnRuLWRlbGV0ZVwiKS5jbGljayhmdW5jdGlvbigpe1xyXG5cdFx0dmFyIGNoZWNrcyA9IGdldENoZWNrZWRCb3hlcyhcImNoZWNrcy1maWxlc1wiLCBcImlkXCIpO1xyXG5cdFx0dmFyIGJ1dHRvbiA9ICQoXCJbbmFtZT0nZmlsZXMtZGVsZXRlJ11cIik7XHJcblx0XHRidXR0b24udmFsKGNoZWNrcyk7XHJcblxyXG5cdH0pO1xyXG5cclxuXHQvL3NoYXJlIGZpbGVzXHJcblx0JChcIi5idG4tc2hhcmVcIikuY2xpY2soZnVuY3Rpb24oKXtcclxuXHRcdHZhciBjaGVja3MgPSBnZXRDaGVja2VkQm94ZXMoXCJjaGVja3MtZmlsZXNcIiwgXCJpZFwiKTtcclxuXHRcdHZhciBidXR0b24gPSAkKFwiW25hbWU9J2ZpbGVzLXNoYXJlJ11cIik7XHJcblx0XHRidXR0b24udmFsKGNoZWNrcyk7XHJcblxyXG5cdH0pO1xyXG5cclxuXHQvL2Rvd25sb2FkIGZpbGVzXHJcblx0JChcIi5idG4tZG93bmxvYWRcIikuY2xpY2soZnVuY3Rpb24oKXtcclxuXHRcdHZhciBjaGVja3MgPSBnZXRDaGVja2VkQm94ZXMoXCJjaGVja3MtZmlsZXNcIiwgXCJkYXRhLWhyZWZcIik7XHJcblx0XHR3aW5kb3cubG9jYXRpb24gPSBjaGVja3M7XHJcblxyXG5cdH0pO1xyXG5cclxuXHQvL3Rvb2xiYXJcclxuICAgIGlmKGlzT25lQ2hlY2tib3hDaGVja2VkKFwiY2hlY2tzLWZpbGVzXCIpKXtcclxuICAgICAgICAkKCcuYnRuLWdyb3VwLmVkaXQnKS5yZW1vdmVDbGFzcygnc3Itb25seScpO1xyXG4gICAgfSBlbHNlIHtcclxuICAgICAgICAkKCcuYnRuLWdyb3VwLmVkaXQnKS5hZGRDbGFzcygnc3Itb25seScpO1xyXG4gICAgfVxyXG5cclxuICAgICQoJ2lucHV0LmZvcm0tY2hlY2staW5wdXQnKS5vbignY2hhbmdlJywgZnVuY3Rpb24oKSB7XHJcbiAgICAgICAgJCgnaW5wdXQuZm9ybS1jaGVjay1pbnB1dCcpLm5vdCh0aGlzKS5wcm9wKCdjaGVja2VkJywgZmFsc2UpO1xyXG4gICAgICAgIGlmKGlzT25lQ2hlY2tib3hDaGVja2VkKFwiY2hlY2tzLWZpbGVzXCIpKXtcclxuICAgICAgICAgICAgJCgnLmJ0bi1ncm91cC5lZGl0JykucmVtb3ZlQ2xhc3MoJ3NyLW9ubHknKTtcclxuXHRcdH0gZWxzZSB7XHJcbiAgICAgICAgICAgICQoJy5idG4tZ3JvdXAuZWRpdCcpLmFkZENsYXNzKCdzci1vbmx5Jyk7XHJcblx0XHR9XHJcbiAgICB9KTtcclxuICAgICQoJ3AuZXJyb3InKS5hZGRDbGFzcygnYWxlcnQgYWxlcnQtZGFuZ2VyJykucHJlcGVuZFRvKCdkaXYubW9kX2ZpbGVzJyk7XHJcbn0pO1xyXG5cclxuLy9QYXNzIHRoZSBjaGVja2JveCBuYW1lIHRvIHRoZSBmdW5jdGlvblxyXG5mdW5jdGlvbiBnZXRDaGVja2VkQm94ZXMoY2hrYm94TmFtZSwgYXR0cmlidXRlKSB7XHJcbiAgdmFyIGNoZWNrYm94ZXMgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5TmFtZShjaGtib3hOYW1lKTtcclxuICB2YXIgY2hlY2tib3hlc0NoZWNrZWQgPSBbXTtcclxuICAvLyBsb29wIG92ZXIgdGhlbSBhbGxcclxuICBmb3IgKHZhciBpPTA7IGk8Y2hlY2tib3hlcy5sZW5ndGg7IGkrKykge1xyXG4gICAgIC8vIEFuZCBzdGljayB0aGUgY2hlY2tlZCBvbmVzIG9udG8gYW4gYXJyYXkuLi5cclxuICAgICBpZiAoY2hlY2tib3hlc1tpXS5jaGVja2VkKSB7XHJcbiAgICAgICAgY2hlY2tib3hlc0NoZWNrZWQucHVzaChjaGVja2JveGVzW2ldLmdldEF0dHJpYnV0ZShhdHRyaWJ1dGUpKTtcclxuICAgICB9XHJcbiAgfVxyXG4gIC8vIFJldHVybiB0aGUgYXJyYXkgaWYgaXQgaXMgbm9uLWVtcHR5LCBvciBudWxsXHJcbiAgcmV0dXJuIGNoZWNrYm94ZXNDaGVja2VkLmxlbmd0aCA+IDAgPyBjaGVja2JveGVzQ2hlY2tlZCA6IG51bGw7XHJcbn1cclxuXHJcbmZ1bmN0aW9uIGlzT25lQ2hlY2tib3hDaGVja2VkKGNoa2JveE5hbWUpIHtcclxuICAgIHZhciBjaGVja2JveGVzID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeU5hbWUoY2hrYm94TmFtZSk7XHJcbiAgICB2YXIgY2hlY2tib3hlc0NoZWNrZWQgPSBbXTtcclxuICAgIC8vIGxvb3Agb3ZlciB0aGVtIGFsbFxyXG4gICAgZm9yICh2YXIgaT0wOyBpPGNoZWNrYm94ZXMubGVuZ3RoOyBpKyspIHtcclxuICAgICAgICAvLyBBbmQgc3RpY2sgdGhlIGNoZWNrZWQgb25lcyBvbnRvIGFuIGFycmF5Li4uXHJcbiAgICAgICAgaWYgKGNoZWNrYm94ZXNbaV0uY2hlY2tlZCkge1xyXG4gICAgICAgICAgICByZXR1cm4gdHJ1ZTtcclxuICAgICAgICB9XHJcbiAgICB9XHJcbiAgICByZXR1cm4gZmFsc2U7XHJcbn1cclxuXHJcbmZ1bmN0aW9uIGNiQ2hhbmdlKG9iaikge1xyXG4gICAgdmFyIGNicyA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlOYW1lKFwiY2hlY2tzLWZpbGVzXCIpO1xyXG4gICAgZm9yICh2YXIgaSA9IDA7IGkgPCBjYnMubGVuZ3RoOyBpKyspIHtcclxuICAgICAgICBjYnNbaV0uY2hlY2tlZCA9IGZhbHNlO1xyXG4gICAgfVxyXG4gICAgb2JqLmNoZWNrZWQgPSB0cnVlO1xyXG59IiwiLypcclxuICogQGxpbmsgaHR0cHM6Ly9naXRodWIuY29tL1hSYXlMUC9sZWFybmluZy1jZW50ZXItYnVuZGxlXHJcbiAqIEBjb3B5cmlnaHQgQ29weXJpZ2h0IChjKSAyMDE4IE5pa2xhcyBMb29zIDxodHRwczovL2dpdGh1Yi5jb20vWFJheUxQPlxyXG4gKiBAbGljZW5zZSBHUEwtMy4wIDxodHRwczovL2dpdGh1Yi5jb20vWFJheUxQL2xlYXJuaW5nLWNlbnRlci1idW5kbGUvYmxvYi9tYXN0ZXIvTElDRU5TRT5cclxuICovXHJcblxyXG4kKGZ1bmN0aW9uKCkge1xyXG5cclxuXHQkKHdpbmRvdykucmVzaXplKGZ1bmN0aW9uKCl7XHJcblx0XHRzZXRTaWRlYmFySGVpZ2h0KCk7XHJcblx0XHRjZW50ZXJFbGVtZW50KCcjbG9naW5Cb3gnKTtcclxuXHR9KTtcclxuXHQkKGRvY3VtZW50KS5zY3JvbGwoZnVuY3Rpb24oKXtcclxuICAgICAgICBzZXRTaWRlYmFySGVpZ2h0KCk7XHJcblx0fSk7XHJcblx0XHJcblx0Y2VudGVyRWxlbWVudCgnI2xvZ2luQm94Jyk7XHJcbiAgICBzZXRTaWRlYmFySGVpZ2h0KCk7XHJcblxyXG4gICAgJCgnI3NpZGViYXJDb2xsYXBzZScpLm9uKCdjbGljaycsIGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAkKCcjbmF2LXNpZGViYXInKS50b2dnbGVDbGFzcygnZC1ub25lJyk7XHJcbiAgICAgICAgJCgnI21haW4nKS50b2dnbGVDbGFzcygnZC1ub25lJyk7XHJcbiAgICB9KTtcclxuICAgIFxyXG4gICAgJCgnLm1vZF9wZXJzb25hbERhdGEgLndpZGdldC10ZXh0JykuYWRkQ2xhc3MoXCJmb3JtLWdyb3VwXCIpO1xyXG4gICAgJCgnLm1vZF9wZXJzb25hbERhdGEgLndpZGdldC1wYXNzd29yZCcpLmFkZENsYXNzKFwiZm9ybS1ncm91cFwiKTtcclxuICAgICQoJy5tb2RfcGVyc29uYWxEYXRhIGlucHV0JykuYWRkQ2xhc3MoXCJmb3JtLWNvbnRyb2xcIik7XHJcbiAgICAkKCcubW9kX3BlcnNvbmFsRGF0YSBzZWxlY3QnKS5hZGRDbGFzcyhcImZvcm0tY29udHJvbFwiKTtcclxuXHRcclxuXHRcclxufSk7XHJcblxyXG5mdW5jdGlvbiBzZXRTaWRlYmFySGVpZ2h0KCl7XHJcblx0dmFyIGR5bmFtaWMgPSAkKGRvY3VtZW50KS5oZWlnaHQoKSAtICQoJyNuYXYtaW50ZXJuJykub3V0ZXJIZWlnaHQoKTtcclxuXHQgICBcclxuXHR2YXIgc2lkZWJhciA9ICQoJyNuYXYtc2lkZWJhcicpO1xyXG5cdHNpZGViYXIuY3NzKHtcclxuXHRcdCdtaW4taGVpZ2h0JzogZHluYW1pYyxcclxuXHQgICAgJ21heC1oZWlnaHQnOiBkeW5hbWljXHJcblx0fSk7XHJcbn1cclxuXHJcbmZ1bmN0aW9uIGNlbnRlckVsZW1lbnQoZWxlbWVudCl7IFxyXG5cclxuXHQvL0VycmVjaG5ldGUgV2VydGUgYW53ZW5kZW5cclxuXHQkKGVsZW1lbnQpLmNzcyh7XHJcbiAgICAgICAgJ21hcmdpbi10b3AnOiAoJCh3aW5kb3cpLmhlaWdodCgpIC0gJChlbGVtZW50KS5vdXRlckhlaWdodCgpKS8yXHJcblx0fSk7XHJcbn1cclxualF1ZXJ5KGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbigkKSB7XHRcdFxyXG5cdCQoXCJ0clwiKS5jbGljayhmdW5jdGlvbigpIHtcclxuXHRcdFxyXG5cdFx0aWYgKCQodGhpcykuZGF0YShcImhyZWZcIikpe1xyXG5cdFx0XHR3aW5kb3cubG9jYXRpb24gPSAkKHRoaXMpLmRhdGEoXCJocmVmXCIpO1xyXG5cdFx0fVxyXG5cdFx0XHJcblx0fSk7XHJcbn0pOyIsIiQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCl7XHJcbiAgICB2YXIgcGVyY2VudCA9ICQoJy5wcm9ncmVzcy1iYXInKS5hdHRyKFwiYXJpYS12YWx1ZW5vd1wiKTtcclxuICAgIHZhciBkZWdyZWUgPSAkKCcucHJvZ3Jlc3MtY2lyY2xlJykuYXR0cihcImFyaWEtZGVncmVlXCIpO1xyXG4gICAgaWYgKGRlZ3JlZSA8IDE4MCkge1xyXG4gICAgICAgICQoJy5wcm9ncmVzcy1yaWdodCAucHJvZ3Jlc3MtYmFyICcpLmNzcyhcInRyYW5zZm9ybVwiLCBcInJvdGF0ZShcIiArIChkZWdyZWUpICtcImRlZylcIik7XHJcbiAgICB9IGVsc2Uge1xyXG4gICAgICAgICQoJy5wcm9ncmVzcy1yaWdodCAucHJvZ3Jlc3MtYmFyICcpLmNzcyhcInRyYW5zZm9ybVwiLCBcInJvdGF0ZSgxODBkZWcpXCIpO1xyXG4gICAgICAgICQoJy5wcm9ncmVzcy1sZWZ0IC5wcm9ncmVzcy1iYXIgJykuY3NzKFwidHJhbnNmb3JtXCIsIFwicm90YXRlKFwiICsgKGRlZ3JlZSAtIDE4MCkgKyBcImRlZylcIik7XHJcbiAgICB9XHJcbn0pO1xyXG4iXX0=
