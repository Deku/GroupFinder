$(document).ready(function (event) {
 
    /*
     * Edicion de datos
     */
    $('#edit-profile-form').submit( function(event) {
        event.preventDefault();
        var data = { 
            editName: $('#editName').val(),
            editTitle: $('#editTitle').val(),
            editBirthday: $('#editBirthday').val(),
            editCountry: $('#editCountry').val(),
            editAbout: $('#editAbout').val()
        };

        var sel = '#edit-status';

        $.ajax({
            type: "POST",
            dataType: "json",
            url: SITE_URL + '/users/edit-profile',
            data: $.param(data),
            beforeSend: function(){
                display_notification(sel, 'update', 'Actualizando perfil...');
            },
            success: function(response) {
                display_notification(sel, 'success', 'Perfil actualizado!');
            },
            error: function() {
                display_notification(sel, 'error', 'Ha ocurrido un error. Por favor inténtalo nuevamente');
            }
        });
    });
    
    /*
     * Edicion de foto de perfil - Subir desde el equipo
     */
    $("#profile-upload-form").submit(function(event) {
        event.preventDefault();
        
        var sel = '#upload-status';
        console.log($('#image-file').files);
        $.ajax({
            url: SITE_URL + "/pictures/upload",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function(){
                display_notification(sel, 'update', 'Actualizando perfil...');
            },
            success: function(data) {
                display_notification(sel, 'success', 'Perfil actualizado!');
                update_current_photo(data);
            },
            error: function() {
                display_notification(sel, 'error', 'Ha ocurrido un error. Por favor inténtalo nuevamente');
            }
        });
    });

    // Vista previa de la foto a subir
    $('#btn-upload-from-desktop').click(function (e) {
        e.preventDefault();
        $('#image-file').click();
    });
    
    $(function() {
        $("#image-file").change(function() {
            var file = this.files[0];
            var imagefile = file.type;
            // Validar que sea un formato permitido
            var match = ["image/jpeg", "image/png", "image/jpg", "image/gif"];
            
            if(!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2])))
            {
                $('#image-preview').attr('src', BASE_URL + 'images/preview.jpg');
                $("#message").html("<p>Por favor selecciona un archivo de imagen válido</p>"+"<h4>Nota</h4>"+"<span>Sólo se permiten archivos con las extensiones .jpg y .png</span>");
                return false;
            }
            else
            {
                var reader = new FileReader();
                reader.onload = imageIsLoaded;
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
    
    function imageIsLoaded(event) {
        $('#image-preview').attr('src', event.target.result);
        $('a[href="#upload-photo"]').data('url', event.target.result);
    };
    
    /*
     * Edicion de foto de perfil - Usar Gravatar
     */
    $("#profile-use-gravatar").click(function(event) {
        event.preventDefault();
        
        var sel = '#gravatar-status';
        
        $.ajax({
            url: SITE_URL + "/pictures/use-gravatar",
            type: "POST",
            beforeSend: function(){
                display_notification(sel, 'update', 'Actualizando perfil...');
            },
            success: function(data) {
                display_notification(sel, 'success', 'Perfil actualizado!');
                update_current_photo(data);
            },
            error: function() {
                display_notification(sel, 'error', 'Ha ocurrido un error. Por favor inténtalo nuevamente');
            }
        });
    });
    
    /*
     * Cambio de contraseña
     */
    $('#change-pass-form').submit( function(event) {
        event.preventDefault();
        
        var data = {
            oldPass: $('#old-password').val(),
            newPass: $('#new-password').val(),
            verifNewPass: $('#v-new-password').val()
        };
        
        var sel = '#changepass-status';
        
        $.ajax({
            type: "POST",
            dataType: "json",
            url: SITE_URL + '/accounts/change-password',
            data: $.param(data),
            beforeSend: function(){
                display_notification(sel, 'update', 'Cambiando contraseña...');
            },
            success: function(response) {
                display_notification(sel, 'success', 'Contraseña cambiada correctamente');
            },
            error: function(response) {
                display_notification(sel, 'error', 'Error al intentar cambiar la contraseña. ' + response.error);
            }
        });
    });
    
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var active = $(e.target); // newly activated tab
        console.log("triggered " + active.data('url'));
        $('#image-preview').attr('src', active.data('url'));
    });
});