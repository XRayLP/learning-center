/*
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

let Routing = require('../../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router');
let Routes = require('../js_routes.json');
let loading = "<tr id='loadingDiv'><td></td><td></td><td><i class='fas fa-spinner fa-3x fa-spin'></i></td><td></td></tr>";
Routing.setRoutingData(Routes);

$(document).ready(function(){

    //refresh Toolbar
    refreshToolbar();
    $("input[name='checks-files']").click(function () {
        refreshToolbar();
    });

	//delete files
	$(".btn-delete").click(function(){
		var checks = getCheckedBoxes("checks-files", "id");
		var button = $("#form_delete_file input#id");
		button.val(checks);
	});

    //delete files
    $(".btn-download").click(function(){
        var checks = getCheckedBoxes("checks-files", "id");
        var button = $("#form_download_file input#id");
        button.val(checks);
    });

	//share files
    $(".btn-share").click(function(){
        var checks = getCheckedBoxes("checks-files", "id");
        var button = $("#form_share_file input#file");
        button.val(checks);
        getShares(checks);

    });

    //share files
    $(".btn-remove-share").click(function(){
        console.log('TEST');
        var checks = getCheckedBoxes("checks-files", "id");
        var share = this.attr('data-share-id');

        removeShare(checks, share);

    });


    //share files
    $(".btn-edit-share").click(function(){
        var checks = getCheckedBoxes("checks-files", "id");
        var button = $("#form_update_share_file input#file");
        button.val(checks);

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

function refreshToolbar()
{
    let toolbar = $("#filemanager-toolbar");
    let checks = getCheckedBoxes("checks-files", "id");
    if (checks === null) {
        $(".btn-share").addClass("disabled");
        $(".btn-download").addClass("disabled");
        $(".btn-delete").addClass("disabled");
    } else {
        $(".btn-share").removeClass("disabled");
        $(".btn-download").removeClass("disabled");
        $(".btn-delete").removeClass("disabled");
    }
}



function generateShareView(file_ids) {
    let shares = getShares(file_ids);
    let shareDiv = $('.files-shares');

    shareDiv.html('');
    for (i = 0; i < shares.length; i++) {
        var share = shares[i];
        shareDiv.append("<div class='share'>"+ share['name'] +"<button class='btn btn-danger btn-remove-share' data-share-id='" + share['id'] + "'>Remove</button></div>");
    }
};

function getShares(file_ids) {
    $.ajax({
        url: Routing.generate('lc_shares'),
        data: {file_ids: file_ids},
        type: 'POST',
        dataType: 'json',
        async: true,

        beforeSend: function () {
            $('.files-shares').html('');
            $('.files-shares').append(loading);
        },

        success: function (data) {
            let shares = data;
            let shareDiv = $('.files-shares');

            shareDiv.html('');
            for (i = 0; i < shares.length; i++) {
                var share = shares[i];
                shareDiv.append("<div class='share'>"+ share['name'] +"<button class='btn btn-danger btn-remove-share' data-share-id='" + share['id'] + "'>Remove</button></div>");
            }
            shareDiv.append("<button class='btn btn-success btn-share-add' type='button' data-toggle='collapse' data-target='#collapseAddShare' aria-expanded='false' aria-controls='collapseAddShare'>Add</button>");

            $(".btn-remove-share").on("click", function () {
                var checks = getCheckedBoxes("checks-files", "id");
                var share = $(this).attr('data-share-id');

                removeShare(checks, share);
            });
        }

    });
};

function removeShare(file_ids, share_id) {
    $.ajax({
        url: Routing.generate('lc_shares_remove'),
        data: {file_ids: file_ids, share_id: share_id},
        type: 'POST',
        dataType: 'json',
        async: true,

        beforeSend: function () {
            $('.files-shares').html('');
            $('.files-shares').append(loading);
        },

        success: function (data) {
            getShares(file_ids);
        }

    })
};
