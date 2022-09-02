jQuery(function( $ ) {
	'use strict';

	function updated(){
		$(".random_dynamic_fields").find(".rands_inner_field").each(function(index){
			$(this).find('.rand_field_count').text(index+1)
		});
		if($(".random_dynamic_fields").children().length === 0){
			$(".random_dynamic_fields").html(`<p style="margin: 0; color: red">No entry added!</p>`)
		}
	}

	let shortcodeField = `<div class="rands_inner_field">
		<span class="rand_field_count">1</span>
		<input type="text" placeholder="Shortcode" name="shortcodes[]" class="shortcode">
		<span class="remove_rand_field">+</span>
	</div>`;

	$(document).on("click", ".add-new-shortcode", function(e){
		e.preventDefault();
		if($(".random_dynamic_fields").find(".rands_inner_field").length === 0){
			$(".random_dynamic_fields").html("");
		}
		$(".random_dynamic_fields").append(shortcodeField);
		updated();
	});

	$(document).on("click", ".remove_rand_field", function(){
		$(this).parent().remove();
		updated();
	})
});
