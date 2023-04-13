class Menu {

	init() {

		// Animate logo init.
		this.logoAnimate()

		// Menu click handler.
		jQuery('#sm-menu li').click( function() {

			// Hide all the sections and then show the target section.
			const section = jQuery(this).attr('section')
			const navItem = jQuery(this)
			jQuery('#sm-menu li').removeClass('sm-section-active')
			navItem.addClass('sm-section-active')
			jQuery( '.sm-section' ).hide()
			jQuery( '#section-' + section ).show()

			// Init reports if selected by user.
			if( section === 'reports' ) {
				const reports = new Reports()
				reports.init()
			}

			// MetricLog init.
			if( section == 'metrics' ) {
				var metricLog = new MetricLog;
				metricLog.init()
			}

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
