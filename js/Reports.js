class Reports {

	init() {

		const metricId = jQuery('#field-metric-id').val()

		// Fetch report data for today's date.
		this.fetch( metricId )

		// Setup change event.
		this.filterInitMetric();

	}

	/**
	 * Initializes the metric filter by adding a change event listener to the metric ID select field (#field-metric-id).
	 * The listener fetches the new data for the selected metric and re-renders the chart with the updated data.
	 *
	 * @return void
	 */
	filterInitMetric() {
	  const metricSelect = jQuery('#sm-report-filter-metric');

	  metricSelect.on('change', () => {
			console.log('metric changed...')
	    const metricId = metricSelect.val();
	    this.fetch(metricId);
	  });
	}


	fetch( metricId ) {

		// Clear stats.
		this.statsClear()

		// Clear reports.
		this.reportClear()

		jQuery.ajax({
		  type: 'POST',
		  url: ajaxurl,
		  data: {
		    action: 'metric_report_data_fetch',
		    data: {
					metric_id: metricId // Replace with the metric ID you want to fetch data for
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

	/**
	 * Calculates the log count, total value, and average value of an array of logs.
	 *
	 * @param {Array} logs An array of logs, where each log is an object with a numeric `value` property.
	 * @returns {Object} An object with properties `logCount`, `total`, and `average`.
	 * If the `logs` array is empty, all properties will be set to zero.
	 */
	calculate(logs) {
		let figures = {
		 count: 0,
		 total: 0,
		 average: 0
		};

		if (logs.length === 0) {
		 return figures;
		}

		let totalValue = 0;
		for (let i = 0; i < logs.length; i++) {
		 totalValue += Number(logs[i].value);
		}

		figures.count = logs.length;
		figures.total = totalValue;
		if (logs.length > 0) {
		 figures.average = totalValue / logs.length;
		}

		return figures;
	}

	render( data ) {

		const logs = data.logs
		const figures = this.calculate( logs )

		// Use stat <template> markup to create the stats.

		const statComponent = jQuery('#sm-stat-component').get(0).content.cloneNode(true);
		const targetContainer = jQuery('.sm-stats-row');

		this.renderCountStat( figures, statComponent, targetContainer )
		this.renderTotalStat( figures, statComponent, targetContainer )
		this.renderAverageStat( figures, statComponent, targetContainer )

		// Init the chart report with the labels and data created from the return results.

		const chartData = this.chartDataParse(logs)
		console.log(chartData)

		const reportChart = new ReportChart(chartData.labels, chartData.data);
		reportChart.renderChart();

	}

	chartDataParse(logs) {
  const labels = [];
  const data = [];

  for (let i = 0; i < logs.length; i++) {
    labels.push(logs[i].created);
    data.push(parseFloat(logs[i].value)); // Use parseFloat() to convert values to numbers
  }

  return {
    labels: labels,
    data: data
  };
}


	renderCountStat( figures, statComponent, targetContainer ) {

		// Wrap the statComponent fragment in a new element
		const statElement = jQuery('<div>').append(jQuery('#sm-stat-component').get(0).content.cloneNode(true));

		// Append the new element to the target container
		statElement.hide().appendTo(targetContainer).fadeIn();

		// Modify the elements in the statComponent markup as needed
		statElement.find('.sm-stat-value').text(figures.count);
		statElement.find('.sm-stat-label').text('Log Count');

	}

	renderTotalStat( figures, statComponent, targetContainer ) {

		// Wrap the statComponent fragment in a new element
		const statElement = jQuery('<div>').append(jQuery('#sm-stat-component').get(0).content.cloneNode(true));

		// Append the new element to the target container
		statElement.hide().appendTo(targetContainer).fadeIn();

		// Modify the elements in the statComponent markup as needed
		statElement.find('.sm-stat-value').text(figures.total);
		statElement.find('.sm-stat-label').text('Total');

	}

	renderAverageStat( figures, statComponent, targetContainer ) {

		// Wrap the statComponent fragment in a new element
		const statElement = jQuery('<div>').append(jQuery('#sm-stat-component').get(0).content.cloneNode(true));

		// Append the new element to the target container
		statElement.hide().appendTo(targetContainer).fadeIn();

		// Modify the elements in the statComponent markup as needed
		statElement.find('.sm-stat-value').text(figures.average.toFixed(2));
		statElement.find('.sm-stat-label').text('Average');

	}

	statsClear() {
	  const statsRow = jQuery('.sm-stats-row');
	  if (statsRow.children().length > 0) {
	    statsRow.fadeOut('fast', function() {
	      statsRow.empty();
	      statsRow.show();
	    });
	  }
	}

	reportClear() {

		const reportChart = new ReportChart(['A', 'B', 'C'], [100, 200, 300]);
		reportChart.renderChart();

	}

}
