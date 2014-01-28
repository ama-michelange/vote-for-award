// 
$(document).ready(function () {
    $("[rel=tooltip]").tooltip({
        'placement': 'auto top',
        'trigger': 'hover',
        'container': 'body',
        'delay': {
            'show': 500,
            'hide': 100
        }
    });
    $("[data-rel=tooltip]").tooltip({
        'placement': 'auto top',
        'trigger': 'hover',
        'container': 'body',
        'delay': {
            'show': 500,
            'hide': 100
        }
    });
    $("[data-rel=popover]").popover({
        'placement': 'auto left',
        'trigger': 'hover',
        'delay': {
            'show': 500,
            'hide': 200
        }
    });

    $(".datepicker").datepicker({
        // 'format' : 'yyyy-mm-dd',
        'format': 'dd/mm/yyyy',
        'weekStart': 1
    });

    $("#myModal").on('shown', function () {
        $('input:text:visible:first', this).focus();
    });

    $("select").select2({
        // width:"off"
    });

    // $('#acl').on('hidden.bs.collapse', function() {
    // // do something…
    // alert("caché");
    // });

    $("[data-chevron=collapse]").chevron();

});

// ----------------------
// Chevron

(function ($) {
    "use strict";

    var Chevron = function (element) {
        this.$element = $(element);
        this.init(element);
    };

    Chevron.prototype.init = function (element) {
        this.$element = $(element);
        var $parent = this.$element.parent();
        var href = $parent.attr('href');
        // debug('href = ' + href);

        $(href).on('hidden.bs.collapse', $.proxy(this.hidden, this));
        $(href).on('shown.bs.collapse', $.proxy(this.shown, this));
    };

    Chevron.prototype.hidden = function () {
        this.$element.removeClass('glyphicon-chevron-up');
        this.$element.addClass('glyphicon-chevron-down');
        // alert("caché");
    };
    Chevron.prototype.shown = function () {
        this.$element.removeClass('glyphicon-chevron-down');
        this.$element.addClass('glyphicon-chevron-up');
        // alert("shown");
    };

    // Private function for debugging.
    /*
     function debug(mess) {
     if (window.console && window.console.log) {
     window.console.log(mess);
     }
     }*/

    // CHEVRON PLUGIN DEFINITION
    // ========================

    var old = $.fn.chevron;

    $.fn.chevron = function () {
        return this.each(function () {
            var $this = $(this);
            var data = $this.data('ama.chevron');

            if (!data) {
                $this.data('ama.chevron', new Chevron(this));
            }
        });
    };

    $.fn.chevron.Constructor = Chevron;

    // CHEVRON NO CONFLICT
    // ==================

    $.fn.chevron.noConflict = function () {
        $.fn.chevron = old;
        return this;
    };

    // CHEVRON DATA-API
    // ===============

    // $(document).on('click.ama.chevron.data-api', '[data-toggle^=button]',
    // function(e) {
    // var $btn = $(e.target)
    // if (!$btn.hasClass('btn'))
    // $btn = $btn.closest('.btn')
    // $btn.button('toggle')
    // e.preventDefault()
    // })

}(jQuery));
