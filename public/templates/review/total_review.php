<?php 
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://cmsminds.com
 * @since      1.0.0
 *
 * @package    Easy_Reservations_Reviews
 * @subpackage Easy_Reservations_Reviews/public/templates
 */
global $post;
$user_info                     = ersrvr_user_logged_in_data();
$user_email                    = ! empty( $user_info ) ? $user_info['user_email'] : '';
$username                      = ! empty( $user_info ) ? $user_info['username'] : '';
$user_phone_number             = ! empty( $user_info ) ? $user_info['user_phone_number'] : '';
$get_guest_user_enable_Setting = ersrvr_get_plugin_settings( 'ersrvr_enable_reservation_reviews_guest_users' );
$getallComments                = get_comments( array(
	'post_id' => $post->ID,
), ); ?>
<div class="ersrvr_total_review_html">
    <?php 
    $get_avrage_ratings = ersrvr_get_total_average_ratings();
    if ( ! empty( $get_avrage_ratings ) && is_array( $get_avrage_ratings ) ) {
        foreach ( $get_avrage_ratings as $get_avrage_rating ) {
            $avrage_ratings[] = $get_avrage_rating->meta_value;
        }
    }
    $avrage_ratings      = ! empty( $avrage_ratings ) ? $avrage_ratings : array();
    $total_rating_sum    = ! empty( $avrage_ratings ) ? array_sum( $avrage_ratings ) : 0;
    $total_rating_star   = (int) ( 0 !== $total_rating_sum ) ? round( $total_rating_sum / count( $avrage_ratings ) ) : 0;
    $total_rating_amount = (int) ( 0 !== $total_rating_sum ) ? round( $total_rating_sum / count( $avrage_ratings ), 2 ) : 0;
    
    ?>
    <div class="list-Of-Review-title">
        <h2 class="font-popins font-size-24 font-weight-bold"><?php echo esc_html( $total_rating_amount ); ?> Reviews</h2>
    </div>
    <div class="total-of-star-rating">
        <div class="rating-item d-flex flex-wrap align-items-center">
            <div class="col-12 col-sm-12 rating-group px-0">
                <?php for ( $i = 1; $i <= 5; $i++ ) { ?>
                    <?php
                    $filled_star_class = ( $total_rating_star >= $i ) ? 'fill_star_click' : '';
                    $filled_icons      = ( is_float( $total_rating_amount ) ) ? 'fa-star-half' : 'fa-star';
                    ?>
                <input class="rating__input <?php echo esc_attr( $filled_star_class ); ?>" name="rating3" id="rating<?php echo esc_attr( $i ); ?>-<?php echo esc_attr( $i ); ?>" value="<?php echo esc_attr( $i ); ?>" type="radio">
                <label aria-label="<?php echo esc_attr( $i ); ?> star" class="rating__label" for="rating<?php echo esc_attr( $i ); ?>-<?php echo esc_attr( $i ); ?>"><span class="rating__icon rating__icon--star fa fa-star"></span></label>
                <?php } ?>
            </div>
        </div>
    </div>
</div>