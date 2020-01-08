jQuery(document).ready(function( $ ) {

    // ================================================================
    // Check Is Mobile
    // ================================================================

    var isMobile = function () {
        var check = false;
        (function (a) {
            if (/(android|ipad|playbook|silk|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4)))check = true
        })(navigator.userAgent || navigator.vendor || window.opera);
        return check;
    };


    /*Menu Open Script*/
    $('#mobileMenu .icon__self').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */
        $('#mobile-view').css({ display: 'block'});
        $('.icon__self').css({ display: 'none'});
        $('.icon_close').css({ display: 'block'});
        $('html, body').css({
            overflow: 'hidden',
            height: '100%'
        });
    });

    /*Menu Close Script*/
    $('#mobileMenu .icon_close').on('click', function(event) {
        event.preventDefault();
        /* Act on the event */
        $('#mobile-view').css({ display: 'none'});
        $('.icon__self').css({ display: 'block'});
        $('.icon_close').css({ display: 'none'});
        $('html, body').css({
            overflow: 'initial',
            height: 'initial'
        });
    });


    $('#signin-button, #mobileLogin').on('click', function(event) {
        $('.login-container').css('display', 'block');
        $('.overlay').addClass('overlay-is-enabled');
        $('html, body').css({
            'overflow': 'hidden',
            'height': '100%'
        });
    });

    $('.login_close').on('click', function(event) {
        $('.login-container').css('display', 'none');
        $('.overlay').removeClass('overlay-is-enabled');
        $('html, body').css({
            'overflow-y': 'initial',
            'height': 'initial'
        });
    });

    $('#header_signup').on('click', function(event) {
        $('.reg-container').css('display', 'block');
        $('.overlay').addClass('overlay-is-enabled');
        $('html, body').css({
            'overflow': 'hidden',
            'height': '100%'
        });
    });

    $('.reg_close').on('click', function(event) {
        $('.reg-container').css('display', 'none');
        $('.overlay').removeClass('overlay-is-enabled');
        $('html, body').css({
            'overflow-y': 'initial',
            'height': 'initial'
        });
    });


    if ( $('ul.sports-list li').children('div.filter-box') ) {
        $('ul.sports-list li').children('div.filter-box').siblings().removeAttr('href');
        $('ul.sports-list li').on('click', function(event) {
            $(this).children('.filter-box').slideToggle();
        });
    }
    
    $('.fiveHead h2').on('click', function(event) {
        $(this).siblings('.fiveSub').slideToggle();
    });

        // jQuery back to Top
        $(window).scroll(function () {
            if ($(this).scrollTop() != 0) {
                $('#backToTop').fadeIn();
            } else {
                $('#backToTop').fadeOut();
            }
        });

        $('#backToTop').on('click',function(){
            $("html, body").animate({ scrollTop: 0 }, 1500);
            return false;
        });



        /*Soccer league */
        /*var width= $( window ).width();
        if(width < 767){
            $('.soccer_league').click(function(e) {
                $('#mobile-view, #Mobile-sports-menu').css('display', 'none');
                $('.primier-div-sports').css('display','block');
            });
            $('.icon_close').click(function(e){
                $('.primier-div-sports').css('display','none');
            });
        }else{
           $('.soccer_league').click(function(e) {
                $('.primier-div-sports').slideToggle('fast');

            });
        }*/


        $('ul.tabs li').click(function(){
        var tab_id = $(this).attr('data-tab');

            $('ul.tabs li').removeClass('current');
            $('.tab-content').removeClass('current');

            $(this).addClass('current');
            $("#"+tab_id).addClass('current');
        })

});  // end of jquery main wrapper
