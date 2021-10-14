<?php
/**
 * This file is used for templating the reservable item contact owner modal.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/public/templates/modals
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.
?>
<div id="ersrvrr-contact-owner-modal" class="ersrvr-modal">
	<div class="ersrvr-modal-content modal-content modal-lg m-auto p-3">
		<h3><?php esc_html_e( 'Edit Review', 'easy-reservations-reviews' ); ?></h3>
		<span class="ersrvr-close-modal quick-close close">×</span>
		<div class="modal-body ersrvr_edit_reviews_body_container"></div>
	</div>
</div>