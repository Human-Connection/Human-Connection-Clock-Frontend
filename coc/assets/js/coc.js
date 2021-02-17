/**
 * @var {object} cocVars
 */

window.coc = ((window, document, $) => {
    let app           = {},
        formOverlay   = $('.coc-form-overlay'),
        defaultMsg    = '',
        defaultName   = 'Mensch',
        userImages    = $('.user-image'),
        userMessage   = $('#user-message'),
        userWallIndex = 0,
        entriesCount  = 0,
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

    let fieldMapToFormId = [];
    fieldMapToFormId['firstname'] = 'coc-firstname';
    fieldMapToFormId['lastname'] = 'coc-lastname';
    fieldMapToFormId['email'] = 'coc-email';
    fieldMapToFormId['message'] = 'coc-message';
    fieldMapToFormId['country'] = 'coc-country';
    fieldMapToFormId['anon'] = 'coc-anon';
    fieldMapToFormId['beta'] = 'coc-register-beta';
    fieldMapToFormId['nl'] = 'coc-register-nl';
    fieldMapToFormId['pr'] = 'coc-register-privacy';
    fieldMapToFormId['age'] = 'coc-register-age';
    fieldMapToFormId['slogan'] = 'coc-slogan';
    fieldMapToFormId['file'] = 'coc-add-avatar';
    fieldMapToFormId['error'] = 'form-error';
    fieldMapToFormId['captcha'] = 'coc-captcha';

    let findIndex = (elem, items) => {
        let i, len = items.length;
        for (i = 0; i < len; i++) {
            if (items[i] === elem) {
                return i;
            }
        }
        return -1;
    };

    let userImageClickHandler = function (e) {
        let message = $(this).data('message');
        let uName = $(this).data('uname');
        let anon = $(this).data('anon');
        let src = $(this).attr('src');
        let srcc = this.parentElement;
        let country = $(this).data('country');

        // update index
        userWallIndex = findIndex(srcc, document.getElementsByClassName('user-item'));
        userMessage.find('.user-message-image').attr('src', src);
        if (anon !== undefined && anon === 1) {
            uName = defaultName;
        }
        userMessage.find('.message-name').text(uName);

        userMessage.find('.message-text').text(message);

        app.loadCountryFlagImage(country);

        userMessage.show();
    };

    app.ownImageChangeHandler = function () {
        $('#ownImageUpload').on('change', (event) => {
            if (event.target.files && event.target.files[0]) {
                files = event.target.files;
                let reader = new FileReader();

                reader.onload = function (e) {
                    $('#coc-image')
                        .attr('src', e.target.result)
                        .width(200)
                        .height(200);
                };

                reader.readAsDataURL(event.target.files[0]);
                useOwnImage = true;
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

        const digitsElement = document.querySelector('.display .digits');
        if (digitsElement) {
            entriesCount = digitsElement.getAttribute('data-amount');
        }

        // Dirty Hack to update counter on english pages (url includes path '/en') to avoid acf error & broken counter
        let refreshCounterMiliseconds = 30 * 1000;
        if (window.location.pathname && window.location.pathname.includes('/en/')) {
            refreshCounterMiliseconds = 1;
        }

        window.setTimeout(app.updateCounter, refreshCounterMiliseconds);

        $('#joinCoC').click((e) => {
            // show form
            app.toggleForm();

            e.preventDefault();
        });

        $('.coc-form-close').click(() => {
            app.toggleForm();
        });

        $('#joinNowBtn').click(function (e) {
            e.preventDefault();

            Object.values(fieldMapToFormId).forEach((value) => {
                if ($('#' + value)) {
                    $('#' + value).removeClass('input-error');
                }
            });
            $('.form-error-hint').remove();

            let data = new FormData(),
                emailInput = $('#coc-email'),
                messageInput = $('#coc-message'),
                fnInput = $('#coc-firstname'),
                privacyChecked = $('#coc-register-privacy')[0].checked;

            // append files
            // own image?
            if (useOwnImage === true) {
                if (files !== null && files !== undefined) {
                    data.append('image', files[0]);
                }
            } else {
                data.append('imageUrl', $('#coc-image').attr('src'));
            }

            data.append('firstname', fnInput.val());
            data.append('lastname', $('#coc-lastname').val());
            data.append('email', emailInput.val());
            data.append('message', messageInput.val());
            data.append('country', $('#coc-country').val());
            data.append('anon', $('#coc-anon')[0].checked);
            data.append('beta', '0');
            data.append('nl', $('#coc-register-nl')[0].checked);
            data.append('age', $('#coc-register-age')[0].checked);
            data.append('slogan', $('#coc-slogan')[0].checked);
            data.append('pr', privacyChecked);
            data.append('pr', privacyChecked);
            data.append('captcha', grecaptcha.getResponse());

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
                url: cocVars.ajax_url + 'coc/v2/createEntry/',
                method: 'POST',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', cocVars.nonce);
                },
                success: function (resp) {
                    if (resp !== null && resp.success === true) {
                        // hide form
                        new PNotify({
                            title: app.t('successNotificationTitle', 'Das hat geklappt!'),
                            text: app.t('successNotificationMessage', 'DANKE, dass DU dabei bist. JEDER EINZELNE ZÄHLT! Bitte bestätige jetzt Deine Emailadresse.') ,
                            addclass: 'stack-bottomright',
                            stack: {'dir1': 'up', 'dir2': 'left', 'push': 'top'}
                        });

                        app.toggleForm();

                        setTimeout(function () {
                            window.location.reload();
                        }, 3000);
                    } else {
                        if (resp !== null) {
                            // show errors
                            Object.keys(resp).forEach((key) => {
                                if (key !== 'success' || key !== 'error') {
                                    if ($('#' + fieldMapToFormId[key])) {
                                        $('#' + fieldMapToFormId[key]).addClass('input-error');
                                        let lastElement = $('#' + fieldMapToFormId[key]).next().length ? $('#' + fieldMapToFormId[key]).next() : $('#' + fieldMapToFormId[key])
                                        lastElement.after('<p class="form-error-hint">' + resp[key] + '</p>');
                                    }
                                }
                            });
                        }

                        new PNotify({
                            title: app.t('errorNotificationTitle', 'Ups! Das geht schief!'),
                            text: app.t('errorNotificationMessage', 'Bitte probiere es erneut!'),
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

        $('.user-image').on('click', userImageClickHandler);

        $('.close-wrapper').find('.fa-times').on('click', () => {
            userMessage.hide();
        });

        $('#loadMore').on('click', function (e) {
            offset = offset + 1;
            let urlParams = {};
            urlParams['offset'] = offset;
            urlParams['profileImage'] = $('#profileImage').prop('checked') ? 1 : 0;
            urlParams['orderByDate'] = $('#orderByDate').val() === 'asc' ? 'asc' : 'desc';
            urlParams['country'] = $('#filterByCountry').val();

            $.ajax({
                url: cocVars.ajax_url + 'coc/v2/getEntries/?' + $.param(urlParams),
                method: 'GET',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', cocVars.nonce);
                },
                success: function (data) {
                    // less than page size elements
                    // hide load more btn
                    // TODO: pass param instead of hard 81
                    if (data.length < 81) {
                        $('#loadMore').hide();
                    } else {
                        $('#loadMore').show();
                    }

                    if (data.length > 0) {
                        for (let i = 0; i < data.length; i++) {
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

                        $('.user-image').on('click', userImageClickHandler);
                        userImages    = $('.user-image');
                    } else {
                        // no more data - ensure load more is hidden
                        $('#loadMore').hide();
                    }
                },
                cache: false,
            });

            e.preventDefault();
        });

        $('#profileImage, #orderByDate, #filterByCountry').on('change', function (e) {
            let urlParams = {};
            urlParams['profileImage'] = $('#profileImage').prop('checked') ? 1 : 0;
            urlParams['orderByDate'] = $('#orderByDate').val() === 'asc' ? 'asc' : 'desc';
            urlParams['country'] = $('#filterByCountry').val();

            $.ajax({
                url: cocVars.ajax_url + 'coc/v2/getEntries/?' + $.param(urlParams),
                method: 'GET',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', cocVars.nonce);
                },
                success: function (data) {
                    // less than page size elements
                    // hide load more btn
                    // TODO: pass param instead of hard 81
                    if (data.length < 81) {
                        $('#loadMore').hide();
                    } else {
                        $('#loadMore').show();
                    }

                    if (data.length > 0) {
                        $('.user-container').html('');
                        for (let i = 0; i < data.length; i++) {
                            let obj = data[i];
                            // got merged with lastname on server - output as firstname for compat with old fs
                            let uName = obj.firstname;
                            let msg = obj.message;
                            let loadedImg = obj.image === '' ? cocVars.homeUrl + '/wp-content/plugins/coc/assets/images/coc-placeholder.jpg' : obj.image;
                            let country = obj.country;
                            let img = '<img class="user-image loaded" data-anon="' + obj.anon + '" data-uname="' + uName + '" data-message="' + msg + '" data-country="' + country + '" style="width:100%;margin-top:5px;" alt="signer-image" src="' + loadedImg + '" />';
                            $('.user-container').append(
                                '<div class="user-item">' +
                                img +
                                '</div>'
                            );

                            userImages.push(img);
                        }

                        $('.user-image').on('click', userImageClickHandler);
                        userImages    = $('.user-image');

                    } else {
                        // no more data - ensure load more is hidden
                        $('#loadMore').hide();
                    }
                },
                cache: false,
            });

            e.preventDefault();
        });

        $('#prevMessage').on('click', app.prevMessage);

        $('#nextMessage').on('click', app.nextMessage);

        app.ownImageChangeHandler();

        $('#country-rankings-load-more .load-more-link').on('click', (event) => {
            event.preventDefault();

            $('.country-ranking-item.hidden').slice(0, 12).each((index, element) => {
                $(element).removeClass('hidden');
            });

            if ($('.country-ranking-item.hidden').length === 0) {
                $('#country-rankings-load-more').hide();
            }

            setTimeout(function(){
                $([document.documentElement, document.body]).animate({
                    scrollTop: $('#country-rankings-load-more').offset().top
                }, 1000);
            }, 150);
        });

        defaultName   = app.t('defaultEntryName', 'Mensch');

        let closeIcn = document.getElementsByClassName("close-wrapper");
        let lightBoxWrap = document.getElementsByClassName("user-message-text");
        if (lightBoxWrap.length > 0 && closeIcn.length > 0) {
            lightBoxWrap[0].prepend(closeIcn[0]);
        }

        const messageInputElement = document.querySelector('#coc-message');

        if (messageInputElement) {
            messageInputElement.addEventListener('input',  (event) => {
                let value = event.target.value;
                const counterElement = document.getElementById('messageCounter');
                counterElement.textContent =  value.length + '/500';
            });
        }
    };

    $(window).off('keyup').on('keyup', (e) => {
        if (e.which === 27) {
            app.closeForm();
            app.closeUserOverlay();
        }
        if (e.which === 37) {
            app.prevMessage();
        }
        if (e.which === 39) {
            app.nextMessage();
        }
    });

    app.toggleForm = () => {
        if (formOverlay.css('display') === 'block')
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
            let entryLabel = app.t('entryIsFrom', 'Eintrag kommt aus');

            messageCountryElement.addClass('loaded');
            messageCountryElement.html(
                '<img src="' + flagUrl + '" title="' + entryLabel + ' ' + countryName + '" alt="' + entryLabel + ' ' + countryName + '" height="35">'
            );
        }
    };

    app.loadCountries = () => {
        let countryFileName = 'countries_en.json';
        if (typeof( cocVars.language) !== 'undefined' && cocVars.language === 'de') {
            countryFileName = 'countries_de.json';
        }

        $.getJSON(cocVars.homeUrl + '/wp-content/plugins/coc/assets/translation/' + countryFileName, function(data) {
            app.countries = data;
        })
    };

    app.getCountryNameByCountryCode = (countryCode) => {
        countryCode.toUpperCase();
        if (app.countries && countryCode in app.countries) {
            return app.countries[countryCode];
        }

        return '';
    };

    app.translate = (key, fallback = '') => {
        if (cocVars.translation && typeof(cocVars.translation[key]) !== 'undefined' && cocVars.translation[key] !== '') {
            return cocVars.translation[key];
        }

        return fallback;
    }

    app.t = (key, fallback = '') => {
        return app.translate(key, fallback);
    }

    app.updateCounter = () => {
        const cocNumberOfDigits = 8;
        const refreshCounterEachNumberOfSeconds = 30;
        const digitClassMap = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];

        $.ajax({
            url: cocVars.ajax_url + 'coc/v2/getCount',
            method: 'GET',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', cocVars.nonce);
            },
            success: function (data) {
                let count = Number.parseInt(data);
                if (count > 0 && Number.parseInt(entriesCount) > 0 && count !== Number.parseInt(entriesCount)) {
                    const countString = count.toString().padStart(cocNumberOfDigits, '0');
                    const digitElements = document.querySelectorAll('.digits .number');

                    for (i = 0; i < digitElements.length; i++) {
                        digitElements[i].firstChild.className = digitClassMap[countString[i]];
                    }
                }
            },
            cache: false,
        })

        window.setTimeout(app.updateCounter, refreshCounterEachNumberOfSeconds * 1000);
    }

    app.prevMessage = () => {
        if (userWallIndex <= 0) {
            userWallIndex = userImages.length - 1;
        } else {
            userWallIndex--;
        }

        let nextData = $(userImages[userWallIndex]);
        let msg = nextData.data('message');

        userMessage.find('.user-message-image').attr('src', nextData[0].src);
        userMessage.find('.message-text').text(msg);

        let uName = nextData.data('uname');
        if (nextData.data('anon') === 1) {
            uName = defaultName;
        }
        userMessage.find('.message-name').text(uName);

        let country = nextData.data('country');
        app.loadCountryFlagImage(country);
    };

    app.nextMessage = () => {
        if (userWallIndex >= userImages.length - 1) {
            userWallIndex = 0;
        } else {
            userWallIndex++;
        }

        let nextData = $(userImages[userWallIndex]);
        let msg = nextData.data('message');

        userMessage.find('.user-message-image').attr('src', nextData[0].src);
        userMessage.find('.message-text').text(msg);

        let uName = nextData.data('uname');
        if (nextData.data('anon') === 1) {
            uName = defaultName;
        }
        userMessage.find('.message-name').text(uName);

        let country = nextData.data('country');
        app.loadCountryFlagImage(country);
    };

    $(document).ready(app.init);

    return app;

})(window, document, jQuery);
