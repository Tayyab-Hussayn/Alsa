(function($, undefined) {
	"use strict";

	window.VAMTAM = window.VAMTAM || {};

	$(function() {
		$('[name^=woosw_button], [name=woosw_page_share], [name=woosw_page_icon], #woosw_page_items, [name=woosw_page_copy], [name="woosw_menus[]"], [name=woosw_menu_action], [name=woosw_perfect_scrollbar], [name=woosw_color], [name=woosw_link], [name=woosw_show_note], [name=woosw_empty_button], [name=woosw_continue_url], [name=woosw_enable_multiple], [name=woosw_maximum_wishlists], [name="woosw_cats[]"]').closest('tr').hide();
		$('[name="woosw_menus[]"]').closest('tr').prev().hide();
		$('[name="woosw_cats[]"]').closest('tr').prev().hide();
		$('[name="woosw_button_type"]').closest('tr').prev().hide();
		$('[name="woosw_perfect_scrollbar"]').closest('tr').prev().hide();
		$('[name="woosw_popup_position"]').closest('tr').prev().hide();
		$('[name="woosw_description"]').closest('tr').prev().hide();
		$('[name="woosw_enable_multiple"]').closest('tr').prev().hide();
	});
})( jQuery );