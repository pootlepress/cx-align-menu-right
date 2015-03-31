/* ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- 
 * Document:	align-menu-right.js
 * Description:	PootlePress Align Menu Right javascript module.
 * Author:		PootlePress
 * Author URI:	http://pootlepress.com
 * Version:		1.0.2
 * Date:		2-May-2014
 * ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- 
*/
(function( $ ) {

    $.fn.alignMenuRight = function(o) {
    	var debug	= false,
    		poo = {
    		options			:	o,
    		viewportW		:	window.innerWidth, // this is the width that media query in css is tested against
    		isMobile		:	(window.innerWidth < 768) ? true : false,
    		isFullWidth		:	($('body').hasClass("full-header")) ? true : false,
    		isBoxedLayout	:	($('body').hasClass("boxed-layout")) ? true : false,
    		isFixedMobile	:	($('body').hasClass("fixed-mobile")) ? true : false,
    		opacityLayers	:	false,
    		elm		:	0,
    		nav		:	0,
    		hdr		:	0,			// header element: #header-container if full width, otherwise #header
    		navSticky :	0,			// standard nav element used when not right aligned
    		alignRightNav : { clear : 'none', float : 'right', width : 'auto', marginBottom: 0 },
    		logoMods	:	{ clear : 'none', float : 'left',  width : 'auto' },    		
    		headerMods	:	{ paddingBottom : 0 },
    		stickyLayr	:	{ position : 'fixed', top : 0, display : 'block' },
    		stickyHdr	:	{ position : 'fixed', top : 0, display : 'block', zIndex : 9003 },
    		stickyNav 	: 	{ position : 'fixed', top : 0, display : 'block', zIndex : 10000 },
    		stickyTopNav: 	{ position : 'fixed', top : 0, display : 'block', zIndex : 9004, width : '100%' },
    		stickyMobileHdr	:	{ zIndex : 9003 },
    		hdrDims				:	{ width : 'auto', height : 'auto' },
    		// header layers for opacity 
    		hdrBackdropCss		:	{ position : 'absolute', top : 0, zIndex : '9001' },
    		hdrBackgroundCss	:	{ position : 'absolute', top : 0, zIndex : '9002', opacity : 0.2 },
    		hdrCss				: 	{ position : 'relative', display : 'block', background : 'none', zIndex : 9003 },
    		noTransparency		:	{ backgroundColor: '#ffffff' }
    			};
		return this.each(function() {
			if (debug) console.log( "viewWidth=" + poo.viewportW +"\n" +
                "Window Width: " + $(window).width() + "\n" +
                "Window Inner Width: " + window.innerWidth + "\n" +
                JSON.stringify(poo.options));
			// setup ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
			pootlepress.stickyHdrOptions = o;		// save my presence in global variable
            pootlepress.poo = poo;
            pootlepress.hasRunBefore = true;
			poo.elm = $(this); poo.hdr = $(this);
			poo.nav = ( poo.isFullWidth ) ? $('#nav-container') : $('#navigation');
			poo.noTransparency.backgroundColor = $('body').css( 'background-color' );
			poo.hdrDims.height			= poo.hdr.height() + "px";
			poo.hdrDims.innerHeight		= poo.hdr.innerHeight() + "px";
			poo.hdrDims.outerHeight		= poo.hdr.outerHeight() + "px";
			poo.hdrDims.trueOuterHeight	= poo.hdr.outerHeight(true) + "px";
			poo.hdrDims.width			= poo.hdr.width() + "px";
			poo.hdrDims.innerWidth		= poo.hdr.innerWidth() + "px";
			poo.hdrDims.outerWidth		= poo.hdr.outerWidth() + "px";
			poo.hdrDims.trueOuterWidth	= poo.hdr.outerWidth(true) + "px";
			if (debug) console.log(JSON.stringify(poo.hdrDims));
			if ( poo.isFullWidth ) {
	 			poo.stickyHdr.width = '100%';
	 			poo.stickyNav.width = '100%';
				poo.hdr = poo.elm.parent();
				poo.elm.css( { backgroundColor : 'rgba(0, 0, 0, 0)' } );	// conflict
				poo.elm.css( { backgroundImage : 'none' } );				// avoidance
			} else if ( poo.isBoxedLayout ) {
				poo.stickyHdr.top += parseInt($('#inner-wrapper').css('border-top-width'));
				poo.stickyHdr.width = poo.hdrDims.width;
 				poo.stickyNav.width = poo.nav.css('width');
 			} else if ( poo.isFixedMobile && poo.isMobile ) {
    			poo.stickyNav.width = '100%';
			} else {
				poo.stickyHdr.width =  poo.hdrDims.trueOuterWidth;
 				poo.stickyNav.width = poo.options.layoutWidth;
				poo.nav.css('min-height', '0');
			}


            if (poo.isMobile) {
//                console.log('Is Mobile: ' + window.innerWidth + '\n');
//                if ($('#wpadminbar').height() > 0) {
//                    var offset = $('#navigation').offset();
//                    if (offset.top == 0) {
//                        var top = $('#navigation').css('top');
//                        var topInt = parseInt(top.replace('px', ''));
//
//                        topInt += $('#wpadminbar').height();
//                        $('#navigation').css('top', topInt + 'px');
//                    }
//                }
                $('#navigation').css('width', ($('body').innerWidth() * 0.8) + 'px');
				
				//Explicitly setting nav height equal to #inner-wrapper
				var inner_wrap_height = $('#inner-wrapper').height();
				$('#navigation').css('height', inner_wrap_height);

            } else {
                $('#navigation').css({width: '100%',height: ''});
            }

            poo.hdr.attr('bg-color', poo.hdr.css('background-color'));

			// move nav menu if align right option selected ----- ----- ----- ----- ----- 
			if ( poo.options.alignright && !poo.isMobile) {				// not for mobile
                $('#logo').css(poo.logoMods);
                $('.header-widget').after(poo.nav.css(poo.alignRightNav));
            }

    	});
    };

    $(window).resize(function () {
        resizeCheck();
    });

    $(window).load(function () {
        resizeCheck();
    });


    function resizeCheck() {
        if (typeof pootlepress != 'undefined' && typeof pootlepress.hasRunBefore != 'undefined') {
//            console.log('Run check\n');
            $('#header').alignMenuRight(pootlepress.stickyHdrOptions);

        } else {
//            console.log('Don\'t run check\n');
        }
    }

} ( jQuery ));
