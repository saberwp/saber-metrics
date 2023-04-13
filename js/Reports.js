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

		// Parse filter values.
		const metricId   = jQuery('#sm-report-filter-metric').val()
		const timePeriod = jQuery('#sm-report-filter-time-period').val()
		const grouping   = jQuery('#sm-report-filter-grouping').val()

		// Initiate report data fetch.
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

	/**
	 * Calculates total count, total value, and average value for a set of groups.
	 *
	 * @param {Object[]} groups - An array of group objects, each with count, total, average, and date properties.
	 * @returns {Object} - An object with count, total, and average properties.
	 */
	calculateGroups(groups) {
	  let figures = {
	    count: 0,
	    total: 0,
	    average: 0
	  };

	  if (groups.length === 0) {
	    return figures;
	  }

	  let totalCount = 0;
	  let totalValue = 0;
	  for (let i = 0; i < groups.length; i++) {
	    totalCount += parseInt(groups[i].count);
	    totalValue += parseFloat(groups[i].total);
	  }

	  figures.count = totalCount;
	  figures.total = totalValue;
	  figures.average = totalValue / groups.length;

	  return figures;
	}

	render( data ) {

		console.log('render data...')
		console.log( data )

		// Clear stats.
		this.statsClear()

		// Clear reports.
		this.reportClear()

		// Get logs.
		const logs = data.logs
		const groups = data.grouped

		// Init the chart report with the labels and data created from the return results.
		let chartData = null
		let figures = null
		if( data.data.grouping !== 'log' && groups !== null ) {
			console.log('rendering a group by ' + data.data.grouping)
			chartData = this.chartDataParseGroups(groups)
			figures = this.calculateGroups( groups )
		} else {
			console.log('rendering logs instead of groups')
			chartData = this.chartDataParse(logs)
			figures = this.calculate( logs )
		}

		// Use stat <template> markup to create the stats.
		const statComponent = jQuery('#sm-stat-component').get(0).content.cloneNode(true);
		const targetContainer = jQuery('.sm-stats-row');
		this.renderCountStat( figures, statComponent, targetContainer )
		this.renderTotalStat( figures, statComponent, targetContainer )
		this.renderAverageStat( figures, statComponent, targetContainer )

		// Remove .sm-stat-new class.
		jQuery('.sm-stat-new').removeClass('sm-stat-new')


		// Render chart.
		const reportChart = new ReportChart(chartData.labels, chartData.data);
		reportChart.renderChart();

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

	renderCountStat(figures, statComponent, targetContainer) {

	  // Create a new element and set its contents to the statComponent fragment
	  const statElement = jQuery(statComponent).contents().clone();

	  // Modify the elements in the statComponent markup as needed
	  const statValue = statElement.find('.sm-stat-value');
	  statValue.text(figures.count);

	  const statLabel = statElement.find('.sm-stat-label');
	  statLabel.text('Log Count');

	  // Append the new element to the target container
	  jQuery(targetContainer).append(statElement);

		// Use jQuery's fadeIn() method to make the new element visible
	  statElement.hide().fadeIn();

	}

	renderTotalStat(figures, statComponent, targetContainer) {

		// Create a new element and set its contents to the statComponent fragment
	  const statElement = jQuery(statComponent).contents().clone();

	  // Modify the elements in the statComponent markup as needed
	  const statValue = statElement.find('.sm-stat-value');
	  statValue.text(figures.total);

	  const statLabel = statElement.find('.sm-stat-label');
	  statLabel.text('Total');

	  // Append the new element to the target container
	  jQuery(targetContainer).append(statElement);

		// Use jQuery's fadeIn() method to make the new element visible
	  statElement.hide().fadeIn();

	}

	renderAverageStat( figures, statComponent, targetContainer ) {

		// Create a new element and set its contents to the statComponent fragment
	  const statElement = jQuery(statComponent).contents().clone();

	  // Modify the elements in the statComponent markup as needed
	  const statValue = statElement.find('.sm-stat-value');
	  statValue.text( Math.round( figures.average ) );

	  const statLabel = statElement.find('.sm-stat-label');
	  statLabel.text('Average');

	  // Append the new element to the target container
	  jQuery(targetContainer).append(statElement);

		// Use jQuery's fadeIn() method to make the new element visible
	  statElement.hide().fadeIn();

	}

	statsClear() {
	  const stats = jQuery('.sm-stats-row .sm-stat:not(.sm-stat-new)');
		console.log('stats for remove:')
		console.log(stats)
		stats.remove()
	}

	reportClear() {

		const reportChart = new ReportChart(['A', 'B', 'C'], [100, 200, 300]);
		reportChart.renderChart();

	}

}
