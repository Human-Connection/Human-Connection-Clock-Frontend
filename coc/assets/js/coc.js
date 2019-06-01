/**
 * @var {object} cocVars
 */

window.coc = ((window, document, $) => {
    let app           = {},
        formOverlay   = $('.coc-form-overlay'),
        defaultMsg    = 'Ich bin für Veränderung.',
        defaultName   = 'Mensch',
        baseUrl       = 'https://tools.human-connection.org/avataaars/composite',
        userImages    = $('.user-image'),
        userMessage   = $('#user-message'),
        userWallIndex = 0,
        files         = null,
        useOwnImage   = false,
        countries     = {},
        hair          = [],
        eyebrow       = [],
        eyes          = [],
        nose          = [],
        mouth         = [],
        facialHair    = [],
        clothing      = [],

        hairIndex       = 0,
        eyebrowIndex    = 0,
        eyesIndex       = 0,
        noseIndex       = 0,
        mouthIndex      = 0,
        facialHairIndex = 0,
        clothingIndex   = 0,
        offset          = 0;

    let indexMap = [];
    indexMap['hair']       = hairIndex;
    indexMap['eyebrow']    = eyebrowIndex;
    indexMap['eyes']       = eyesIndex;
    indexMap['nose']       = noseIndex;
    indexMap['mouth']      = mouthIndex;
    indexMap['facialHair'] = facialHairIndex;
    indexMap['clothing']   = clothingIndex;

    let findIndex = (elem, items) => {
        let i, len = items.length;
        for(i=0; i<len; i++) {
            if (items[i]===elem) {
                return i;
            }
        }
        return -1;
    };

    app.ownImageChangeHandler = function(){
        $('#ownImageUpload').on('change', (event) => {
            if (event.target.files && event.target.files[0]){
                files = event.target.files;
                let reader = new FileReader();

                reader.onload = function(e){
                    $('#coc-image')
                        .attr('src', e.target.result)
                        .width(200)
                        .height(200);
                };

                reader.readAsDataURL(event.target.files[0]);
                useOwnImage = true;

                $('#avatar-wrapper').html("");
                $('#add-avatar-icon').removeClass('fa-close');
                $('#add-avatar-icon').addClass('fa-plus');
            }
            $(this).val('');
        });

        $('#customUploadWrap').on('click', (e) => {
            $('#ownImageUpload').trigger('click');
            useOwnImage = true;

            e.preventDefault();
        });
    };

    app.init = () => {
        app.loadCountries();

        $('#joinCoC').click((e) => {
            // show form
            app.toggleForm();

            e.preventDefault();
        });

        $('.coc-form-close').click(() => {
            app.toggleForm();
        });

        $('#coc-add-avatar .add-avatar-wrapper').click(function(){
            let avatarWrapper = $('#avatar-wrapper');
            let addAvatarIcon = $('#add-avatar-icon');
            useOwnImage = false;

            if(avatarWrapper.html() === ""){
                avatarWrapper.show();
                addAvatarIcon.removeClass('fa-plus');
                addAvatarIcon.addClass('fa-close');

                $('#avatar-wrapper').append(
                    '<div class="fusion-tabs fusion-tabs-2 classic horizontal-tabs icon-position-left">'
                    + '<div class="nav">'
                    + '<ul class="nav-tabs nav-justified" id="selection-cat-tabs">'
                    + '<li class="active">'
                    + '<a class="tab-link" id="hair-tab" data-toggle="tab" href="#hair" role="tab" aria-controls="hair" aria-selected="false">Haare</a>'
                    + '</li>'
                    + '<li>'
                    + '<a class="tab-link" id="eyebrow-tab" data-toggle="tab" href="#eyebrow" role="tab" aria-controls="eyebrow" aria-selected="false">Augenbrauen</a>'
                    + '</li>'
                    + '<li>'
                    + '<a class="tab-link" id="eyes-tab" data-toggle="tab" href="#eyes" role="tab" aria-controls="eyes" aria-selected="false">Augen</a>'
                    + '</li>'
                    //  + '<li>'
                    //  + '<a class="tab-link" id="nose-tab" data-toggle="tab" href="#nose" role="tab" aria-controls="nose" aria-selected="true">Nase</a>'
                    //  + '</li>'
                    + '<li>'
                    + '<a class="tab-link" id="mouth-tab" data-toggle="tab" href="#mouth" role="tab" aria-controls="mouth" aria-selected="true">Mund</a>'
                    + '</li>'
                    + '<li>'
                    + '<a class="tab-link" id="facialHair-tab" data-toggle="tab" href="#facialHair" role="tab" aria-controls="facialHair" aria-selected="false">Barthaar</a>'
                    + '</li>'
                    + '<li>'
                    + '<a class="tab-link" id="clothing-tab" data-toggle="tab" href="#clothing" role="tab" aria-controls="clothing" aria-selected="true">Kleidung</a>'
                    + '</li>'
                    + '</ul>'
                    + '</div>'
                    + '</div>'
                );

                $('#avatar-wrapper').append('<div class="tab-content" id="selection-tabs"></div>');
                $.each(cocVars.avatars, (i, val) => {
                    let isFirst = true;
                    $('#avatar-wrapper #selection-tabs').append(
                        '<div class="tab-pane fade fusion-clearfix" id="'+i+'" role="tabpanel" aria-labelledby="'+i+'-tab">'
                        + '<div class="selection-wrapper">'
                        + '<div class="selection-' + i + '">'
                        + '</div>'
                        + '<div class="control-wrapper">'
                        + '<div class="go-left-wrapper pull-left disabled" data-prop="'+ i +'">'
                        + '<i class="switch-action fas fa-caret-left"></i>'
                        + '</div>'
                        + '<div class="go-right-wrapper pull-right" data-prop="'+ i +'">'
                        + '<i class="switch-action fas fa-caret-right"></i>'
                        + '</div>'
                        + '</div>'
                        + '</div>'
                        + '</div>'
                    );
                    if(i == 'hair'){
                        $('#'+i).addClass('active in');
                    }
                    $.each(val, (j, opt) => {
                        if(isFirst === true){
                            isFirst = false;
                            $('.selection-'+i).append('<div class="opt-wrapper" style="text-align:center;"><img src="'+opt.url+'" alt="avatar" /></div>');
                        } else {
                            $('.selection-'+i).append('<div class="opt-wrapper" style="text-align:center;display:none;"><img src="'+opt.url+'" alt="avatar" /></div>');
                        }

                        let activeVar;

                        switch(i) {
                            case 'hair':
                                activeVar = hair;
                                break;
                            case 'eyebrow':
                                activeVar = eyebrow;
                                break;
                            case 'eyes':
                                activeVar = eyes;
                                break;
                            case 'nose':
                                activeVar = nose;
                                break;
                            case 'mouth':
                                activeVar = mouth;
                                break;
                            case 'facialHair':
                                activeVar = facialHair;
                                break;
                            case 'clothing':
                                activeVar = clothing;
                                break;
                        }

                        activeVar.push(opt.name);
                    })
                });

                $('#coc-image').attr("src",
                    baseUrl + "?hair="
                    + hair[indexMap['hair']] +
                    '&eyebrow=' + eyebrow[indexMap['eyebrow']] +
                    '&eyes=' + eyes[indexMap['eyes']] +
                    '&nose=' + nose[indexMap['nose']] +
                    '&mouth=' + mouth[indexMap['mouth']] +
                    '&facialHair=' + facialHair[indexMap['facialHair']] +
                    '&clothing=' + clothing[indexMap['clothing']]
                );

                // read data('prop')
                // count current prop index 1 up or down unless 0 or prop.length
                // hide current active and display next or prev using img.src attr
                $('.go-left-wrapper').click(function(){
                    let prop = $(this).data("prop");
                    let wraps = $('.selection-'+prop).find('.opt-wrapper');

                    if(indexMap[prop] > 0){
                        wraps[indexMap[prop]].style.display = 'none';
                        indexMap[prop]--;
                        wraps[indexMap[prop]].style.display = 'block';

                        $('#coc-image').attr("src",
                            baseUrl + "?hair="
                            + hair[indexMap['hair']] +
                            '&eyebrow=' + eyebrow[indexMap['eyebrow']] +
                            '&eyes=' + eyes[indexMap['eyes']] +
                            '&nose=' + nose[indexMap['nose']] +
                            '&mouth=' + mouth[indexMap['mouth']] +
                            '&facialHair=' + facialHair[indexMap['facialHair']] +
                            '&clothing=' + clothing[indexMap['clothing']]
                        );
                        $(this).removeClass('disabled');
                    }else{
                        $(this).addClass('disabled');
                    }

                    if(indexMap[prop] <= 0){
                        $(this).addClass('disabled');
                    }

                    if(indexMap[prop] < wraps.length - 1){
                        $(this).parent().find('.go-right-wrapper').removeClass('disabled');
                    }
                });

                $('.go-right-wrapper').click(function(){
                    let prop  = $(this).data("prop");
                    let wraps = $('.selection-'+prop).find('.opt-wrapper');

                    if(wraps.length -1 == indexMap[prop]){
                        $(this).addClass('disabled');
                    }else{
                        wraps[indexMap[prop]].style.display = 'none';
                        indexMap[prop]++;
                        wraps[indexMap[prop]].style.display = 'block';

                        $('#coc-image').attr("src",
                            baseUrl + "?hair="
                            + hair[indexMap['hair']] +
                            '&eyebrow=' + eyebrow[indexMap['eyebrow']] +
                            '&eyes=' + eyes[indexMap['eyes']] +
                            '&nose=' + nose[indexMap['nose']] +
                            '&mouth=' + mouth[indexMap['mouth']] +
                            '&facialHair=' + facialHair[indexMap['facialHair']] +
                            '&clothing=' + clothing[indexMap['clothing']]
                        );
                        $(this).removeClass('disabled');
                    }

                    if(indexMap[prop] >= 1){
                        $(this).parent().find('.go-left-wrapper').removeClass('disabled');
                    }else{
                        $(this).parent().find('.go-left-wrapper').addClass('disabled');
                    }
                });
            }else{
                avatarWrapper.html("");
                addAvatarIcon.removeClass('fa-close');
                addAvatarIcon.addClass('fa-plus');
            }
        });

        $('#joinNowBtn').click(function(e){
            e.preventDefault();

            let data = new FormData(),
                emailInput = $('#coc-email'),
                messageInput = $('#coc-message'),
                fnInput = $('#coc-firstname'),
                hasErrors = false,
                privacyChecked = $('#coc-register-privacy')[0].checked;

            if(fnInput.val() === ''){
                fnInput.addClass('input-error');
                hasErrors = true;
            }

            if(emailInput.val() === ''){
                emailInput.addClass('input-error');
                hasErrors = true;
            }

            if(messageInput.val() === ''){
                messageInput.addClass('input-error');
                hasErrors = true;
            }

            if(privacyChecked === 0 || privacyChecked === undefined || privacyChecked === false){
                $('#coc-register-privacy').parent().addClass('input-error');
                hasErrors = true;
            }

            if(hasErrors)
                return;

            // append files
            // own image?
            if(useOwnImage === true){
                if(files !== null && files !== undefined){
                    data.append('image', files[0]);
                }
            }else{
                data.append('imageUrl', $('#coc-image').attr("src"));
            }

            data.append('firstname', fnInput.val());
            data.append('lastname', $('#coc-lastname').val());
            data.append('email', emailInput.val());
            data.append('message', messageInput.val());
            data.append('country', $('#coc-country').val());
            data.append('anon', $('#coc-anon')[0].checked);
            data.append('beta', '0');
            data.append('nl', $('#coc-register-nl')[0].checked);
            data.append('pr', privacyChecked);

            // PAX
            $.urlParam = function (name) {
                let results = new RegExp('[\?&]' + name + '=([^&#]*)')
                    .exec(window.location.search);
                if (results == null) {
                    return 0;
                }
                return results[1] || 0;
            };

            data.append('pax', $.urlParam('pax'));

            $.ajax({
                url: cocVars.ajax_url+'coc/v2/createEntry/',
                method: 'POST',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', cocVars.nonce);
                },
                success: function(resp) {
                    if(resp !== null && resp.success === true){
                        // hide form
                        new PNotify({
                            title: 'Das hat geklappt!',
                            text: 'Jetzt noch deine E-Mail adresse bestätigen und der Wandel beginnt!',
                            addclass: 'stack-bottomright',
                            stack: {'dir1': 'up', 'dir2': 'left', 'push': 'top'}
                        });

                        app.toggleForm();

                        setTimeout(function () {
                            window.location.reload();
                        }, 3000);
                    }else{
                        if (resp !== null) {
                            // show errors
                            emailInput.addClass('input-error');
                        }

                        new PNotify({
                            title: 'Ups! Das geht schief!',
                            text: 'Bitte probiere es erneut!',
                            addclass: 'stack-bottomright',
                            stack: {'dir1': 'up', 'dir2': 'left', 'push': 'top'}
                        });
                    }
                },
                data: data,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false
            });
        });

        $('.user-image').on("click", function(e){
            let message = $(this).data('message');
            let uName   = $(this).data('uname');
            let anon    = $(this).data('anon');
            let country = $(this).data('country');
            let src     = $(this).attr('src');
            let srcc    = this.parentElement;

            // update index
            userWallIndex = findIndex(srcc, document.getElementsByClassName('user-item'));
            userMessage.find('.user-message-image').attr('src', src);
            if(anon !== undefined && anon === 1){
                uName = defaultName;
            }
            userMessage.find('.message-name').text(uName);

            if(message === ''){
                message = defaultMsg;
            }
            userMessage.find('.message-text').text(message);

            app.loadCountryFlagImage(country);

            userMessage.show();
        });

        $('.close-wrapper').find('.fa-times').on("click", () => {
            userMessage.hide();
        });

        $('#loadMore').on("click", function(e){
            offset = offset +1;
            $.ajax({
                url: cocVars.ajax_url+'coc/v2/getEntries/?offset='+offset,
                method: 'GET',
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-WP-Nonce', cocVars.nonce);
                },
                success: function(data){
                    // less than page size elements
                    // hide load more btn
                    // TODO: pass param instead of hard 81
                    if(data.length < 81){
                        $('#loadMore').hide();
                    }

                    if(data.length > 0){
                        for(let i = 0;i < data.length;i++){
                            let obj = data[i];
                            // got merged with lastname on server - output as firstname for compat with old fs
                            let uName = obj.firstname;
                            let msg = obj.message;
                            let loadedImg = obj.image === '' ? cocVars.homeUrl + '/wp-content/plugins/coc/assets/images/coc-placeholder.jpg' : obj.image;
                            let country = obj.country;
                            let img = '<img class="user-image" data-anon="'+obj.anon+'" data-uname="'+uName+'" data-message="'+msg+'" data-country="' + country +'" style="width:100%;margin-top:5px;" alt="signer-image" src="'+loadedImg+'" />';
                            $('.user-container').append(
                                '<div class="user-item">' +
                                img +
                                '</div>'
                            );

                            userImages.push(img);
                        }

                        $('.user-image').on("click", function(){
                            let message = $(this).data('message');
                            let src   = $(this).attr('src');
                            let uName = $(this).data('uname');
                            let anon  = $(this).data('anon');
                            let country = $(this).data('country');
                            let srcc    = this.parentElement;

                            userWallIndex = findIndex(srcc, document.getElementsByClassName('user-item'));
                            userMessage.find('.user-message-image').attr('src', src);
                            if(message === ''){
                                message = defaultMsg;
                            }

                            if(anon !== undefined && anon === 1){
                                uName = defaultName;
                            }
                            userMessage.find('.message-name').text(uName);
                            userMessage.find('.message-text').text(message);

                            app.loadCountryFlagImage(country);

                            userMessage.show();
                        })
                    }else{
                        // no more data - ensure load more is hidden
                        $('#loadMore').hide();
                    }
                },
                cache: false,
            });

            e.preventDefault();
        });

        $('#prevMessage').on("click", () => {
            if(userWallIndex <= 0){
                userWallIndex = userImages.length -1;
            }else{
                userWallIndex--;
            }

            let nextData = $(userImages[userWallIndex]);
            let msg = nextData.data('message');
            if(msg === ''){
                msg = defaultMsg;
            }
            userMessage.find('.user-message-image').attr('src', nextData[0].src);
            userMessage.find('.message-text').text(msg);

            let uName = nextData.data('uname');
            if(nextData.data('anon') === 1){
                uName = defaultName;
            }
            userMessage.find('.message-name').text(uName);

            let country = nextData.data('country');
            app.loadCountryFlagImage(country);
        });

        $('#nextMessage').on("click", () => {
            if(userWallIndex >= userImages.length -1){
                userWallIndex = 0;
            }else{
                userWallIndex++;
            }

            let nextData = $(userImages[userWallIndex]);
            let msg = nextData.data('message');
            if(msg === ''){
                msg = defaultMsg;
            }
            userMessage.find('.user-message-image').attr('src', nextData[0].src);
            userMessage.find('.message-text').text(msg);

            let uName = nextData.data('uname');
            if(nextData.data('anon') === 1){
                uName = defaultName;
            }
            userMessage.find('.message-name').text(uName);

            let country = nextData.data('country');
            app.loadCountryFlagImage(country);
        });

        app.ownImageChangeHandler();
    };

    $(window).off('keyup').on('keyup', (e) => {
        if(e.which === 27) {
            app.closeForm();
            app.closeUserOverlay();
        }
    });

    app.toggleForm = () => {
        if(formOverlay.css('display') === 'block')
            formOverlay.hide();
        else
            formOverlay.show();

    };

    app.resetForm = () => {

    };

    app.closeForm = () => {
        formOverlay.hide();
    };

    app.resetForm = () => {

    };

    app.closeUserOverlay = () => {
        userMessage.hide();
    };

    app.loadCountryFlagImage = (country) => {
        let messageCountryElement = userMessage.find('.message-country');
        messageCountryElement.html('');

        if (country.length === 2) {
            let flagUrl =  cocVars.homeUrl + '/wp-content/plugins/coc/assets/images/flags/' +  country.toLowerCase() + '.png';
            let countryName = app.getCountryNameByCountryCode(country);

            messageCountryElement.addClass('loaded');
            messageCountryElement.html(
                '<img src="' + flagUrl + '" title="Eintrag kommt aus ' + countryName + '" alt="Eintrag kommt aus ' + countryName + '" height="35">'
            );
        }
    };

    app.loadCountries = () => {
        $.getJSON(cocVars.homeUrl + '/wp-content/plugins/coc/assets/js/countries.json', function(data) {
            app.countries = data;
        })
    };

    app.getCountryNameByCountryCode = (countryCode) => {
        countryCode.toUpperCase();
        if (countryCode in app.countries) {
            return app.countries[countryCode];
        }

        return '';
    };

    $(document).ready(app.init);

    return app;

})(window, document, jQuery);
