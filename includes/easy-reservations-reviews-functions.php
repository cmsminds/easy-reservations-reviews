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
                'name' => __( 'Enable', 'easy-reservations-reviews' ),
                'type' => 'checkbox',
                'desc' => __( 'This will decide whether the customers can add reviews. Default is no.', 'easy-reservations-reviews' ),
                'id'   => 'ersrvr_enable_reviews_settings',
            ),
            array(
                'name'        => __( 'Submit Review Button Text', 'easy-reservations-reviews' ),
                'type'        => 'text',
                'desc'        => __( 'This holds the submit reviews button text button text. Default: Submit Review', 'easy-reservations-reviews' ),
                'desc_tip'    => true,
                'id'          => 'ersrv_submit_review_button_text',
                'placeholder' => __( 'E.g.: Submit Review', 'easy-reservations-reviews' ),
            ),
            array(
                'type' => 'sectionend',
                'id'   => 'ersrvr_reviews_settings',
            ),
        );
        return $fields;
    }
}