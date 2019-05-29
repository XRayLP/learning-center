$(document).ready(function() {
    $('.sidenav').sidenav();
    
    $('.sidenav.account').sidenav({
        edge: 'right'
    });

    $('.dropdown-trigger').dropdown({
        alignment:'right'
    });
    
    $('.modal').modal();
    
    $('select').formSelect();
    
    $('.current').addClass('active');

});