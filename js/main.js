var saberMetricsData = {

	metricLogInit: 0

}

// Form #metric-save-form submit.
jQuery('#metric-save-form').submit(function(event) {
  event.preventDefault(); // Block the default form submission
  var data = {}; // Initialize an empty object to store the form data
  data.name = jQuery('#field-name').val();
  data.title = jQuery('#field-title').val();
	data.id = jQuery('#field-id').val();
  console.log(data); // Output the data to the console for testing

	// Send the data to the server using AJAX
  jQuery.ajax({
    url: ajaxurl,
    method: 'POST',
    data: {
			data: data,
			action: 'metric_save'
		},
    success: function(response) {
      console.log('Data saved successfully!');
      console.log(response); // Output the response to the console for testing
			addMetricRow(response.data.data)
    },
    error: function(xhr, status, error) {
      console.log('Error saving data: ' + error);
    }
  });

});

// Metric delete.
// jQuery click handler for .row-delete
jQuery('#metric-table .row-delete').click(function() {
  var id = jQuery(this).data('id'); // Get the ID from the data-id attribute
  var confirmation = confirm('Are you sure you want to delete this item?'); // Show a confirmation dialog

  if (confirmation) {
    // Send an AJAX request to delete the item
    jQuery.ajax({
      url: ajaxurl,
      method: 'POST',
      data: {
        action: 'metric_delete',
        id: id
      },
      success: function(response) {
        console.log('Item deleted successfully!');
        console.log(response); // Output the response to the console for testing
				// Fade out the table row with the matching ID
		    var rowId = 'metric-row-' + response.id;
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

// jQuery click handler for .row-edit
jQuery('#metric-table .row-edit').click(function() {
  var id = jQuery(this).data('id'); // Get the ID from the data-id attribute
  var title = jQuery('#metric-row-' + id + ' .metric-title').text(); // Get the title from the metric row

  // Set the form fields with the edit data
  jQuery('#field-id').val(id);
  jQuery('#field-title').val(title);
  jQuery('#field-name').val(name);
});

function addMetricRow(data) {
  var newRow = '<tr id="metric-row-' + data.id + '">' +
                 '<td class="metric-id">' + data.id + '</td>' +
                 '<td class="metric-title">' + data.title + '</td>' +
                 '<td>' +
                   '<button class="row-edit" data-id="' + data.id + '">EDIT</button>' +
                   '<button class="row-delete" data-id="' + data.id + '">DELETE</button>' +
                 '</td>' +
               '</tr>';

	var $newRow = jQuery(newRow).hide(); // Create a jQuery object for the new row and hide it

	// Append the new row to the table and fade it in
	jQuery('#metric-table tbody').append($newRow);
	$newRow.fadeIn(500);
}
