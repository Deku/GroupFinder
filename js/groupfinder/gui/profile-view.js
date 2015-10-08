$(document).ready( function() {
    $('#send_friend_request').click( function(e) {
       e.preventDefault();
       
       var ref_id = $(e.target).data('ref');
       var sel = "#send_friend_request";
       
       $.ajax({
            type: "POST",
            dataType: "json",
            url: SITE_URL + '/users/send-friend-request/' + ref_id,
            success: function(response) {
                display_notification(sel, 'success', response.success_message);
            },
            error: function(response) {
                display_notification(sel, 'error', response.error_message);
            }
        });
    });
});
