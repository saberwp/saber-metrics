class Reports {

	init() {

		// Refresh report with current filters.
		this.refresh()

		// Setup change event.
		this.filterInit();

	}

	/**
	 * Initializes the metric filter by adding a change event listener to the metric ID select field (#field-metric-id).
	 * The listener fetches the new data for the selected metric and re-renders the chart with the updated data.
	 *
	 * @return void
	 */
	filterInit() {
		const metricSelect = jQuery('#sm-report-filter-metric');
		const periodSelect = jQuery('#sm-report-filter-time-period');
		const groupingSelect = jQuery('#sm-report-filter-grouping');

		const handleChange = () => {
		this.refresh()
		}

		metricSelect.on('change', handleChange);
		periodSelect.on('change', handleChange);
		groupingSelect.on('change', handleChange);
	}

	// Refresh the report with current filters applied.
	refresh() {

		// Clear stats.
		this.statsClear()

		// Clear reports.
		this.reportClear()

		const metricId   = jQuery('#sm-report-filter-metric').val()
		const timePeriod = jQuery('#sm-report-filter-time-period').val()
		const grouping   = jQuery('#sm-report-filter-grouping').val()
		this.fetch(metricId, timePeriod, grouping);
	}

	fetch( metricId, timePeriod, grouping ) {

		jQuery.ajax({
		  type: 'POST',
		  url: ajaxurl,
		  data: {
		    action: 'metric_report_data_fetch',
		    data: {
					metric_id: metricId,
					time_period: timePeriod,
					grouping: grouping
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

		// Clear stats.
		this.statsClear()

		// Clear reports.
		this.reportClear()

		// Get logs.
		const logs = data.logs
		const groups = data.grouped
		const figures = this.calculate( logs )

		// Use stat <template> markup to create the stats.
		const statComponent = jQuery('#sm-stat-component').get(0).content.cloneNode(true);
		const targetContainer = jQuery('.sm-stats-row');
		this.renderCountStat( figures, statComponent, targetContainer )
		this.renderTotalStat( figures, statComponent, targetContainer )
		this.renderAverageStat( figures, statComponent, targetContainer )

		// Init the chart report with the labels and data created from the return results.
		if( groups !== null ) {
			console.log('has groups!')
			console.log( groups )
			const chartData = this.chartDataParseGroups(groups)
			const reportChart = new ReportChart(chartData.labels, chartData.data);
			reportChart.renderChart();
		} else {
			const chartData = this.chartDataParse(logs)
			const reportChart = new ReportChart(chartData.labels, chartData.data);
			reportChart.renderChart();
		}

	}

	// Parse chart data from groups.
	chartDataParseGroups(groups) {
	  const labels = [];
	  const data = [];

	  for (let i = 0; i < groups.length; i++) {
	    const group = groups[i];
	    labels.push(group.date);
	    data.push(parseFloat(group.total));
	  }

	  return {
	    labels: labels,
	    data: data
	  };
	}

	/* Parse chart data from logs. */
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
