$( document ).ready(function() {
	$('.pmpro_required').parent('div').find('label').append('<span class="asterisk"> * </span>');
});


$( document ).ready(function() {
	$("#first_name").keyup(function(){
		$("#bfirstname").val(this.value);
	});

	$("#last_name").keyup(function(){
		$("#blastname").val(this.value);
	});
});