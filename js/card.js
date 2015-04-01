
	function center_image() {
		$( ".card-image" ).each(function() {
			var img = $(this);
			var imageWidth = img.width();
			var imageHeight = img.height();

			if(imageHeight > imageWidth)
			    $(this).attr('style',"height:100%;");
			else
			    $(this).attr('style',"width:100%;");

			 $(this).css({
		        left: ($(this).parent('.card-inner').width() - $(this).outerWidth())/2,
		        top: ($(this).parent('.card-inner').height() - $(this).outerHeight())/2
		    });
		});
	 }


	$(document).ready( function() {
		center_image();
	});

	$(window).resize( function() {
		center_image();
	});

	$(".card-image").load( function() {
		center_image();
	});


$(document).ready( function() {
$('.text-fill').textfill({
});
});