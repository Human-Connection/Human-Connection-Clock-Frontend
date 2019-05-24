window.hcfrontent = (function(window, document, $){
    let app = {};

    app.init = function(){
        app.doHoverImgs();

        if($('.hcmedia-video').length){
            $('.hcmedia-video').click(function(){
                app.lazyloadYT($(this));
            });
        }

        if($('.alphareports-view').length){
            $('.alpha-frontend-title').click(function(e){
                let id = $(this).data('id');
                if(id !== undefined){
                    let el = $('#report-content-'+id);
                    if(el.is(":visible")){
                        el.hide();
                    }else{
                        el.show();
                    }
                }
                e.preventDefault();
            });

            $('#alphaSearchVal').on("propertychange change click keyup input paste", function(event){
                let searchVal = $(this).val().toLowerCase();
                let articles = $('#hcalpha').find('article.hcalpha');
                /*
                    TODO: each articles search title and content
                    hide all items not relevant
                    or highlight all items relevant
                */
                articles.each(function(i){
                    if(searchVal === ''){
                        $(this).show();
                        $(this).parent().parent().prev().show();
                    }else{
                        $(this).parent().parent().parent().prev().hide();
                        if($(this).find('.alpha-frontend-title')[0].innerText.toLowerCase().search(searchVal) > -1 || 
                            $(this).find('.panel-body p')[0].innerText.toLowerCase().search(searchVal) > -1){
                            $(this).show();
                        }else{
                            $(this).hide();
                        }
                    }
                });
            })
        }
    };

    app.doHoverImgs = function(){
        let $hcHoverImages = $('.hc-hoverimage');
        $hcHoverImages.on('mouseenter', function() {
            let id = $(this).attr('id');
            $hcHoverImages.not($(this)).addClass('inactive');
            $('.hc-people-text[data-id="' + id +'"]').addClass('active');
            $('.hc-people-info').removeClass('active');
        }).on('mouseleave', function() {
            let id = $(this).attr('id');
            $hcHoverImages.removeClass('inactive');
            $('.hc-people-text[data-id="' + id +'"]').removeClass('active');
            $('.hc-people-info').addClass('active');
        });
    };

    app.lazyloadYT = function(videoEle){
        let iframe = document.createElement("iframe");
        /*
         <div class="iframe-wrapper video-wrapper">

         */

        $(videoEle).find('.image-wrapper').after('<div class="iframe-wrapper video-wrapper"></div>');

        //<iframe width="100%" src="<?php //echo $videoUrl; ?>?rel=0" frameborder="0" allow="encrypted-media" allowfullscreen></iframe>
        iframe.setAttribute("frameborder", "0");
        iframe.setAttribute("width", "100%");
        iframe.setAttribute("allowfullscreen", "");
        iframe.setAttribute("allow", "encrypted-media");
        iframe.setAttribute("src", videoEle.data('url')+"?rel=0&showinfo=0&autoplay=1" );

        $(videoEle).find('.video-wrapper').append(iframe);
        $(videoEle).find('.image-wrapper').hide();
    };

    $(document).ready(app.init);

    return app;

})(window, document, jQuery);