$( document ).ready(function() {
	var img = $("#image");
	var imageWidth = img.width();
	var imageHeight = img.height();

	if(imageHeight >imageWidth)
	    $("#image").attr('style',"height:100%;");
	else
	    $("#image").attr('style',"width:100%;");


	$( window ).load(function() {
	 $('#image').css({
	        left: ($(".card-inner").width() - $('#image').outerWidth())/2,
	        top: ($(".card-inner").height() - $('#image').outerHeight())/2
	    });
	 });

	$( window ).resize(function() {
	 $('#image').css({
	        left: ($(".card-inner").width() - $('#image').outerWidth())/2,
	        top: ($(".card-inner").height() - $('#image').outerHeight())/2
	    });
	 });

});