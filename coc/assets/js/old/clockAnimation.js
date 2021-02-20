jQuery(document).ready(function ($) {
    var clock;
    var amount;
    var currentAmount;
    var radius;
    var locked;
    var mobile;
    var tablet;
    var tl;
    var tlRotation;
    var highlightRandomIntervall;

    //resize timeout vars
    var rtime;
    var timeout;
    var delta;

    createAnimation();
    function createAnimation() {
        clock = $('#clock');
        amount = 35;
        currentAmount = amount;
        radius = null;
        locked = false;
        tablet = null;
        mobile = null;
        tl = new TimelineMax({paused: true});
        tlRotation = new TimelineMax({paused: true, repeat: -1});
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
                tl.pause();
                tlRotation.pause();

                tablet = false;
                radius = 212;
                createInitTL();
                createRotationTL();
                locked = false;

                tl.play('start');
                tlRotation.play();

            },
        });
        enquire.register("screen and (min-width:430px) and (max-width:767px)", {
            //Three rows
            match: function () {
                tl.pause();
                tlRotation.pause();
                mobile = false;
                tablet = true;
                radius = 147;
                createInitTL();
                createRotationTL();
                locked = false;
                tl.play('start');
                tlRotation.play();
            },
        });
        enquire.register("screen and (max-width:430px)", {
            //Three rows
            match: function () {
                tl.pause();
                tlRotation.pause();
                mobile = true;
                tablet = false;
                radius = 104;
                createInitTL();
                createRotationTL();
                locked = false;
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
                rotatePause();
                rotateTop();
            }, 1000)

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

    function playAnimation() {
        tl.play();
    }
    function pauseAnimation() {
        tl.pause();
    }
    function reverseAnimation() {
        tl.reverse();
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

        tl.fromTo($('#world'), 2, {scale: 0.1}, {scale: 1, z:0.01, ease: Power0.easeNone}, 'start+=0.05');
        //INIT Timeline
        tl.set($('body'), {className: '-=state-animating'});
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

    // add class if dom loaded

    $('#clock').addClass('coc-ready');

    function toRadians(angle) {
        return angle * (Math.PI / 180);
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

    $.fn.random = function () {
        return this.eq(Math.floor(Math.random() * this.length));
    }
});
