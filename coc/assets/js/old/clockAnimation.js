jQuery(document).ready(function ($) {

    var joining;
    var people;
    var clock;
    var numbers;
    var amount;
    var currentAmount;
    var radius;
    var locked;
    var mobile;
    var tablet;
    var tl;
    var tlAddPerson;
    var tlRotation;
    var tlClock;
    var highlightRandomIntervall;

    //resize timeout vars
    var rtime;
    var timeout;
    var delta;

//    function init() {
//        $.get("COC.html", function (data) {
//            $('#worldAnimationContainer').html(data);
//        }).complete(createAnimation);
//    }
    createAnimation();
    function createAnimation() {

        joining = $('#person_1');
        people = $('.figurchen').not('#person_1');
        clock = $('#clock');
        numbers = $('#clock .number');
        amount = 35;
        currentAmount = amount;
        radius = null;
        locked = false;
        tablet = null;
        mobile = null;
        tl = new TimelineMax({paused: true});
        tlRotation = new TimelineMax({paused: true, repeat: -1});
        tlClock = new TimelineMax({paused: true});
        tlAddPerson = new TimelineMax({paused: true});
        cocSmall = $('.hc-coc.coc-small').length !== 0;
        cocMedium = $('.hc-coc.coc-medium').length !== 0;
        //resize timeout vars

        rtime;
        timeout = false;
        delta = 200;

        addEnquieres();
        addEvents();
    }

    function addEnquieres() {
        enquire.register("screen and (min-width:767px)", {
//Three rows
            match: function () {

//                TweenMax.to($('.number'), .5 , {scale: 1});

                tl.pause();
                tlRotation.pause();
                tlClock.pause();

                tablet = false;
                radius = 211;
                createInitTL();
                createRotationTL();
                createAddPersonTL();
                locked = true;
                setTimeout(function () {
                    locked = false;
                    tlClock.play();
                }, 4000);

                tl.play('start');
                setTimeout(function () {

                    createClockTL();
                    tlRotation.play();
                }, 3000);

            },
        });
        enquire.register("screen and (min-width:430px) and (max-width:767px)", {
            //Three rows
            match: function () {
//                TweenMax.to($('.number'), .5 , {scale: 0.5});

                tl.pause();
                tlRotation.pause();
                tlClock.pause();
                mobile = false;

                tablet = true;
                radius = 146;
                createInitTL();
                createRotationTL();
                createAddPersonTL();
                locked = true;
                setTimeout(function () {
                    locked = false;
                    tlClock.play();
                }, 3000)

                tl.play('start');
                setTimeout(function () {

                    createClockTL();
                    tlRotation.play();
                }, 4000);
            },
        });
        enquire.register("screen and (max-width:430px)", {
            //Three rows
            match: function () {
//                TweenMax.to($('.number'), .5 , {scale: 0.5});

                tl.pause();
                tlRotation.pause();
                tlClock.pause();
                mobile = true;
                tablet = false;
                radius = 102;
                createInitTL();
                createClockTL();
                createRotationTL();
                createAddPersonTL();
                locked = true;
                setTimeout(function () {
                    locked = false;
                    tlClock.play();
                }, 3000)

                tl.play('start');
                tlRotation.play();
            },
        });
    }

    function addEvents() {
        $('body').on('click', '.controls .play', function () {
            playAnimation();
        });
        $('body').on('click', '.controls .pause', function () {
            pauseAnimation();
        });
        $('body').on('click', '.controls .addPerson', function () {
            addPerson();
        });
        $('body').on('click', '.controls .removePerson', function () {
            removePerson();
        });
        $('body').on('click', '.controls .rotateTop', function () {
            rotatePause();
            rotateTop();
        });
        $('body').on('click', '.controls .rotateStart', function () {
            rotateStart();
        });
        $('body').on('click', '.controls .rotateStop', function () {
            rotatePause();
        });
        $('body').on('click', '.controls .reverse', function () {
            reverseAnimation();
        });
        $('body').on('click', '.controls .highlightRandom', function () {
            highlightPerson();
        });
        $('body').on('click', '#cocJoin', function () {
            setTimeout(function () {

                removePerson();
                rotatePause();
                rotateTop();
            }, 1000)

        });
        $('body').on('click', '.fusion-modal.declaration .modal-footer .fusion-button,  .fusion-modal.declaration .modal-header button', function () {
//            rotateStart();
            addPerson();
        });

    }

    function createRotationTL() {
        tlRotation.to($('#worldAnimationContainer'), 160, {rotation: 360, ease: Power0.easeNone});
    }
    function rotateTop() {
        TweenMax.to($('#worldAnimationContainer'), 1, {rotation: 362, ease: Power0.easeNone, onComplete: function () {
                tlRotation.progress(0);
            }
        });
    }
    function removePerson() {
        tlAddPerson.reverse();
    }
    function addPerson() {
        $(window).scrollTop(100);
        setTimeout(function () {

//                addPerson();


            if (!$('body').hasClass('state-animating'))
                tlAddPerson.play();
            setTimeout(function () {
                rotateStart();
            }, 3000)
        }, 1500)
    }
    function playAnimation() {
        tl.play();
    }
    function pauseAnimation() {
        tl.pause();
    }
    function reverseAnimation() {

        if ($('body').hasClass('cocPersonAdded')) {
            removePerson();

            setTimeout(function () {
                tl.reverse();
            }, tlAddPerson.duration() * 1000);
        } else {
            tl.reverse();
        }

    }
    function rotateStart() {
        tlRotation.play();
    }
    function rotatePause() {
        tlRotation.pause();
    }


    function createInitTL() {
        if (cocSmall) {
            radius = 70;
        } else if (cocMedium) {
            radius = 138;
        }
        tl = null;
        tl = new TimelineMax({paused: true, });
        tl.add('start');
        tl.add(function () {
            $('body').scrollTop(0);
            tlRotation.progress(0);
        });
        tl.add('startWorld = start+0');
        tl.set($('#world'), {opacity: 1, x: '-50%', y: '-50%'}, 'start+=0');
        tl.set($('#person_1'), {x: 0, y: 0, opacity: 0}, 'start+=0');
        tl.set($('body'), {className: '+=state-animating'}, 'start+=0');

        tl.set($(people), {opacity: 0}, 'start+=0');
        tl.fromTo($('#world'), 2, {scale: 0.1}, {scale: 1, z:0.01, ease: Power0.easeNone}, 'start+=0.05');
        //INIT Timeline

//        tl.add('startPeople+=start+5');
        var cnt = 0;
        people.each(function () {
            var rot = 360 / amount * cnt - 80;
            if (mobile) {
                x = Math.cos(toRadians(rot)) * radius - 15;
                y = Math.sin(toRadians(rot)) * radius - 15;
            } else {

                if (tablet) {
                    x = Math.cos(toRadians(rot)) * radius - 25;
                    y = Math.sin(toRadians(rot)) * radius - 25;
                } else {
                    x = Math.cos(toRadians(rot)) * radius - 38;
                    y = Math.sin(toRadians(rot)) * radius - 38;
                }
            }
            if (cocSmall) {
                x = Math.cos(toRadians(rot)) * radius - 18;
                y = Math.sin(toRadians(rot)) * radius - 18;
            } else if (cocMedium) {
                x = Math.cos(toRadians(rot)) * radius - 35.2;
                y = Math.sin(toRadians(rot)) * radius - 35.2;
            }


            tl.set($(this), {opacity: 1, rotation: 85 + rot}, 'start+=' + (0.05 * cnt + 3.3));
            tl.fromTo($(this), 1, {x: 0, y: 0}, {x: x, y: y, ease: Power1.easeOut}, 'start+=' + (0.08 * cnt + 3.3));

//            tl.set($(this), {zIndex: cnt}, 'startPeople+=' + (0.08 * cnt + 1));
            $(this).data('index', cnt);
            $(this).data('PersonX', x);
            $(this).data('PersonY', y);
            cnt++;
        });
        tl.set($('body'), {className: '-=state-animating'});
    }


    function createClockTL() {
        tlClock.add('start');
        var cnt = 0;
        $(numbers.get().reverse()).each(function () {
            tlClock.to($(this), 1, {opacity: 1}, 'start+=' + cnt * 0.2);
            cnt++;
        });
    }

    //AddPerson Timeline
    function createAddPersonTL() {
        var joiningX, joiningY, rot;

        locked = true;
        setTimeout(function () {
            locked = false;
        }, 1000)
        tlAddPerson = new TimelineMax({paused: true});

        $(this).css('cursor', 'auto');
        var peopleBefore = $('.figurchen').not('#person_1');
        var peopleAfter = $('.figurchen');
        var amountAfter = 36;
        var cnt = 0;
        tlAddPerson.add('startAdding');
        currentAmount = amount;
        tlAddPerson.set($('body'), {className: '-=cocPersonAdded'});
        peopleBefore.each(function () {

            rot = 360 / amountAfter * cnt - 80;
            if (mobile) {
                x = Math.cos(toRadians(rot)) * radius - 15;
                y = Math.sin(toRadians(rot)) * radius - 15;
                joiningX = Math.cos(toRadians(-90)) * radius - 15;
                joiningY = Math.sin(toRadians(-90)) * radius - 15;
            } else {
                if (tablet) {
                    x = Math.cos(toRadians(rot)) * radius - 25;
                    y = Math.sin(toRadians(rot)) * radius - 25;
                    joiningX = Math.cos(toRadians(-90)) * radius - 25;
                    joiningY = Math.sin(toRadians(-90)) * radius - 25;
                } else {
                    x = Math.cos(toRadians(rot)) * radius - 38;
                    y = Math.sin(toRadians(rot)) * radius - 38;
                    joiningX = Math.cos(toRadians(-90)) * radius - 38;
                    joiningY = Math.sin(toRadians(-90)) * radius - 38;
                }
            }

            tlAddPerson.to($(this), 1, {x: x, y: y, rotation: 90 + rot}, 'startAdding+=' + 0);
            $(this).data('index', cnt);
            $(this).data('PersonX', x);
            $(this).data('PersonY', y);
            cnt++;
        });
        rot = 360 / amountAfter * cnt - 80;
        if (mobile) {
            joiningX = Math.cos(toRadians(-90)) * radius - 15;
            joiningY = Math.sin(toRadians(-90)) * radius - 15;
        } else {
            if (tablet) {

                joiningX = Math.cos(toRadians(-90)) * radius - 25;
                joiningY = Math.sin(toRadians(-90)) * radius - 25;
            } else {

                joiningX = Math.cos(toRadians(-90)) * radius - 38;
                joiningY = Math.sin(toRadians(-90)) * radius - 38;
            }
        }
        tlAddPerson.set(joining, {rotation: 0}, 'startAdding+=' + 0);
        tlAddPerson.to(joining, 1, {x: joiningX, y: joiningY, opacity: 1}, 'startAdding+=' + 1);

        joining.data('index', 0);
        joining.data('x', joiningX);
        joining.data('y', joiningY);

        tlAddPerson.add(function () {

            currentAmount = amount + 1;
        });
        tlAddPerson.set($('body'), {className: '+=cocPersonAdded'});

    }

    function highlightPerson() {
        if ($(this).hasClass('figurchen')) {

            var el = $(this);
        } else {
            var el = $('.figurchen').not('#person_1').random();
        }
        var index = el.data('index');
        var xOld;
        var yOld;

        var xNew, yNew;

        var rot = 360 / currentAmount * index - 80;
        if (mobile) {
            var newRadius = radius + 10;
            xNew = Math.floor(Math.cos(toRadians(rot)) * newRadius - 15);
            yNew = Math.floor(Math.sin(toRadians(rot)) * newRadius - 15);
            xOld = Math.floor(Math.cos(toRadians(rot)) * radius - 15);
            yOld = Math.floor(Math.sin(toRadians(rot)) * radius - 15);
            TweenMax.to(el, 1, {x: xNew, y: yNew, scale: 1.4});
            TweenMax.to(el, 1, {x: xOld, y: yOld, scale: 1, delay: 2});
        } else {
            if (tablet) {
                var newRadius = radius + 10;
                xNew = Math.floor(Math.cos(toRadians(rot)) * newRadius - 25);
                yNew = Math.floor(Math.sin(toRadians(rot)) * newRadius - 25);
                xOld = Math.floor(Math.cos(toRadians(rot)) * radius - 25);
                yOld = Math.floor(Math.sin(toRadians(rot)) * radius - 25);
                TweenMax.to(el, 1, {x: xNew, y: yNew, scale: 1.4});
                TweenMax.to(el, 1, {x: xOld, y: yOld, scale: 1, delay: 2});
            } else {
                var newRadius = radius + 20;
                xNew = Math.cos(toRadians(rot)) * newRadius - 38;
                yNew = Math.sin(toRadians(rot)) * newRadius - 38;
                xOld = Math.cos(toRadians(rot)) * radius - 38;
                yOld = Math.sin(toRadians(rot)) * radius - 38;
                TweenMax.to(el, 1, {x: xNew, y: yNew, scale: 1.5});
                TweenMax.to(el, 1, {x: xOld, y: yOld, scale: 1, delay: 2});
            }
        }
    }

    function increaseCOC() {
        var amount = $('.digits').data('amount');
        var digits = getlength(amount);

//        $('').
        $($(".number").get().reverse()).each(function () {

//            $(this).children().removeAttr('class');
//            $(this).children().removeAttr('class');

            var className;

//for(var cnt; digits)
//            switch (zahl)
//
//            {
//            case 0:
//                    className = "zero";
//                    break;
//                    case 1:
//                    className = "one";
//                    break;
//                    case 2:
//                    className = "two";
//                    break;
//                    case 3:
//                    className = "three";
//                    break;
//                    case 4:
//                    className = "four";
//                    break;
//                    case 5:
//                    className = "five";
//                    break;
//                    case 6:
//                    className = "six";
//                    break;
//                    case 7:
//                    className = "seven";
//                    break;
//                    case 8:
//                    className = "eight";
//                    break;
//                    case 9:
//                    className = "nine";
//                    break;
//        }

        });
    }
//    increaseCOC();

    // add class if dom loaded

    $('#clock').addClass('coc-ready');

    function toRadians(angle) {
        return angle * (Math.PI / 180);
    }
    function getlength(number) {
//        return number.toString().length;
    }

    function shuffleArray(array) {
        for (var i = array.length - 1; i > 0; i--) {
            var j = Math.floor(Math.random() * (i + 1));
            var temp = array[i];
            array[i] = array[j];
            array[j] = temp;
        }
        return array;
    }

//    init();
    $.fn.random = function () {
        return this.eq(Math.floor(Math.random() * this.length));
    }
});
