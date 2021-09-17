(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	jQuery(document).on('click','.ersrv_add_more_criterias', function(e) {
		e.preventDefault();
		var criteria_name = prompt("Please Add Criteria","");
		if( '' !== criteria_name ) {
			var criteria_slug    = criteria_name.toLowerCase();
			criteria_slug        = criteria_slug.replace( ' ', '-' );
			var previous_criteria = $('#ersrv_submit_review_criterias option').val();
			console.log(previous_criteria);
			$('#ersrv_submit_review_criterias').append('<option value="'+ criteria_slug +'">'+ criteria_name +'</option>');
			// $("#ersrv_submit_review_criterias").val([criteria_slug]).trigger("change");
			
			$('#ersrv_submit_review_criterias').select2('val', [criteria_slug]);

		}

	} );
	
})( jQuery );
