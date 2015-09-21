$(document).ready( function(event) {
    $('#comment-form').submit(function(event) {
        event.preventDefault();
        var data = { ref : $('#ref').val() , origin : $('#origin').val() , message : $('#message').val() };
        var spinner = $('<i class="fa fa-spinner fa-spin"></i>');

        $.ajax({
            type: "POST",
            dataType: "json",
            url: SITE_URL + '/comments/post',
            data: $.param(data),
            beforeSend: function(){
                $('#comment-submit').prepend(spinner.fadeIn());
            },
            success: function(response) {
                $('#comments').append($(response).fadeIn());
                $('#comment-submit').html($('<span></span>').html('Enviar').fadeIn());
                $('#message').val('');
            },
            error: function(response) {
                $('#comment-submit').html($('<span>Enviar</span>').fadeIn());
            }
        });

        return false;
    });
});

/*
 * Comments
 */
function load_comments(t, i, l) {
    var data = {type: t , id: i };
    $.ajax({
        type: "POST",
        dataType: "json",
        url: SITE_URL + '/comments/get',
        data: $.param(data),
        beforeSend: function(){
            $('#comments').prepend('<i class="fa fa-spinner fa-spin"></i> ');
        },
        success: function(response) {
            $('#comments-title').text((response.length || '0') + ' comentario' + (response.length && response.length === 1 ? '' : 's'));
            $('#comments').empty();
            
            if (response.result != false) {
                for (var i = 0; i < response.length; i++) {
                    $('#comments').append(response[i]);
                }

                $("time.timeago").each(function() {
                    $(this).html($.timeago($(this).attr("datetime")));
                });
            } else {
                $('#comments').append("<h2>No hay comentarios aún</h2>");
            }
        },
        error: function(response) {
            $('#comments-title').text('0 comentarios');
        }
    });
}

function reply(obj) {
    $('html, body').animate({
            scrollTop: $("#message").offset().top
    }, 500);
    $('#message').val('@'+$(obj).attr('data-ref')+' ').focus();
    
    return false;
};