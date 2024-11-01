(function (global, $) {

    var editor,
        syncCSS = function () {
            rtSyncCss();
        },
        loadAce = function () {
            $('.rt-custom-css').each(function () {
                var id = $(this).find('.custom-css').attr('id');
                editor = ace.edit(id);
                global.safecss_editor = editor;
                editor.getSession().setUseWrapMode(true);
                editor.setShowPrintMargin(false);
                editor.getSession().setValue($(this).find('.custom_css_textarea').val());
                editor.getSession().setMode("ace/mode/css");
            });

            $.fn.spin && $('.custom_css_container').spin(false);
            $('#post').submit(syncCSS);
        };
    if ($.browser.msie && parseInt($.browser.version, 10) <= 7) {
        $('.custom_css_container').hide();
        $('.custom_css_textarea').show();
        return false;
    } else {
        $(global).load(loadAce);
    }
    global.aceSyncCSS = syncCSS;

    function rtSyncCss() {
        $('.rt-custom-css').each(function () {
            var e = ace.edit($(this).find('.custom-css').attr('id'));
            $(this).find('.custom_css_textarea').val(e.getSession().getValue());
        });
    }

    $(document).ready(function () {
        $(".rt-tab-nav li:first-child a").trigger('click');
        callActionScript();
    });

    function callActionScript() {
        $(".rt-color").wpColorPicker();
        $("select.rt-select2").select2({
            theme: "classic",
            dropdownAutoWidth: true,
            width: '100%'
        });
    }

    $(document).on("click", "span.rtAddImage", function (e) {
        var file_frame, image_data;
        var $this = $(this).parents('.rt-image-holder');
        if (undefined !== file_frame) {
            file_frame.open();
            return;
        }
        file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Select or Upload Media For your profile gallery',
            button: {
                text: 'Use this media'
            },
            multiple: false
        });
        file_frame.on('select', function () {
            var attachment = file_frame.state().get('selection').first().toJSON();
            var imgId = attachment.id;
            var imgUrl = (typeof attachment.sizes.thumbnail === "undefined") ? attachment.url : attachment.sizes.thumbnail.url;
            $this.find('.hidden-image-id').val(imgId);
            $this.find('.rtRemoveImage').show();
            $this.find('img').remove();
            $this.find('.rt-image-preview').append("<img src='" + imgUrl + "' />");
        });
        // Now display the actual file_frame
        file_frame.open();
    });

    $(document).on("click", "span.rtRemoveImage", function (e) {
        e.preventDefault();
        if (confirm("Are you sure?")) {
            var $this = $(this).parents('.rt-image-holder');
            $this.find('.hidden-image-id').val('');
            $this.find('.rtRemoveImage').hide();
            $this.find('img').remove();
        }
    });

    $(".rt-tab-nav li").on('click', 'a', function (e) {
        e.preventDefault();
        var container = $(this).parents('.rt-tab-container');
        var nav = container.children('.rt-tab-nav');
        var content = container.children(".rt-tab-content");
        var $this, $id;
        $this = $(this);
        $id = $this.attr('href');
        content.hide();
        nav.find('li').removeClass('active');
        $this.parent().addClass('active');
        container.find($id).show();
    });

    $("#rt-tpt-settings-form").on('submit', function (e) {
        e.preventDefault();
        rtSyncCss();
        var arg = {
            data: $(this).serialize(),
            action: 'tptSettingsAction',
            rttpt_nonce: rttpt.nonce
        };
        AjaxCall(arg, function (data) {
            if (!data.error) {
                $('#rt-response').removeClass('error');
                $('#rt-response').show('slow').text(data.msg);
            } else {
                $('#rt-response').addClass('error');
                $('#rt-response').show('slow').text(data.msg);
            }
        });
    });
    $('#tpt-add-new-col').on('click', function () {
        var target = $("#tpt-table-wrapper"),
            id = target.find('.tpt-table-col').length,
            arg = {
                id: id,
                action: 'tpt_add_new_col_action',
                rttpt_nonce: rttpt.nonce
            };
        AjaxCall(arg, function (data) {
            if (!data.error) {
                target.append(data.data);
                setSortableCols();
            }
        });
    });

    $(document).on("click", ".tpt-delete-col", function () {
        if (confirm("Are you sure to delete!!!")) {
            var self = $(this),
                target = self.parents('.tpt-table-col');
            target.slideUp('slow', function () {
                $(this).remove();
            });
        }
    });

    $(document).on('click', '.rt-style', function () {
        var self = $(this),
            type = self.attr("data-type"),
            value = self.parent('.tpt-style-wrapper').find('input').val(),
            arg = {
                type: type,
                value: value,
                action: 'tpt_style_action',
                rttpt_nonce: rttpt.nonce
            };
        AjaxCall(arg, function (data) {
            if (!data.error) {
                $('body #rt-popup-wrapper').animate({
                    'height': 'toggle'
                }, 500, function () {
                    $(this).remove();
                });
                rtPopUp(data.html, self);
                callActionScript();
            }
        });
    });

    $(document).on('click', 'span.tpt-add-row', function () {
        var self = $(this),
            target = self.parents('.section-content').find('.tpt-body-content'),
            id = self.parents('.tpt-table-col').find('.tpt-item-general .tpt-style-wrapper input').attr('name');
        id = id.replace('tpt_data[', '');
        id = id.replace('][general][style]', '');
        id = parseInt(id, 10);
        var arg = {
            id: id,
            action: 'tpt_add_row_action',
            rttpt_nonce: rttpt.nonce
        };

        AjaxCall(arg, function (data) {
            console.log(data);
            if (!data.error) {
                target.append(data.html);
                setSortableRows();
            }
        });

    });
    $(document).on('click', 'span.tpt-edit-row', function () {

        var self = $(this),
            value = self.parents('.body-row').find('.body-item input').val(),
            arg = {
                value: value,
                action: 'tpt_edit_row_action',
                rttpt_nonce: rttpt.nonce
            };

        AjaxCall(arg, function (data) {
            if (!data.error) {
                $('body #rt-popup-wrapper').animate({
                    'height': 'toggle'
                }, 500, function () {
                    $(this).remove();
                });
                rtPopUp(data.html, self, true);
                callActionScript();
            }
        });
    });

    $(document).on('click', 'span.tpt-delete-row', function () {
        if (confirm("Are you sure?")) {
            var self = $(this),
                target = self.parents('.body-row');
            target.slideUp('slow', function () {
                $(this).remove();
            });
        }
    });

    $(document).on('click', ".icon-list-wrap span.icon", function () {
        var self = $(this),
            id = self.attr('data-id'),
            target = self.parents('.icon-list-wrapper').find('.selected-icon-wrap');
        target.find('input').val(id);
        target.find('.selected-icon').html('<i class="fa ' + id + '"></i>');
        self.parents('.icon-list-wrap').find('.icon').removeClass('selected');
        self.addClass('selected');
    });

    $(document).on('click', '.show-icons, .hide-icons', function () {
        var self = $(this),
            parent = self.parent('.toolbar'),
            target = self.parents('.icon-list-wrapper').find('.icon-list-wrap');
        if (parent.hasClass('hide')) {
            target.slideDown();
            parent.addClass('show');
            parent.removeClass('hide');
        } else {
            target.slideUp();
            parent.addClass('hide');
            parent.removeClass('show');
        }
    });

    $(document).on('change', '.rt-img-selector-wrap .rt-img-selector', function () {
        var self = $(this),
            src = self.attr('data-src'),
            value = self.val(),
            target = self.parent().find('.img-selector-media img');
        target.attr('src', src + value + ".png");
    });

    function rtPopUp(data, self, type) {
        var container = $("<div id='rt-popup-wrapper' />");
        container.css({"opacity": 0});
        container.html("<div class='rt-popup-container'>" +
            "<div class='rt-popup-close'><span class='dashicons dashicons-dismiss'></span></div>" +
            "<div class='rt-popup-content'>" + data + "</div>" +
            "<div class='rt-popup-apply'><span class='apply-btn button button-primary'>Apply</span></div>" +
            "</div>");
        $('body').append(container);
        container.animate({
            opacity: 1
        }, 500);
        container.find(".rt-popup-close").on('click', function () {
            $(this).parents('#rt-popup-wrapper').animate({
                opacity: 0
            }, 500, function () {
                $(this).remove();
            });
        });
        container.find(".rt-popup-apply").on('click', "span.apply-btn", function () {
            var data = $(this).parents('#rt-popup-wrapper').find('.rt-popup-content').find('select,input').serialize();
            if (type) {
                self.parents('.body-row').find('.body-item input').val(data);
                var content = $(this).parents('#rt-popup-wrapper').find('#content').val();
                self.parents('.body-row').find('.content').text(content);
            } else {
                self.parent('.tpt-style-wrapper').find('input').val(data);
            }
            $(this).parents('#rt-popup-wrapper').animate({
                opacity: 0
            }, 500, function () {
                $(this).remove();
            });
        });
    }


    setSortableCols();
    setSortableRows();
    $(window).load(function () {
        setSortableCols();
        setSortableRows();
    });

    function setSortableRows() {
        if (typeof jQuery().sortable !== 'undefined') {
            var $cols = $('.tpt-body-content');
            $cols.sortable({
                axis: 'y',
                revert: 100,
                items: '.body-row',
                distance: 5,
                handle: '.tpt-move-row'
            });
        }
    }

    function setSortableCols() {
        if (typeof jQuery().sortable !== 'undefined') {
            var $cols = $('#tpt-table-wrapper');
            $cols.sortable({
                axis: 'x',
                revert: 100,
                items: '.tpt-table-col',
                distance: 5,
                handle: '.tpt-move-col',
                scrollSpeed: 30,
                scrollSensitivity: 50,
                placeholder: 'rt-pricing-col-placeholder',
                tolerance: 'pointer',
                start: function (event, ui) {
                    ui.item.closest('#tpt-table-wrapper').css('height', ui.item.outerHeight(true));
                    ui.item.siblings('.tpt-table-col').css('opacity', 0.5);
                    ui.placeholder.css('height', ui.item.outerHeight(true) - 59);
                },
                stop: function (event, ui) {
                    ui.item.siblings().css('opacity', 1)
                },
                update: function (event, ui) {
                    setColIndex();
                    // $(document).trigger('click');
                    ui.item.closest('#tpt-table-wrapper').css('height', 'auto')
                }
            });
        }

    }

    function setColIndex() {
        var $cols = $('#tpt-table-wrapper').find('.tpt-table-col');
        for (var x = 0; x < $cols.length; x++) {
            var $elem = $cols.eq(x),
                $inputs = $elem.find('[name*="tpt_data"]');

            for (var z = 0; z < $inputs.length; z++) {
                var el = $inputs[z];
                el.setAttribute('name', el.getAttribute('name').replace(/tpt_data\[([0-9]+)?\]/g, 'tpt_data[' + x + ']'));
            }

        }
    }

    function AjaxCall(data, handle) {
        $.ajax({
            type: "post",
            url: ajaxurl,
            data: data,
            beforeSend: function () {
                $('body').append($("<div id='rt-loading'><span class='rt-loading'>Updating ...</span></div>"));
            },
            success: function (data) {
                $("#rt-loading").remove();
                handle(data);
            },
            error: function () {
                $("#rt-loading").remove();
                alert('error');
            }
        });
    }

    $(document).on('change', '.selected_img_preview', function () {
        tptSelectedImgPreview($(this));
    });
    $(document).find('.selected_img_preview').each(function () {
        tptSelectedImgPreview($(this));
    });

})(this, jQuery);

function tptSelectedImgPreview(target) {
    var id = target.val(),
        src = target.attr('data-src') + id + '.png';
    if (id) {
        target.parent('.field').find('img').remove();
        target.parent('.field').append('<img class="slt-preview-img" src="' + src + '" />');
    }
}