$(document).ready( function() {
    
    $('.reward__button-select').click(function (e) {
        var target = $(e.target).data('reward-id');
        $('.reward--selected').removeClass('reward--selected');
        $(target).addClass('reward--selected');
    });
    
    /*
     * Asignar eventos al modal de postulacion
     */
    $('#modal-application').on('show.bs.modal', function (event) {
        var origin = $(event.relatedTarget);
        $('#modal-application button.save').unbind('click');
        $('#modal-application a.delete').unbind('click');

        $('#modal-application #rid').val(origin.data('rid'));
        $('#modal-application textarea').val("");
        
        $('#modal-application #btn-confirm').on('click', function(event) {
            event.preventDefault();
            
            var data = { action: 'add', role: origin.data('rid'), msg: $('#modal-application #application-message').val() };
            var sel = '#application-notification';

            $.ajax({
                type: "POST",
                dataType: "json",
                url: SITE_URL + '/projects/applications/'+pID,
                data: $.param(data),
                beforeSend: function(){
                    display_notification(sel, 'update');
                },
                success: function() {
                    display_notification(sel, 'success');
                },
                error: function(response) {
                    display_notification(sel, 'error', response.error_message);
                },
                done: function() {
                    $('#modal-application #btn-cancel').html("Cerrar");
                }
            });
        });
    });
});


