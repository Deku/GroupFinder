$('a.remove_friend').click( function (e) {
        e.preventDefault();
        if (confirm('¿Seguro que deseas eliminar a esta persona de tu lista de amigos?')) {
            var origin = $(event.target);
            var rID = origin.data('ref');
            var sel = '#' + origin.data('u');

            var data = { ref: rID };
            $.ajax({
                 type: "POST",
                 dataType: "json",
                 url: SITE_URL + '/users/remove-friend/',
                 data: $.param(data),
                 beforeSend: function(){
                     display_notification(sel, 'update');
                 },
                 success: function(response) {
                     display_notification(sel, 'success', 'Eliminado correctamente');
                     $('#friends_'+rID).fadeOut(200).remove();
                 },
                 error: function(response) {
                     display_notification(sel, 'error', response.error_message);
                 }
             });
       }
    });
    
function send_message(el) {
   var ref = $('#modal-msg input#ref').val();
   var msg = $('#modal-msg textarea#m').val();

   var sel = "#message-notification";
   var data = { ref: ref, message: msg };
   $.ajax({
        type: "POST",
        dataType: "json",
        url: SITE_URL + '/conversations/send-message/',
        data: $.param(data),
        beforeSend: function(){
            display_notification(sel, 'update');
        },
        success: function(response) {
            $('#modal-msg .modal-body').empty();
            display_notification('#modal-msg .modal-body', 'success', response.success_message);
        },
        error: function(response) {
            display_notification(sel, 'error', response.error_message);
        }
    });
};

$('#modal-msg').on('show.bs.modal', function(e) {
    var origin = $(event.target);
    $('#modal-msg button#send_message').unbind('click');
    $('#modal-msg input#ref').val(origin.data('ref'));
    $('#modal-msg span#user-name').html(origin.data('name'));

    $('#send_message').click( function (e) {
        event.preventDefault();

        send_message();
    });
});