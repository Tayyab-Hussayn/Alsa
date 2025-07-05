<?php
/**
 * Show options for ordering
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/orderby.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$elementor_active = VamtamElementorBridge::is_elementor_active();
?>
<form class="woocommerce-ordering" method="get">
	<?php if ( $elementor_active ) : ?>
		<div class="elementor-field-group">
		<div class="elementor-select-wrapper">
	<?php endif; ?>
		<select name="orderby" class="orderby <?php echo esc_attr( $elementor_active ? 'elementor-field-textual' : '' ) ?>" aria-label="<?php esc_attr_e( 'Shop order', 'numerique' ); ?>">
		<?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
			<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
		<?php endforeach; ?>
		</select>
		<input type="hidden" name="paged" value="1" />
	<?php if ( $elementor_active ) : ?>
		</div>
		</div>
	<?php endif; ?>
	<?php wc_query_string_form_fields( null, array( 'orderby', 'submit', 'paged', 'product-page' ) ); ?>
</form>
