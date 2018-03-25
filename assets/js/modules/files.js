/*
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

$(document).ready(function(){
	//delete files
	$(".btn-delete").click(function(){
		var checks = getCheckedBoxes("checks-files", "id");
		var button = $("[name='delete_file[id]']");
		button.val(checks);

	});

	//share files
	$(".btn-share").click(function(){
		var checks = getCheckedBoxes("checks-files", "id");
		var button = $("[name='share_file[id]']");
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