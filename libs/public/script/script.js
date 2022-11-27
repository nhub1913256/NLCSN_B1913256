$("#mega-menu").hover(
    function () {
        $(".menu-mega-content").addClass('show-mega-menu');
    },
    function () {
        $(".menu-mega-content").removeClass('show-mega-menu');
    }
)

$(".menu-mega-content").hover(
    function () {
        $(this).addClass("show-mega-menu");
        $("#mega-menu").addClass("hover-mega-menu")
    },
    function () {
        $(this).removeClass('show-mega-menu');
        $("#mega-menu").removeClass("hover-mega-menu")
    }
)

var numItems1 = $('#block-1 #slide-product .slider-products-first .product-box').length
var countSlide1 = 0
var moveSlide1 = 230
$("#block-1 #slide-product .btn-slide-right").click(function () {
    countSlide1++
    if (countSlide1 <= (numItems1 - 3)) {
        $("#block-1 #slide-product .slider-products-first").css('left', '-' + countSlide1 * moveSlide1 + 'px')
    }

    if (countSlide1 >= (numItems1 - 3)) {
        countSlide1 = (numItems1 - 3)
    }
})

$("#block-1 #slide-product .btn-slide-left").click(function () {
    countSlide1--
    if (countSlide1 >= 0) {
        $("#block-1 #slide-product .slider-products-first").css('left', '-' + countSlide1 * moveSlide1 + 'px')
    } else {
        countSlide1 = 0
    }
})

var numItems2 = $('#block-2 #slide-product .slider-products-first .product-box').length
var countSlide2 = 0
var moveSlide2 = 230
$("#block-2 #slide-product .btn-slide-right").click(function () {
    countSlide2++
    if (countSlide2 <= (numItems2 - 3)) {
        $("#block-2 #slide-product .slider-products-first").css('left', '-' + countSlide2 * moveSlide2 + 'px')
    }

    if (countSlide2 >= (numItems2 - 3)) {
        countSlide2 = (numItems2 - 3)
    }
})

$("#block-2 #slide-product .btn-slide-left").click(function () {
    countSlide2--
    if (countSlide2 >= 0) {
        $("#block-2 #slide-product .slider-products-first").css('left', '-' + countSlide2 * moveSlide2 + 'px')
    } else {
        countSlide2 = 0
    }
})

//

var numItems3 = $('#block-3 #slide-product .slider-products-first .product-box').length
var countSlide3 = 0
var moveSlide3 = 230
$("#block-3 #slide-product .btn-slide-right").click(function () {
    countSlide3++
    if (countSlide3 <= (numItems3 - 3)) {
        $("#block-3 #slide-product .slider-products-first").css('left', '-' + countSlide3 * moveSlide3 + 'px')
    }

    if (countSlide3 >= (numItems3 - 3)) {
        countSlide3 = (numItems3 - 3)
    }
})

$("#block-3 #slide-product .btn-slide-left").click(function () {
    countSlide3--
    if (countSlide3 >= 0) {
        $("#block-3 #slide-product .slider-products-first").css('left', '-' + countSlide3 * moveSlide3 + 'px')
    } else {
        countSlide3 = 0
    }
})

//

var numItems4 = $('#block-4 #slide-product .slider-products-first .product-box').length
var countSlide4 = 0
var moveSlide4 = 230
$("#block-4 #slide-product .btn-slide-right").click(function () {
    countSlide4++
    if (countSlide4 <= (numItems4 - 3)) {
        $("#block-4 #slide-product .slider-products-first").css('left', '-' + countSlide4 * moveSlide4 + 'px')
    }

    if (countSlide4 >= (numItems4 - 3)) {
        countSlide4 = (numItems4 - 3)
    }
})

$("#block-4 #slide-product .btn-slide-left").click(function () {
    countSlide4--
    if (countSlide4 >= 0) {
        $("#block-4 #slide-product .slider-products-first").css('left', '-' + countSlide4 * moveSlide4 + 'px')
    } else {
        countSlide4 = 0
    }
})
