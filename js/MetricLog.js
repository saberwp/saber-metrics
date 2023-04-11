class MetricLog {

	init() {
	  // Other initialization code here

	  // Form #metric-log-save-form submit.
	  jQuery('#metric-log-save-form').submit(function(event) {
	    event.preventDefault(); // Block the default form submission
	    var data = {}; // Initialize an empty object to store the form data
	    data.value = jQuery('#field-value').val();
	    data.id = jQuery('#field-id').val();
	    console.log(data); // Output the data to the console for testing

	    // Send the data to the server using AJAX
	    jQuery.ajax({
	      url: ajaxurl,
	      method: 'POST',
	      data: {
	        data: data,
	        action: 'metric_log_save'
	      },
	      success: function(response) {
	        console.log('Data saved successfully!');
	        console.log(response); // Output the response to the console for testing
	        metricLog.addMetricLogRow(response.data)
	      },
	      error: function(xhr, status, error) {
	        console.log('Error saving data: ' + error);
	      }
	    });
	  });
	}

	addMetricLogRow(data) {
	  var newRow = '<tr id="metric-log-row-' + data.id + '">' +
	                 '<td class="metric-id">' + data.id + '</td>' +
	                 '<td class="metric-title">' + data.data.value + '</td>' +
	                 '<td>' +
	                   '<button class="row-edit" data-id="' + data.id + '">EDIT</button>' +
	                   '<button class="row-delete" data-id="' + data.id + '">DELETE</button>' +
	                 '</td>' +
	               '</tr>';

		var $newRow = jQuery(newRow).hide(); // Create a jQuery object for the new row and hide it

		// Append the new row to the table and fade it in
		jQuery('#metric-log-table tbody').append($newRow);
		$newRow.fadeIn(500);
	}

}

// Self init.
var metricLog = new MetricLog;
metricLog.init()
