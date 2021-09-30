<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://cmsminds.com
 * @since      1.0.0
 *
 * @package    Easy_Reservations_Reviews
 * @subpackage Easy_Reservations_Reviews/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Easy_Reservations_Reviews
 * @subpackage Easy_Reservations_Reviews/public
 * @author     cmsMinds <info@cmsminds.com>
 */
class Easy_Reservations_Reviews_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the JavaScript & stylesheet for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		global $wp_registered_widgets, $post, $wp_query;
		$post_id = ! empty( $post->ID ) ? $post->ID : 0;
		// Active style file based on the active theme.
		$current_theme     = get_option( 'stylesheet' );
		$active_style      = ersrvr_get_active_stylesheet( $current_theme );
		$active_style_url  = ( ! empty( $active_style['url'] ) ) ? $active_style['url'] : '';
		$active_style_path = ( ! empty( $active_style['path'] ) ) ? $active_style['path'] : '';
		// Enque Style file.
		if ( ! empty( $active_style_url ) && ! empty( $active_style_path ) ) {
			wp_enqueue_style(
				$this->plugin_name,
				$active_style_url,
				array(),
				filemtime( $active_style_path ),
			);
		}
		// Enque Javascript file.
		wp_enqueue_script(
			$this->plugin_name,
			ERSRVR_PLUGIN_URL . 'public/js/easy-reservations-reviews-public.js',
			array(),
			filemtime( ERSRVR_PLUGIN_PATH . 'public/js/easy-reservations-reviews-public.js' ),
			true
		);

		// Enqueue the common public style.
		wp_enqueue_style(
			$this->plugin_name . '-common',
			ERSRVR_PLUGIN_URL . 'public/css/core/easy-reservations-reviews-common.css',
			array(),
			filemtime( ERSRVR_PLUGIN_PATH . 'public/css/core/easy-reservations-reviews-common.css' )
		);
		$user_email = '';
		if ( is_user_logged_in() ) {
			$user_data  = ersrvr_user_logged_in_data();
			$user_email = $user_data['user_email'];
		}
		// Localize variables.
		wp_localize_script(
			$this->plugin_name,
			'ERSRVR_Reviews_Public_Script_Vars',
			array(
				'ajaxurl'         => admin_url( 'admin-ajax.php' ),
				'user_logged_in'  => ( is_user_logged_in() ) ? 'yes' : 'no',
				'user_email'      => $user_email,
				'current_post_id' => $post_id,
			)
		);

	}

	/**
	 * Review Form Shortcode.
	 *
	 * @param array $args Holds the shortcode arguments.
	 * @return string
	 * @since 1.0.0
	 */
	public function ersrvr_review_form_shortcode_callback( $args = array() ) {
		// Return, if it's admin panel.
		if ( is_admin() ) {
			return;
		}

		ob_start();
		echo wp_kses(
			ersrvr_prepare_reviews_html(),
			array(
				'div'      => array(
					'class'         => array(),
					'id'            => array(),
					'role'          => array(),
					'data-criteria' => array(),
				),
				'span'     => array(
					'class' => array(),
				),
				'p'        => array(
					'class' => array(),
				),
				'a'        => array(
					'href'          => array(),
					'class'         => array(),
					'download'      => array(),
					'data-toggle'   => array(),
					'role'          => array(),
					'aria-expanded' => array(),
					'aria-controls' => array(),
				),
				'h1'       => array(),
				'button'   => array(
					'type'  => array(),
					'class' => array(),
				),
				'input'    => array(
					'type'        => array(),
					'name'        => array(),
					'id'          => array(),
					'accept'      => array(),
					'placeholder' => array(),
					'class'       => array(),
					'value'       => array(),
					'checked'     => array(),
				),
				'form'     => array(
					'method'  => array(),
					'enctype' => array(),
					'action'  => array(),
				),
				'label'    => array(
					'class' => array(),
					'for'   => array(),
				),
				'textarea' => array(
					'name'        => array(),
					'id'          => array(),
					'class'       => array(),
					'placeholder' => array(),
				),
				'h2'       => array(
					'class' => array(),
				),
				'h3'       => array(
					'class' => array(),
				),
				'h4'       => array(
					'class' => array(),
				),
				'h5'       => array(
					'class' => array(),
				),
				'img'      => array(
					'src'   => array(),
					'class' => array(),
					'alt'   => array(),
				),
				'ul'       => array(
					'class' => array(),
				),
				'li'       => array(
					'class' => array(),
				),
			),
		);
		return ob_get_clean();
	}
	/**
	 * Add review setting section on easy reservations setting page.
	 *
	 * @param string $reservation_id Holds the reservation product id.
	 * @since 1.0.0
	 */
	public function ersrvr_after_item_details_callback( $reservation_id ) {
		$product = wc_get_product( $reservation_id );
		// check product is reservation type or not.
		if ( $product->is_type( 'reservation' ) ) {
			echo wp_kses(
				ersrvr_prepare_reviews_html(),
				array(
					'div'      => array(
						'class'         => array(),
						'id'            => array(),
						'role'          => array(),
						'data-criteria' => array(),
					),
					'span'     => array(
						'class' => array(),
					),
					'p'        => array(
						'class' => array(),
					),
					'a'        => array(
						'href'          => array(),
						'class'         => array(),
						'download'      => array(),
						'data-toggle'   => array(),
						'role'          => array(),
						'aria-expanded' => array(),
						'aria-controls' => array(),
					),
					'h1'       => array(),
					'button'   => array(
						'type'  => array(),
						'class' => array(),
					),
					'input'    => array(
						'type'        => array(),
						'name'        => array(),
						'id'          => array(),
						'accept'      => array(),
						'placeholder' => array(),
						'class'       => array(),
						'value'       => array(),
						'checked'     => array(),
					),
					'form'     => array(
						'method'  => array(),
						'enctype' => array(),
						'action'  => array(),
					),
					'label'    => array(
						'class' => array(),
						'for'   => array(),
					),
					'textarea' => array(
						'name'        => array(),
						'id'          => array(),
						'class'       => array(),
						'placeholder' => array(),
					),
					'h2'       => array(
						'class' => array(),
					),
					'h3'       => array(
						'class' => array(),
					),
					'h4'       => array(
						'class' => array(),
					),
					'h5'       => array(
						'class' => array(),
					),
					'img'      => array(
						'src'   => array(),
						'class' => array(),
						'alt'   => array(),
					),
					'ul'      => array(
						'class' => array(),
					),
					'li'      => array(
						'class' => array(),
					),
				),
			);
		}
	}

	/**
	 * Submit Review Form Data
	 *
	 * @since 1.0.0
	 */
	public function ersrvr_submit_reviews() {
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );
		// Check if action mismatches.
		if ( empty( $action ) || 'ersrvr_submit_reviews' !== $action ) {
			wp_die();
		}
		$user_email   = filter_input( INPUT_POST, 'useremail', FILTER_SANITIZE_STRING );
		$user_email   = ( ! empty( $user_email ) ) ? $user_email : '';
		$post_id      = filter_input( INPUT_POST, 'current_post_id', FILTER_SANITIZE_NUMBER_INT );
		$user         = get_user_by( 'email', $user_email );
		$user_id      = ( !empty( $user->ID ) ) ? $user->ID : 0;
		$user_name    = ( ! empty( $user->data->display_name ) ) ? $user->data->display_name : '';
		$author_url   = ( !empty( get_author_posts_url( $user_id ) ) ) ? get_author_posts_url( $user_id ) : '';
		
		$posted_array = filter_input_array( INPUT_POST );
		$all_criteria =  ( ! empty( $posted_array['user_criteria_ratings'] ) ) ? $posted_array['user_criteria_ratings'] : array();
		
		foreach ( $all_criteria as $key => $criteria ) {
			$closest_criteria  = $criteria['closest_criteria'];
			$rating            = $criteria['rating'];
			$combine_ratings[] = $rating;
		}
		$total_ratings  = array_sum( $combine_ratings );
		$avrage_ratings = $total_ratings / count( $combine_ratings );
		$save_option    = array(
			'average_ratings' => $avrage_ratings
		);
		$comment_data =  array(
			'comment_post_ID'      => $post_id,
			'comment_author'       => $user_name,
			'comment_author_email' => $user_email,
			'comment_author_url'   => $author_url,
			'comment_content'      => 'abc',
			'user_id'              => $user_id,
			'comment_author_IP'    => '127.0.0.1',
			'comment_agent'        => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
			'comment_date'         => date('m/d/Y h:i:s', time()),
			'comment_approved'     => 1,
		);
		$comment_id = wp_insert_comment($comment_data);
		add_comment_meta( $comment_id, 'average_ratings', $avrage_ratings );
		add_comment_meta( $comment_id, 'user_criteria_ratings', $all_criteria );
		
		die("poop");
	}
	

}
