class SectionTracker {

	init() {

		console.log('section tracker init....')

		this.tableInit()

	}

	tableInit() {

		// Initialize List.js for the #metric-table.
		var options = {
			valueNames: ['metric-log-id'],
			page: 5,
			pagination: true
		};
		var list = new List('metric-log-table', options);

	}

}
