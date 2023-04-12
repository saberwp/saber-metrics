class Reports {

	init() {

		console.log('reports init...')

		// Init chart.

			// Get the canvas element
		  var ctx = document.getElementById('saber-chart').getContext('2d');

		  // Check if a chart already exists on the canvas
		  var existingChart = Chart.getChart(ctx);

		  if (existingChart) {
		    // Destroy the existing chart if it exists
		    existingChart.destroy();
		  }

		const reportChart = new ReportChart();
		reportChart.renderChart();

		// Fetch report data for default metric_id 13 and today date.
		this.fetch()

	}

	fetch() {

		jQuery.ajax({
		  type: 'POST',
		  url: ajaxurl,
		  data: {
		    action: 'metric_report_data_fetch',
		    data: {
					metric_id: 13 // Replace with the metric ID you want to fetch data for
				}
		  },
		  success: function(response) {
		    console.log(response);
				const reports = new Reports()
				reports.render( response.data )
		  },
		  error: function(xhr, status, error) {
		    console.log('Error:', error);
		  }
		});

	}

	render( data ) {

		const logs = data.logs

		// Use stat <template> markup to create the stats.

		const statComponent = jQuery('#sm-stat-component').get(0).content.cloneNode(true);
		const targetContainer = jQuery('.sm-stats-row');

		// Wrap the statComponent fragment in a new element
		const statElement = jQuery('<div>').append(statComponent);

		// Append the new element to the target container
		statElement.appendTo(targetContainer);

		// Modify the elements in the statComponent markup as needed
		statElement.find('.sm-stat-value').text('100');
		statElement.find('.sm-stat-label').text('New Label');


		// Init the chart report with the labels and data created from the return results.




	}

}
