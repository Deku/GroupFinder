/* 
 * A Lil' Crud interface
 * @author : Jose Gonzalez <maangx@gmail.com>
 */

;( function (window) {
   'use strict';
   
   function extend (a, b) {
        for(var key in b) { 
            if(a.hasOwnProperty(key) ) {
                a[key] = b[key];
            }
        }
        return a;
    }
    
    function createEl (tag, attrbs, appendTo) {
        var el = document.createElement(tag);
        
        if (attrbs) { extend(el, attrbs); }
        if (appendTo) { appendTo.appendChild(el); }
        
        return el;
    }
    
    function createInput (id, type, desc, parent) {
        var div = createEl ( 'div', { className: 'form-group' }, parent);
        
        createEl (
            'label',
            {
               for: id,
               className: 'col-md-4 col-xs-12 control-label',
               innerText: desc
            },
            div
        );
        
        var inputWrap = createEl ( 'div', { className: 'col-md-8 col-xs-12' }, div);
        
        createEl(
            'input',
            {
                id: id,
                name: id,
                className: 'form-control',
                type: type
            },
            inputWrap
        );
    }
    
    function createTextarea (id, desc, parent) {
        var div = createEl ( 'div', { className: 'form-group' }, parent );
        
        createEl (
            'label',
            {
               for: id,
               className: 'col-md-2 col-xs-12 control-label',
               innerText: desc
            },
            div
        );
        
        var inputWrap = createEl ( 'div', { className: 'col-md-10 col-xs-12' }, div);
        
        createEl(
            'textarea',
            {
                id: id,
                name: id,
                className: 'form-control'
            },
            inputWrap
        );
    }
    
    function display_notification(selector, action) {
        var className = '';
        var innerHtml = '';
        
        switch (action)
        {
            case 'update':
                className = 'alert alert-info';
                innerHtml = '<i class="fa fa-spinner fa-spin"></i> Actualizando...';
                break;
            case 'success':
                className = 'alert alert-success';
                innerHtml = '<i class="fa fa-check"></i> Actualizado correctamente';
                break;
            case 'error':
                className = 'alert alert-danger';
                innerHtml = '<i class="fa fa-times"></i> Error al intentar actualizar';
                break;
        }
        
        $(selector).fadeOut();
        $(selector).attr('class', className);
        $(selector).html(innerHtml);
        $(selector).fadeIn();
    }
    
    function LilCrud (sel, options) {
        this.wrapper = document.getElementById(sel);
        this.id = '';
        extend(this, options);
        this.countItems = 0;
    }
    
    LilCrud.prototype.add = function () {
        var i = this.countItems;
        var el = new Item(i, this);
        this.wrapper.appendChild(el.el);
        this.countItems++;
    }
    
    LilCrud.prototype.get = function (role) {
        
    }
    
    LilCrud.prototype.edit = function (role, opts) {
        
    }
    
    LilCrud.prototype.rename = function (role, newName) {
        
    }
    
    LilCrud.prototype.remove = function (id) {
        
    }
    
    LilCrud.prototype.send = function (data) {
        var r = false;
        
        $.ajax({
            type: "POST",
            dataType: "json",
            url: SITE_URL + '/projects/save',
            data: $.param(data),
            beforeSend: function () {
                display_notification(this.notifId, 'update');
            },
            success: function() {
                display_notification(this.notifId, 'success');
                r = true;
            },
            error: function() {
                display_notification(this.notifId, 'error');
            }
        });
        
        return r;
    }
    
    function Item (id, editor, structure) {
        this.id = id;
        this.editor = editor;
        this.el = createEl('div', { id: this.id, className: 'vacant-item row' });
        this.fields = structure;
        
        for (field in fields) {
            field.el = new Field(this.id + '-' + this.field.id, field);
        }
    }
    
    function Field (id, data) {
        this.id = id;
        
        switch (data.tag) {
            case 'input':
                this.el = createInput(id, data.label);
                break;
            case 'textarea':
                this.el = createTextarea(id, data.label);
                break;
            default:
                this.el = null;
        }
    }
    
    window.LilCrud = LilCrud;
   
}) ( window );

