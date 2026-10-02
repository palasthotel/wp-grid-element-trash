(function( $ ) {
	'use strict';

	$(".grid-element-trash-wrapper").on("click", ".trash-check", function(e){
		var $item = $(e.target);
		$.ajax({
			url: ajaxurl,
			data: {
				action: "grid_element_trash_change_option",
				element: $item.data("element"),
				type: $item.attr("name"),
				value: ($item.is(":checked"))? 0: 1,
			}, 
			success: function(data){
				console.log(data);
			}
		});
	});

})( jQuery );
