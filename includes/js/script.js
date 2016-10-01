var $bulk_generate_coupons = jQuery.noConflict();

$bulk_generate_coupons(document).ready(function($) {
	
	$( '#bulk_coupon_start' ).datepicker({
		prevText: '',
		nextText: '',
		minDate: 0,
		dateFormat: $( 'input[name=date_format]' ).val()
	});
	$( '#bulk_coupon_end' ).datepicker({
		prevText: '',
		nextText: '',
		minDate: 0,
		dateFormat: $( 'input[name=date_format]' ).val()
	});

});