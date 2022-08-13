/**
 * Code for the admin.
 *
 * @package    Custom_User_Insertion
 */

(function ($) {
	"use strict";

	$( document ).on(
		"ready",
		function () {
			$( ".custom_user_skill" ).select2();
		}
	);

	$( document ).on(
		"keypress",
		".user_input",
		function (e) {
			var regex = new RegExp( "^[a-zA-Z0-9_ s\r\n]+$" );
			var key   = String.fromCharCode( ! e.charCode ? e.which : e.charCode );
			if ( ! regex.test( key )) {
				e.preventDefault();
				return false;
			}
		}
	);

	$(document).ready(function(){
		$(".nav-tabs li.active").click(); 
		
		$(".nav-tabs li").click(function(e){
		e.preventDefault();
			$(".nav-tabs li").removeClass('active');
			$(this).addClass('active');
		let tid=  $(this).find('a').attr('href');
			console.log("ID:"+tid);
			$('.tab-pane').removeClass('active in');
			$(tid).addClass('active in');
		});
	});
})( jQuery );
