<?php
/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrvr_setting_fields' ) ) {
	/**
	 * add setting fields here.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	function ersrvr_setting_fields(){
		$fields = array(
			array(
				'title' => __( 'Reservations Reviews Setting', 'easy-reservations-reviews' ),
				'type'  => 'title',
				'desc'  => '',
				'id'    => 'ersrvr_reviews',
			),
			array(
				'name'        => __( 'Submit Review Button Text', 'easy-reservations-reviews' ),
				'type'        => 'text',
				'desc'        => __( 'This holds the submit reviews button text. Default: Submit Review', 'easy-reservations-reviews' ),
				'desc_tip'    => true,
				'id'          => 'ersrv_submit_review_button_text',
				'placeholder' => __( 'E.g.: Submit Review', 'easy-reservations-reviews' ),
			),
			array(
				'name'     => __( 'Review Criteriass', 'easy-reservations-reviews' ),
				'type'     => 'multiselect',
				'options'  => ersrvr_default_review_criterias(),
				'class'    => 'wc-enhanced-select',
				'desc'     => __( '<a href="#" class="ersrv_add_more_criterias">' . __( 'Add Criterias','easy-reservations-reviews') . '</a>&nbsp;&nbsp;<a href="#" class="ersrv_remove_criterias">' . __( 'Remove Criterias','easy-reservations-reviews') . '</a>' ),
				'default'  => '',
				'id'       => 'ersrv_submit_review_criterias',
			),
			array(
				'type' => 'sectionend',
				'id'   => 'ersrvr_reviews',
			),
		);
		
		return apply_filters( 'ersrv_review_section_plugin_settings', $fields );
	}
}
/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrvr_default_review_criterias' ) ) {
	/**
	 * display default review criterias
	 *
	 * @return array
	 * @since 1.0.0
	 */
	function ersrvr_default_review_criterias() {
		$criterias = array_merge(
			array(
				__( 'Accuracy', 'easy-reservations-reviews' )      => __( 'Accuracy', 'easy-reservations-reviews' ),
			),
			ersrvr_get_plugin_settings( 'ersrv_submit_review_criterias' )
		);
		// $criterias = array();
		// debug( $criterias );

		/**
		 * 
		 */
		$criterias = apply_filters( 'ersrvr_add_custom_review_criterias', $criterias );

		return $criterias;
	}
}

/**
 * Get plugin setting by setting index.
 *
 * @param string $setting Holds the setting index.
 * @return boolean|string|array|int
 * @since 1.0.0
 */
function ersrvr_get_plugin_settings( $setting ) {
	switch ( $setting ) {
		case 'ersrv_submit_review_criterias':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : array();
			break;

		default:
			$data = -1;
	}

	return $data;
}
