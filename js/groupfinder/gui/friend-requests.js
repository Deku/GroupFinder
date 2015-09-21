$(document).ready( function() {
    /*
     * Aceptar amistad
     */
    $('button.accept_request').click( function (e) {
       e.preventDefault();
       var origin = $(event.target);
       var rqID = origin.data('rq');
       var sel = '#' + origin.data('u');
       
       var data = { action: "accept", rq: rqID };
       $.ajax({
            type: "POST",
            dataType: "json",
            url: SITE_URL + '/users/processRequest/',
            data: $.param(data),
            beforeSend: function(){
                $('#req_info_'+rqID).hide();
                display_notification(sel, 'update');
            },
            success: function(response) {
                display_notification(sel, 'success', response.success_message);
                $('#requests_'+rqID).fadeOut(200).remove();
            },
            error: function(response) {
                display_notification(sel, 'error', response.error_message);
                $(sel).hide();
                $('#req_info_'+rqID).fadeOut(200).show();
            }
        });
    });
    
    /*
     * Rechazar amistad
     */
    $('button.reject_request').click( function (e) {
       e.preventDefault();
       var origin = $(event.target);
       var rqID = origin.data('rq');
       var sel = '#' + origin.data('u');
       
       var data = { action: "reject", rq: rqID };
       $.ajax({
            type: "POST",
            dataType: "json",
            url: SITE_URL + '/users/processRequest/',
            data: $.param(data),
            beforeSend: function(){
                $('#req_info_'+rqID).hide();
                display_notification(sel, 'update');
            },
            success: function(response) {
                display_notification(sel, 'success', response.success_message);
                $('#requests_'+rqID).fadeOut(200).remove();
            },
            error: function(response) {
                display_notification(sel, 'error', response.error_message);
                $(sel).hide();
                $('#req_info_'+rqID).fadeOut(200).show();
            }
        });
    });
});