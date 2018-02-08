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