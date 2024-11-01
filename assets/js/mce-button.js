(function($) {
    tinymce.PluginManager.add('rt_tpt_scg', function( editor, url ) {
        var tpt_tag = 'the-pricing-table';


        //add popup
        editor.addCommand('rt_tpt_scg_popup', function(ui, v) {
            //setup defaults

            editor.windowManager.open( {
                title: 'The Pricing table ShortCode',
                width: $( window ).width() * 0.3,
                height: ($( window ).height() - 36 - 50) * 0.1,
                id: 'rt-tpt-insert-dialog',
                body: [
                    {
                        type   : 'container',
                        html   : '<span class="rt-loading">Loading...</span>'
                    },
                ],
                onsubmit: function( e ) {

                    var shortcode_str;
                    var id = $("#scid").val();
                    var title = $( "#scid option:selected" ).text();
                    if(id && id != 'undefined'){
                        shortcode_str = '[' + tpt_tag;
                            shortcode_str += ' id="'+id+'" title="'+ title +'"';
                        shortcode_str += ']';
                    }
                    if(shortcode_str) {
                        editor.insertContent(shortcode_str);
                    }else{
                        alert('No short code selected');
                    }
                }
            });

            putScList();
        });

        //add button
        editor.addButton('rt_tpt_scg', {
            icon: 'rt_tpt_scg',
            tooltip: 'The Pricing Table',
            cmd: 'rt_tpt_scg_popup',
        });

        function putScList(){
                var dialogBody = $( '#rt-tpt-insert-dialog-body' )
                $.post( ajaxurl, {
                    action: 'rtTPTShortCodeList'
                }, function( response ) {
                    dialogBody.html(response);
                    console.log(response);
                });

        }

    });
})(jQuery);