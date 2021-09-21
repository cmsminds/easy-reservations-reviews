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
	function ersrvr_setting_fields() {
		$fields = array(
			array(
				'title' => __( 'Reservations Reviews Setting', 'easy-reservations-reviews' ),
				'type'  => 'title',
				'desc'  => '',
				'id'    => 'ersrvr_reviews',
				'name'  => 'ersrvr_reviews',
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
				'name'     => __( 'Review Criteria', 'easy-reservations-reviews' ),
				'type'     => 'multiselect',
				'options'  => ersrvr_get_plugin_settings( 'ersrv_submit_review_criterias' ),
				'class'    => 'wc-enhanced-select',
				'desc'     => __( 'This holds the review criteria. If you want to add some more, click on the button besides the selectbox.', 'easy-reservations-reviews' ),
				'desc_tip' => true,
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
if ( ! function_exists( 'ersrvr_get_plugin_settings' ) ) {
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
				$data = ( ! empty( $data ) ) ? ersrvr_prepare_criterias_array( $data ) : array();
				break;
			case 'ersrv_submit_review_button_text':
				$data = get_option( $setting );
				$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : array();
				break;
			default:
				$data = -1;
		}

		return $data;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrvr_prepare_criterias_array' ) ) {
	/**
	 * Get plugin setting by setting index.
	 *
	 * @param string $setting Holds the setting index.
	 * @return boolean|string|array|int
	 * @since 1.0.0
	 */
	function ersrvr_prepare_criterias_array( $data ) {
		// Return, if the data is blank.
		if ( empty( $data ) || ! is_array( $data ) ) {
			return $data;
		}

		$criterias = array();

		// Iterate through the data items to prepare as per the need.
		foreach ( $data as $criteria_name ) {
			$criterias[ $criteria_name ] = $criteria_name;
		}

		return $criterias;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrvr_get_plugin_settings' ) ) {
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
				$data = ( ! empty( $data ) ) ? ersrvr_prepare_criterias_array( $data ) : array();
				break;
			case 'ersrv_submit_review_button_text':
				$data = get_option( $setting );
				$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : array();
				break;
			default:
				$data = -1;
		}

		return $data;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrvr_prepare_reviews_html' ) ) {
	/**
	 * Make Review Form HTML.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	function ersrvr_prepare_reviews_html() {
		$html = '';
		ob_start(); 
		$file_path = plugin_dir_path( __DIR__ ).'public/templates/woocommerce/easy-reservations-reviews-html.php';
		if( file_exists( $file_path ) ) {
			require_once $file_path;
		}
		$html .= wp_kses(
			ob_get_clean(),
			array(
				'div'    => array(
					'class' => array(),
					'id'	=> array(),
					'role'  => array(),
				),
				'span'   => array(
					'class' => array(),
				),
				'p'      => array(),
				'a'      => array(
					'href'          => array(),
					'class'         => array(),
					'download'      => array(),
					'data-toggle'   => array(),
					'role'          => array(),
					'aria-expanded' => array(),
					'aria-controls' => array(),
				),
				'h1'     => array(),
				'button' => array(
					'type'  => array(),
					'class' => array(),
				),
				'input'  => array(
					'type'        => array(),
					'name'        => array(),
					'id'          => array(),
					'accept'      => array(),
					'placeholder' => array(),
				),
				'form'   => array(
					'method'  => array(),
					'enctype' => array(),
					
				),
				'label'  => array(
					'class' => array(),
					'for'   => array(),
				),
				'textarea' => array(
					'name'        => array(),
					'id'          => array(),
					'class'       => array(),
					'placeholder' => array(),
				)

			),
		);
		return $html;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrvr_get_active_stylesheet' ) ) {
	/**
	 * Get the active stysheet URL.
	 *
	 * @param string $current_theme Holds the current theme slug.
	 * @return string
	 * @since 1.0.0
	 */
	function ersrvr_get_active_stylesheet( $current_theme ) {
		switch ( $current_theme ) {
			case 'twentysixteen':
				return array(
					'url'  => ERSRVR_PLUGIN_URL . 'public/css/core/easy-reservations-reviews-twentysixteen.css',
					'path' => ERSRVR_PLUGIN_PATH . 'public/css/core/easy-reservations-reviews-twentysixteen.css',
				);

			case 'twentyseventeen':
				return array(
					'url'  => ERSRVR_PLUGIN_URL . 'public/css/core/easy-reservations-reviews-twentyseventeen.css',
					'path' => ERSRVR_PLUGIN_PATH . 'public/css/core/easy-reservations-reviews-twentyseventeen.css',
				);

			case 'twentynineteen':
				return array(
					'url'  => ERSRVR_PLUGIN_URL . 'public/css/core/easy-reservations-reviews-twentynineteen.css',
					'path' => ERSRVR_PLUGIN_PATH . 'public/css/core/easy-reservations-reviews-twentynineteen.css',
				);

			case 'twentytwenty':
				return array(
					'url'  => ERSRVR_PLUGIN_URL . 'public/css/core/easy-reservations-reviews-twentytwenty.css',
					'path' => ERSRVR_PLUGIN_PATH . 'public/css/core/easy-reservations-reviews-twentytwenty.css',
				);

			case 'twentytwentyone':
				return array(
					'url'  => ERSRVR_PLUGIN_URL . 'public/css/core/easy-reservations-reviews-twentytwentyone.css',
					'path' => ERSRVR_PLUGIN_PATH . 'public/css/core/easy-reservations-reviews-twentytwentyone.css',
				);

			case 'storefront':
				return array(
					'url'  => ERSRVR_PLUGIN_URL . 'public/css/core/easy-reservations-reviews-storefront.css',
					'path' => ERSRVR_PLUGIN_PATH . 'public/css/core/easy-reservations-reviews-storefront.css',
				);

			default:
				return array(
					'url'  => ERSRVR_PLUGIN_URL . 'public/css/core/easy-reservations-reviews-other.css',
					'path' => ERSRVR_PLUGIN_PATH . 'public/css/core/easy-reservations-reviews-other.css',
				);
		}
	}
}