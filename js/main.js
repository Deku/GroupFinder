jQuery(function ($) {
    'use strict',
            //Initiat WOW JS
            new WOW().init();

    // Inicio preloader
    $(window).load(function () {
        $('.main-slider').addClass('animate-in');
        $('.preloader').remove();
    });

    //Responsive Nav
    $('li.dropdown').find('.fa-angle-down').each(function () {
        $(this).on('click', function () {
            if ($(window).width() < 768) {
                $(this).parent().next().slideToggle();
            }
            return false;
        });
    });

    //Fit Vids
    if ($('#video-container').length) {
        $("#video-container").fitVids();
    }

    //Pretty Photo
    $("a[rel^='prettyPhoto']").prettyPhoto({
        social_tools: false
    });

    // Timeago - cambiar a español
    $(document).ready(function () {
        // Spanish
        jQuery.timeago.settings.strings = {
            prefixAgo: "hace",
            prefixFromNow: "dentro de",
            suffixAgo: "",
            suffixFromNow: "",
            seconds: "menos de un minuto",
            minute: "un minuto",
            minutes: "unos %d minutos",
            hour: "una hora",
            hours: "%d horas",
            day: "un día",
            days: "%d días",
            month: "un mes",
            months: "%d meses",
            year: "un año",
            years: "%d años"
        };
    });

    // Search
    $('.fa-search').on('click', function () {
        $('.field-toggle').fadeToggle(200);
    });

    var items = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: SITE_URL + '/search/user?q=%QUERY',
            wildcard: '%QUERY'
        }
    });

    items.initialize();

    $('#search').typeahead({
        hint: true,
        highlight: true,
        minLength: 2
    }, {
        name: 'items',
        displayKey: 'name',
        source: items.ttAdapter(),
        templates: {
            empty: ['<div class="text-error tt-suggestion">No hay resultados para tu b&uacute;squeda</div>']
        }
    });

    // Progress Bar
    $.each($('div.progress-bar'), function () {
        $(this).css('width', $(this).attr('data-transition') + '%');
    });
});

function display_notification(selector, action, message) {
    var className = '';
    var innerHtml = '';

    switch (action)
    {
        case 'update':
            className = 'alert alert-info';
            innerHtml = '<i class="fa fa-spinner fa-pulse"></i> ' + (message || 'Actualizando...');
            break;
        case 'success':
            className = 'alert alert-success';
            innerHtml = '<i class="fa fa-check"></i> ' + (message || 'Actualizado correctamente.');
            break;
        case 'error':
            className = 'alert alert-danger';
            innerHtml = '<i class="fa fa-times"></i> ' + (message || 'Error al intentar actualizar.');
            break;
    }

    $(selector).fadeOut();
    $(selector).attr('class', className);
    $(selector).html(innerHtml);
    $(selector).fadeIn();
}

function selectText(containerId) {
    var elem = document.getElementById(containerId);
    if (document.selection) {
        var range = document.body.createTextRange();
        range.moveToElementText(elem);
        range.select();
    } else if (window.getSelection) {
        var range = document.createRange();
        range.selectNodeContents(elem);
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);
    }
}

function async_post(action, data, el, success, error) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: SITE_URL + action,
        data: $.param(data),
        success: success,
        error: error
    });
}