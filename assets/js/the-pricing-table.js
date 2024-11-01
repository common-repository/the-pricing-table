(function ($, undefined) {
    "use strict";
    $(function () {
        $.TptPricing = {
            fixBodyItemHeight: function () {
                for (var x = 0; x < $.TptPricing.$wrap.length; x++) {
                    var $pricingTable = $.TptPricing.$wrap.eq(x),
                        $cols = $pricingTable.find('.tpt-col-wrap'),
                        maxItemHeight = 0,
                        bodyItem = $cols.find('.tpt-body');
                    bodyItem.height('auto');
                    for (var i = 0; i < $cols.length; i++) {
                        var $col = $cols.eq(i),
                            itemHeight = $col.find('.tpt-body').outerHeight();
                        if (itemHeight > maxItemHeight) {
                            maxItemHeight = itemHeight;
                        }
                    }
                    bodyItem.height(maxItemHeight + 'px');
                }
            },
            Init: function () {
                this.$wrap = $('.tpt-table');
                this.fixWidth();
                //this.fixBodyItemHeight();
            },
            /* Fix width in webkit browsers */
            fixWidth: function () {

                if ('WebkitAppearance' in document.documentElement.style === false) return;

                for (var x = 0; x < $.TptPricing.$wrap.length; x++) {

                    var $pricingTable = $.TptPricing.$wrap.eq(x),
                        $cols = $pricingTable.find('.tpt-col-wrap');

                    if ($pricingTable.is(':hidden') || $pricingTable.offset().top > parseInt($(document).scrollTop() + window.innerHeight + 500 || $pricingTable.data('fix-width') === true)) continue;

                    for (var i = 0; i < $cols.length; i++) {

                        var $col = $cols.eq(i);
                        $cols.css('max-width', 'none');
                        $cols.css('max-width', Math.floor(parseFloat(window.getComputedStyle($col[0]).width)));

                    }

                    $pricingTable.data('fix-width', true);

                }

            }
        };
        /* Init */
        $.TptPricing.Init();
    });

}(jQuery));