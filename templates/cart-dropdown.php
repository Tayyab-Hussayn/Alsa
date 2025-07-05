<?php
	if ( ! current_theme_supports( 'vamtam-cart-dropdown' ) ) {
		return;
	}
?>

<div class="cart-dropdown hidden">
	<div class="cart-dropdown-inner">
		<a class="vamtam-cart-dropdown-link" href="<?php echo esc_url( vamtam_wc_get_cart_url() ) ?>">
			<span><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path d="M28.418 6.947H3.582a.716.716 0 0 0-.716.716v23.62c0 .396.321.716.716.716h24.836a.716.716 0 0 0 .716-.716V7.663a.716.716 0 0 0-.716-.716zm-.717 1.432v22.188H4.298V8.379h23.403z"/><path d="M16 0c3.358 0 6.113 2.701 6.232 6.05l.004.224v5.558a.716.716 0 0 1-1.426.097l-.007-.097V6.274c0-2.662-2.164-4.841-4.803-4.841-2.569 0-4.688 2.066-4.798 4.632l-.004.209v5.558a.716.716 0 0 1-1.426.097l-.007-.097V6.274C9.765 2.823 12.568 0 16.001 0z"/></svg></span>
			<span class="products cart-empty">...</span>
		</a>
		<div class="widget widget_shopping_cart hidden">
			<div class="widget_shopping_cart_content"></div>
		</div>
	</div>
</div>
