<?php
/**
 * Site sub-header. Includes a slider, page title, etc.
 *
 * @package vamtam/numerique
 */


$page_title = VamtamFramework::get( 'page_title', null );

if ( ! is_404() ) {

	// If a title is present using elementor, we don't include our sub-header.
	if ( VamtamElementorBridge::is_elementor_pro_active() ) {
		if ( VamtamElementorBridge::is_title_present_for_post() ) {
			//sub-header should not be included.
			return;
		}
	}

	if ( vamtam_has_woocommerce() ) {
		if ( is_woocommerce() && ! is_single() ) {
			if ( is_product_category() ) {
				$page_title = single_cat_title( '', false );
			} elseif ( is_product_tag() ) {
				$page_title = single_tag_title( '', false );
			} elseif ( is_search() ) {
				$page_title = sprintf( esc_html__( 'Search Results for: %s', 'numerique' ), '<span>' . get_search_query() . '</span>' );
			} else {
				$page_title = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';
			}
		} elseif ( is_cart() || is_checkout() ) {
			$cart_id     = wc_get_page_id( 'cart' );
			$checkout_id = wc_get_page_id( 'checkout' );

			$cart_title     = get_the_title( $cart_id );
			$checkout_title = get_the_title( $checkout_id );
			$complete_title = esc_html__( 'Order Complete', 'numerique' );

			if ( is_cart() ) {
				$checkout_title = '<a href="' . esc_url( get_permalink( $checkout_id ) ) . '" title="' . esc_attr( $checkout_title ) . '">' . $checkout_title . '</a>';
			} else {
				$cart_title = '<a href="' . esc_url( get_permalink( $cart_id ) ) . '" title="' . esc_attr( $cart_title ) . '">' . $cart_title . '</a>';
			}

			$cart_state     = is_cart() ? 'active' : 'inactive';
			$checkout_state = is_checkout() && ! is_order_received_page() ? 'active' : 'inactive';
			$complete_state = is_order_received_page() ? 'active' : 'inactive';

			$page_title = "
				<span class='checkout-breadcrumb'>
					<span class='title-part-{$cart_state}'>$cart_title</span>" .
					vamtam_get_icon_html( array(
						'name' => 'vamtam-theme-arrow-right-sample',
					) ) .
					"<span class='title-part-{$checkout_state}'>$checkout_title</span>" .
					vamtam_get_icon_html( array(
						'name' => 'vamtam-theme-arrow-right-sample',
					) ) .
					"<span class='title-part-{$complete_state}'>$complete_title</span>
				</span>
			";
		}
	}
}

$sub_header_class = array( 'layout-' . VamtamTemplates::get_layout() );

if ( ! VamtamTemplates::has_page_header() || is_404() ) return;

?>

<div id="sub-header" class="<?php echo esc_attr( implode( ' ', $sub_header_class ) ); ?> elementor-page-title">
	<div class="meta-header" >
		<?php do_action( 'vamtam_meta_header_bg' ); ?>

		<?php VamtamTemplates::page_header( false, $page_title ); ?>
	</div>
</div>
