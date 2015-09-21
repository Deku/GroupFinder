$(document).ready( function() {
    
    function send_chat_message() {
        var ref = $('#btn_send_msg').data('ref');
        var msg = $('#chat-input').val();

        var data = { ref: ref, message: msg };
        $.ajax({
             type: "POST",
             dataType: "json",
             url: SITE_URL + '/conversations/sendChatMessage/',
             data: $.param(data),
             beforeSend: function(){
                 $('#send_status').addClass('fa-spinner fa-pulse');
             },
             success: function(response) {
                 $('.conversation-messages').append(response.extra['html']);
                 $('#send_status').removeClass('fa-spinner fa-pulse');
                 $('#chat-input').val('');
                 $('#chat-input').focus();
             },
             error: function(response) {
                 alert(response.error);
                 $('#send_status').removeClass('fa-spinner fa-pulse');
             }
        });
    }
    
    $('#btn_send_msg').click( function (e) {
        e.preventDefault();
        
        send_chat_message();
    });
    
    $('#chat-input').on('keypress', function(e) {
        if (e.charCode != 13)
            return;
        
        e.preventDefault();
        send_chat_message();
    });
});