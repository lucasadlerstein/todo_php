$(document).ready(function() {
    // // Menu Fijo
    // var windowHeight = $(window).height();
    // var barraHeight = $('.barra').innerHeight();
    // $(window).scroll(function() {
    //     var scroll = $(window).scrollTop();
    //     if (scroll > windowHeight) {
    //         $('.barra').addClass('fixed');
    //         $('body').css({ 'margin-top': barraHeight + 'px' });
    //     } else {
    //         $('.barra').removeClass('fixed');
    //         $('body').css({ 'margin-top': '0px' });
    //     }
    // });

    // Menu Mobile
    $('.menu-mobile').on('click', function() {
        $('.contenedor-proyectos').slideToggle();
    });


});