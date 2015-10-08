$(document).ready(function () {
    $('#code-preview').click(function () {
        $('#code-preview').select();
    });
    
    $('#btn-next-step').click(function () {
        swal();
    });
    
    $('#form-funding-goal').submit(function (e) {
        e.preventDefault();
        
        $.ajax({
            type: "POST",
            dataType: "json",
            data: $.param($('#form-funding-goal').serializeArray()) + '&pid=' + $('#pid').val(),
            url: SITE_URL + '/projects/async/saveFundingGoal',
            success: function () {
                swal('Guardado!', 'Meta guardada', 'success');
            },
            error: function(response) {
                swal('Error!', response.error_message, 'error');
            }
        });
    });

    $('#info-summary').on('keyup', function () {
        $('#info-summary-chars').html($('#info-summary').val().length);
    });
    
    $('#add-rewards-notes').on('keyup', function () {
        $('#add-rewards-notes-chars').html($('#add-rewards-notes').val().length);
    });
    
    $('#modal-rewards-notes').on('keyup', function () {
        $('#modal-rewards-notes-chars').html($('#modal-rewards-notes').val().length);
    });
    
    $('.btn-display-add-box').click(function (e) {
        e.preventDefault();
        var target = $(e.target).data('target');
        console.log(target);
        if ($(target).is(':visible')) {
            $(target).hide();
        } else {
            $(target).show();
        }
    });

    /*
     * Guardar informacion general
     */
    $('.save_general_js').click(function (e) {
        e.preventDefault();
        var title = $('#info-title').val();
        var cat = $('#info-category').val();
        var ddate = $('#info-due-date').val();
        var summary = $('#info-summary').val();
        var problem = $('#info-problem').val();
        var solution = $('#info-solution').val();
        var target = $('#info-target').val();

        var data = {segment: 'general', title: title, category: cat, ddate: ddate, summary: summary, problem: problem, solution: solution, target: target};
        var sel = '#save-general-notification';

        $.ajax({
            type: "POST",
            dataType: "json",
            url: SITE_URL + '/projects/save/' + pID,
            data: $.param(data),
            beforeSend: function () {
                display_notification(sel, 'update');
            },
            success: function (response) {
                display_notification(sel, 'success');
            },
            error: function (response) {
                display_notification(sel, 'error', response.error_message);
            }
        });
    });

    /*
     * Guardar informacion extra
     */
    $('.save_extra_js').click(function (e) {
        e.preventDefault();
        var extra = tinymce.activeEditor.getContent();

        var data = {segment: 'extrainfo', extra: extra};
        var sel = '#save-extra-notification';

        $.ajax({
            type: "POST",
            dataType: "json",
            url: SITE_URL + '/projects/save/' + pID,
            data: $.param(data),
            beforeSend: function () {
                display_notification(sel, 'update');
            },
            success: function (response) {
                display_notification(sel, 'success');
            },
            error: function (response) {
                display_notification(sel, 'error', response.error_message);
            }
        });
    });

    /*
     * Agregar una nueva vacante
     */
    $('#form-add-role').submit(function (e) {
        e.preventDefault();

        var sel = '#add-vacant-notification';

        $.ajax({
            type: "POST",
            dataType: "json",
            url: SITE_URL + '/projects/async/add-role',
            data: $.param($('#form-add-role').serializeArray()) + '&pid=' + $('#pid').val(),
            beforeSend: function () {
                display_notification(sel, 'update');
            },
            success: function (response) {
                display_notification(sel, 'success');
            },
            error: function (response) {
                display_notification(sel, 'error', response.error_message);
            }
        });
    });

    /*
     * Agregar una nuevo recurso
     */
    $('#button-add-resource').click(function (e) {
        e.preventDefault();
        var name = $('#add-resource-name').val();
        var cost = $('#add-resource-cost').val();
        var amount = $('#add-resource-amount').val();
        var description = $('#add-resource-description').val();
        var required = $('#add-resource-required').val();

        var data = {action: 'add', name: name, cost: cost, amount: amount, description: description, required: required};
        var sel = '#add-resource-notification';

        $.ajax({
            type: "POST",
            dataType: "json",
            url: SITE_URL + '/projects/resources/' + pID,
            data: $.param(data),
            beforeSend: function () {
                display_notification(sel, 'update');
            },
            success: function (response) {
                display_notification(sel, 'success');
            },
            error: function (response) {
                display_notification(sel, 'error', response.error_message);
            }
        });
    });

    /*
     * Agregar una nueva pregunta frecuente
     */
    $('#button-add-faq').click(function (e) {
        e.preventDefault();
        var quest = $('#add-faq-question').val();
        var answ = $('#add-faq-answer').val();

        var data = {action: 'add', question: quest, answer: answ};
        var sel = '#add-faq-notification';

        $.ajax({
            type: "POST",
            dataType: "json",
            url: SITE_URL + '/projects/faqcreate/' + pID,
            data: $.param(data),
            beforeSend: function () {
                display_notification(sel, 'update');
            },
            success: function (response) {
                display_notification(sel, 'success');
                var _li = document.createElement('li');
                var _h3 = document.createElement('h3');
                var _p = document.createElement('p');
                $(_li).data('q', quest);
                $(_li).data('a', answ);
                $(_h3).text(quest);
                $(_p).text(answ);
                $(_li).append(_h3);
                $(_li).append(_p);
                $('.faq-list').append($(_li).slideDown());

            },
            error: function (response) {
                display_notification(sel, 'error', response.error_message);
            }
        });
    });

    /*
     * Asignar eventos al modal
     */
    $('#modal-role').on('show.bs.modal', function (event) {
        var origin = $(event.relatedTarget);
        $('#modal-role button.save').unbind('click');
        $('#modal-role a.delete').unbind('click');
        Vacants.clean_modal();

        $('#modal-role input#r').val(origin.data('r'));
        $('#modal-role input#a').val(origin.data('a'));
        $('#modal-role textarea#d').val(origin.data('d'));
        $('#modal-role input#rid').val(origin.data('rid'));

        $('#modal-role button.save').click(function (event) {
            event.preventDefault();
            Vacants.edit_role(origin[0], origin.data('rid'));
        });
        
        $('#modal-role a.delete').click(function (event) {
            event.preventDefault();
            Vacants.delete_role(origin[0], origin.data('rid'));
        });
        $('#modal-role a.delete').show();
    });

    /*
     * Aceptar postulacion
     */
    $('.accept_application').click(function (e) {
        e.preventDefault();
        var origin = $(event.target);
        var rqID = origin.data('rq');
        var sel = '#' + origin.data('u');

        var data = {action: "accept", appID: rqID};
        $.ajax({
            type: "POST",
            dataType: "json",
            url: SITE_URL + '/projects/applications/' + pID,
            data: $.param(data),
            beforeSend: function () {
                $('#app_info_' + rqID).hide();
                display_notification(sel, 'update');
            },
            success: function (response) {
                display_notification(sel, 'success', response.success_message);
                $('#applications_' + rqID).fadeOut(200).remove();
                $('#vacants-tab-team').append(response.extra['el'])
            },
            error: function (response) {
                display_notification(sel, 'error', response.error_message);
                $(sel).hide();
                $('#app_info_' + rqID).fadeOut(200).show();
            }
        });
    });

    /*
     * Rechazar postulacion
     */
    $('.reject_application').click(function (e) {
        e.preventDefault();
        var origin = $(event.target);
        var rqID = origin.data('rq');
        var sel = '#' + origin.data('u');

        var data = {action: "reject", appID: rqID};
        $.ajax({
            type: "POST",
            dataType: "json",
            url: SITE_URL + '/projects/applications/' + pID,
            data: $.param(data),
            beforeSend: function () {
                $('#app_info_' + rqID).hide();
                display_notification(sel, 'update');
            },
            success: function (response) {
                display_notification(sel, 'success', response.success_message);
                $('#applications_' + rqID).fadeOut(200).remove();
            },
            error: function (response) {
                display_notification(sel, 'error', response.error_message);
                $(sel).hide();
                $('#app_info_' + rqID).fadeOut(200).show();
            }
        });
    });

    /*
     * Eliminar persona del equipo
     */

    $('.remove_member_js').click(function (e) {
        e.preventDefault();
        if (confirm('Seguro que deseas desvincular a esta persona de tu equipo?')) {
            var origin = $(event.target);
            var uID = origin.data('id');
            var sel = '#' + origin.data('u');

            var data = {action: "remove", uID: uID};
            $.ajax({
                type: "POST",
                dataType: "json",
                url: SITE_URL + '/projects/members/' + pID,
                data: $.param(data),
                beforeSend: function () {
                    display_notification(sel, 'update');
                },
                success: function (response) {
                    display_notification(sel, 'success', 'Desvinculado correctamente');
                    $('#members_' + uID).fadeOut(200).remove();
                },
                error: function (response) {
                    display_notification(sel, 'error', response.error_message);
                }
            });
        }
    });
    
    /*
     * Cambiar el modo de financiamiento
     */
    $('#funding-mode').on('change', function() {
        var sel = '#notification-funding-mode';
        var mode = $(this).val();
        var data = { pid: $('#pid').val(), mode: mode};
        
        $.ajax({
            type: "POST",
            dataType: "json",
            url: SITE_URL + '/projects/async/saveFundingMode',
            data: $.param(data),
            success: function() {
                display_notification(sel, 'success', 'Guardado');
                
                $('#funding-tab-content .tab-pane').removeClass('active');
        
                if (mode == 1) {
                    $('#funding-private').addClass('active');
                } else if (mode == 2) {
                    $('#funding-gov').addClass('active');
                } else if (mode  == 3) {
                    $('#funding-community').addClass('active');
                }
            },
            error: function(response) {
                swal("Error!", response.responseJSON.error_message, "error");
            }
        });
    });
    
    /*
     * Guardar datos de la cuenta bancaria
     */
    $('#btn-funding-bank-save').click(function () {
        var sel = '#notification-funding-bank-acc';

        $.ajax({
            type: "POST",
            dataType: "json",
            url: SITE_URL + '/projects/async/saveBankData',
            data: $.param($('#form-bank-data').serializeArray()) + '&pid=' + $('#pid').val(),
            success: function () {
                swal('Guardado!', 'Se ha guardado la información de tu cuenta bancaria', 'success');
            },
            error: function (response) {
                swal('Error!', response.responseJSON.error_message, 'error');
            }
        });
    });
    
    /*
     * Activar/desactivar recompensas 
     */
    $('#funding-rewards-activate').on('change', function() {
        var sel = '#notification-funding-rewards';
        var data = { pid: $('#pid').val(), activate: ($('#funding-rewards-activate').is(":checked")) }
        
        $.ajax({
            type: "POST",
            dataType: "json",
            url: SITE_URL + '/projects/async/saveRewardsActivate',
            data: $.param(data),
            success: function() {
                display_notification(sel, 'success', 'Guardado');
                
                if ($('#funding-rewards-activate').is(":checked")) {
                    $('#funding-rewards').show();
                } else {
                    $('#funding-rewards').hide();
                }
            },
            error: function (response) {
                display_notification(sel, 'error', response.responseJSON.error_message);
            }
        });
    });
    
    /*
     * Agregar recompensa
     */
    $('#btn-add-rewards').click(function () {
        var sel = '#notification-funding-rewards';
        
        $.ajax({
            type: "POST",
            dataType: "json",
            url: SITE_URL + '/projects/async/addReward',
            data: $.param($('#form-add-rewards').serializeArray()) + '&pid=' + $('#pid').val(),
            success: function(response) {
                display_notification(sel, 'success', response.success_message);
                
                if (!$('#table-funding-rewards').is(':visible')) {
                    $('#table-funding-rewards').show();
                }
                
                $('#table-funding-rewards').append($(response.extra['tr']).slideDown());
            },
            error: function (response) {
                display_notification(sel, 'error', response.error_message);
            }
        });
    });
});

function Vacants() {}

/*
 * Crea un elemento y lo adjunta al wrapper
 * @param int rid
 * @param string role
 * @param int amount
 * @param string description
 */
Vacants.prototype.load = function (rid, role, amount, description) {
    var item = document.createElement('span');
    this.config_role_el($(item), role, amount, description);
    $(item).attr('data-rid', rid);
    $('#vacants-wrapper').append($(item).fadeIn());
}

Vacants.prototype.edit_role = function (obj, rid) {
    var role = $('#modal-role input#r').val();
    var amount = $('#modal-role input#a').val();
    var description = $('#modal-role textarea#d').val();

    if (role === '' && amount <= 0 && description === '') {
        alert('Valores no vÃ¡lidos.');
        return;
    }

    var data = {action: 'edit', role_id: rid, role: role, amount: amount, description: description};
    var sel = '#modal-role-notification';

    $.ajax({
        type: "POST",
        dataType: "json",
        url: SITE_URL + '/projects/async/edit-role',
        data: $.param(data) + '&pid=' + $('#pid').val(),
        beforeSend: function () {
            display_notification(sel, 'update')
        },
        success: function (response) {
            display_notification(sel, 'success');
            this.config_role_el($(obj), role, amount, description);
        },
        error: function (response) {
            display_notification(sel, 'error');
        }
    });

}

Vacants.prototype.delete_role = function (obj, role) {
    swal({
        title: "¿Seguro que deseas eliminar este rol?",
        text: "Para poder eliminarlo no debe haber ningunn miembro asignado a este rol",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Si, eliminalo",
        closeOnConfirm: false,
        showLoaderOnConfirm: true
    }, function () {

        if (!role || role === '') {
            swal('Error!', 'Rol invalido', 'error');
            return;
        }

        var data = {action: 'remove', role: role}
        var sel = '#modal-role-notification';

        $.ajax({
            type: "POST",
            dataType: "json",
            url: SITE_URL + '/projects/async/delete-role',
            data: $.param(data) + '&pid=' + $('#pid').val(),
            success: function (response) {
                swal("Eliminado!", "El rol ha sido eliminado correctamente.", "success");
                $(obj).fadeOut().remove();
            },
            error: function (response) {
                swal("Error!", response.responseJSON.error_message, "error");
            }
        });
    });
}

Vacants.prototype.clean_modal = function () {
    $('#modal-role input[type=text]').val(null);
    $('#modal-role input[type=number]').val(null);
    $('#modal-role textarea').val('');
    $('#modal-role #modal-role-notification').html('');
    $('#modal-role #modal-role-notification').attr('class', '');
}

Vacants.prototype.config_role_el = function (el, role, amount, description) {
    el.html('<label>' + amount + ' ' + role + '</label>');
    el.attr('data-toggle', 'modal');
    el.attr('data-target', '#modal-role');
    el.attr('data-r', role);
    el.attr('data-a', amount);
    el.attr('data-d', description);
    el.attr('class', 'vacant-item');
}

window.Vacants = new Vacants();