class Menu {

	init() {

		this.logoAnimate()

		jQuery('#sm-menu li').click( function() {
			console.log('click menu...')
			const section = jQuery(this).attr('section')
			console.log(section)
			const navItem = jQuery(this)
			jQuery('#sm-menu li').removeClass('sm-section-active')
			navItem.addClass('sm-section-active')
			jQuery( '.sm-section' ).hide()
			jQuery( '#section-' + section ).show()

		})

	}

	logoAnimate() {

		jQuery(document).ready(function() {
		  var $svg = jQuery('#metric-logo');
		  $svg.addClass('scale-up');
		  setTimeout(function() {
		    $svg.removeClass('scale-up');
		  }, 500);
		});


	}

}

const menu = new Menu()
menu.init()
