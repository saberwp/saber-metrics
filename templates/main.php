<header>
	<h1>SABER METRICS</h1>
</header>
<main>
	<section class="sm-app-section">
		<nav>
			<ul>
				<li>DASHBOARD</li>
				<li>METRICS</li>
				<li>REPORTS</li>
				<li>SETTINGS</li>
			</ul>
		</nav>
	</section>
	<section>
		<form id="model-save">
			<input id="field-id" type="hidden" value="0">
			<div class="sm-field-group">
				<label for="field-title">Title</label>
				<input id="field-title" type="text">
			</div>
			<div class="sm-field-group">
				<label for="field-prefix">Prefix</label>
				<input id="field-prefix" type="text">
			</div>
			<div class="sm-field-group">
				<label for="field-append">Append</label>
				<input id="field-append" type="text">
			</div>
			<div class="sm-field-group">
				<label for="field-rounding">Rounding</label>
				<input id="field-rounding" type="number" value="0">
			</div>
			<div class="sm-field-group">
				<input type="submit" value="SAVE" />
			</div>
		</form>

		<!-- Render table. -->
		<table id="metric-table">
			<thead>
				<th>ID</th>
				<th>Title</th>
				<th></th>
			</thead>
			<tbody>
				<?php
					$results = \SaberMetrics\Plugin::fetch_metrics();
					foreach ($results as $result) {

				?>

				<tr id="metric-row-<?php echo $result->id; ?>">
					<td class="metric-id"><?php echo $result->id; ?></td>
					<td class="metric-title"><?php echo $result->title; ?></td>
					<td>
						<button class="row-edit" data-id="<?php echo $result->id; ?>">EDIT</button>
						<button class="row-delete" data-id="<?php echo $result->id; ?>">DELETE</button>
					</td>
				</tr>

				<?php

					}
				?>

			</tbody>
		</table>

	</section>
</main>

<style>

main {
	display: flex;
	gap: 1rem;
}

.sm-field-group {
	margin: 0 0 1rem 0;
}

.sm-field-group label {
	display: block;
	margin: 0 0 3px 0;
}



nav {
	background-color: #F2F2F2;
	padding: 1rem;
	min-width: 12rem;
}

nav li:first-child {
	border-top: solid 1px #CCC;
}

nav li {
	cursor: pointer;
	padding: 0.5rem 0.5rem 0.5rem 0.5rem;
	margin: 0 0 0 0;
	border-bottom: solid 1px #CCC;
}

nav li:hover {
	background-color: #BBB;
	color: #FFF;
}

input[type=submit] {
	cursor: pointer;
}

</style>

<script>

// Form model-save submit.
jQuery('#model-save').submit(function(event) {
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
jQuery('.row-delete').click(function() {
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
jQuery('.row-edit').click(function() {
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



</script>
