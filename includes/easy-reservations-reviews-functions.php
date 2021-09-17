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
				'id'    => 'ersrvr_reviews_settings',
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
				'desc'     => __( 'This holds the review criteria. if you want add custom criteria please click <a href="#" class="ersrv_add_more_criterias">here</a>' ),
				// 'desc_tip' => true,
				'default'  => '',
				'id'       => 'ersrv_submit_review_criterias',
			),
			array(
				'type' => 'sectionend',
				'id'   => 'ersrvr_reviews_settings',
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
	function ersrvr_default_review_criterias(){
		$criterias = array(
			'accuracy'      => __( 'Accuracy', 'easy-reservations-reviews' ),
			'communication' => __( 'Communication', 'easy-reservations-reviews' ),
		);
		$criterias = apply_filters( 'ersrvr_add_custom_review_criterias', $criterias );
		return $criterias;
	}
}