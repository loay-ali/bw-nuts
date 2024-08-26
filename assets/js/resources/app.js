(function($) {

    //Toggle Auth Window
    $('body').on('click','.bw-toggle-auth-window',e => {
        $('#customer_login > *').toggle();
    });

    //Back To Top
    $('body').on('click','#back-to-top',e => {

        window.scroll({
            top: 0,
            left: 0,
            behavior: 'smooth'
        });
    });

    //Toggle Menu
    $('body').on('click','.bw-toggle-menu',e => {

        $('#bw-header-menu').toggleClass('active');
    });

    //Numbers
    $('body').on('click','.bw-number-field button',e => {

        const $ele = $(e.currentTarget);
        const num = $ele.siblings('input');

        const step = (num.attr('step') == undefined ? 1:Number(num.attr('step')));

        if( $ele.text() == '+' ) {

            const max = Number(num.attr('max'));

            if( num.attr('max') == undefined || Number(num.val()) + step <= max ) {
                num.val(Number(num.val()) + step);
            }
        }else {

            const min = Number(num.attr('min'));

            if( num.attr('min') == undefined || Number(num.val()) - step >= min ) {
                num.val(Number(num.val()) - step);
            }
        }
    });

	//Window Element.
	$('body').on('click','.bw-open-window[bw-window]',({currentTarget}) => {

		const $ele = $(currentTarget);

		const window = $ele.attr('bw-window');

		$('#'+ window).fadeIn();
	});

	$('.bw-window .layout,.bw-close-window').click((e) => {
		
		let $window = $(e.currentTarget).parent();
		
		if($(e.currentTarget).hasClass('bw-close-window'))
			$window = $window.parent();
		
		$window.fadeOut();
	});

})(jQuery);