class MetricLog {

	init() {

	  // Initialization events.
		this.editStart()
		this.deleteStart()

	  // Form #metric-log-save-form submit.
	  jQuery('#metric-log-save-form').submit(function(event) {
	    event.preventDefault(); // Block the default form submission
	    var data = {}; // Initialize an empty object to store the form data
	    data.value = jQuery('#metric-log-save-form #field-value').val();
			data.metric_id = jQuery('#metric-log-save-form #field-metric-id').val();
	    data.id = jQuery('#metric-log-save-form #field-id').val();
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

	deleteStart() {

		// jQuery click handler for .row-delete
		jQuery('#metric-log-table .row-delete').click(function() {
		  var id = jQuery(this).data('id'); // Get the ID from the data-id attribute
		  var confirmation = confirm('Are you sure you want to delete this item?'); // Show a confirmation dialog

		  if (confirmation) {
		    // Send an AJAX request to delete the item
		    jQuery.ajax({
		      url: ajaxurl,
		      method: 'POST',
		      data: {
		        action: 'metric_log_delete',
		        id: id
		      },
		      success: function(response) {
		        console.log('Item deleted successfully!');
		        console.log(response); // Output the response to the console for testing
						// Fade out the table row with the matching ID
				    var rowId = 'metric-log-row-' + response.id;
				    jQuery('#' + rowId).fadeOut(500, function() {
				      jQuery(this).remove();
				    });
		      },
		      error: function(xhr, status, error) {
		        console.log('Error deleting item: ' + error);
		      }
		    });
		  }
		});

	}

	editStart() {

		// jQuery click handler for .row-edit
		jQuery('#metric-log-table .row-edit').click(function() {
			console.log('yup edit start...')
		  var id    = jQuery(this).data('id'); // Get the ID from the data-id attribute
		  var value = jQuery('#metric-log-row-' + id + ' .metric-log-value').text(); // Get the title from the metric row

			console.log(id)
			console.log(value)

		  // Set the form fields with the edit data
		  jQuery('#metric-log-save-form #field-id').val(id);
		  jQuery('#metric-log-save-form #field-value').val(value);
		});

	}

	addMetricLogRow(data) {
	  var newRow = '<tr id="metric-log-row-' + data.id + '">' +
	                 '<td class="metric-log-id">' + data.id + '</td>' +
	                 '<td class="metric-log-value">' + data.row.value + '</td>' +
									 '<td class="metric-log-metric-id">' + data.row.metric_id + '</td>' +
									 '<td class="metric-log-created">' + data.row.created + '</td>' +
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
