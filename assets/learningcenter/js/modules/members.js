let Routing = require('../../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router');
let Routes = require('../fos_js_routes.json');
var xhr = null;
Routing.setRoutingData(Routes);

members = {
    getUsers: function (string, project_id) {

        //abort previous ajax request to save resources
        if( xhr != null ) {
            xhr.abort();
            xhr = null;
        }

        let loading = "<tr id='loadingDiv'><td></td><td></td><td><i class='fas fa-spinner fa-3x fa-spin'></i></td><td></td></tr>";
        let usertable = $('#usertable_body');

        xhr = $.ajax({
            url: Routing.generate('lc_projects_members_get', {id: project_id}),
            data: {phrase: string},
            type: 'POST',
            dataType: 'json',
            async: true,

            beforeSend: function () {
                usertable.html('').append(loading);
            },

            success: function (data, status) {
                let projectMembers = data;

                usertable.html('');

                for (let i = 0; i < projectMembers.length; i++) {
                    let projectMember = projectMembers[i];
                    let route = Routing.generate('lc_projects_member_add', {id: projectMember['project']['id'], member_id: projectMember['member']['id']});
                    var add;

                    if (projectMember['projectMember']) {
                        add = '<td><i class="fas fa-check text-success text-center"></td>';
                    } else {
                        add = '<td class="add clickable" data-user="'+ projectMember['member']['id'] +'"><a href="' + route + '"><i class="fas fa-plus text-success text-center"></i></a></td>';
                    }
                    let e = $('<tr><td id="avatar"></td><td id = "firstname"></td><td id = "lastname"></td>' + add + '</tr>');

                    $('#avatar', e).html('<img src="' + projectMember['memberManagement']['avatar'] +'" style="border-radius: 100%" width="36" height="36">');
                    $('#firstname', e).html(projectMember['member']['firstname']);
                    $('#lastname', e).html(projectMember['member']['lastname']);
                    usertable.append(e);
                }
            }
        });
    },
};
