members = {
    searchUsers: function (value, url, group){
        if (value.length >= 3 || value == '#') {
        //ajax request
            $.ajax({
                url: url,
                data: {phrase: value, group: group },
                type: 'POST',
                dataType: 'json',
                async: true,

                success: function (data, status) {
                    $('#usertable_body').html('');

                    for (i = 0; i < data.length; i++) {
                        member = data[i];
                        if (member['isMember']) {
                            var add = '<td><i class="fas fa-check text-success text-center"></td>';
                        } else {
                            var add = '<td class="add clickable" data-user="'+ member['id'] +'"><i class="fas fa-plus text-success text-center"></td>';
                        }
                        var e = $('<tr><td id="avatar"></td><td id = "firstname"></td><td id = "lastname"></td>' + add + '</tr>');

                        $('#avatar', e).html('<img src="' + member['avatar'] +'" style="border-radius: 100%" width="36" height="36">');
                        $('#firstname', e).html(member['firstname']);
                        $('#lastname', e).html(member['lastname']);
                        $('#usertable_body').append(e);
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    alert('Ajax request failed.');
                }
            });
        }
    },

    addUsers: function (user, group, url) {
        $.ajax({
            url: url,
            data: {user: user, group: group},
            type: 'POST',
            dataType: 'json',
            async: true,

            success: function (data, status) {

            },
            error: function (xhr, textStatus, errorThrown) {
                alert('Ajax request failed.');
            }
        })
    },
};
