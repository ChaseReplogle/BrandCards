/**
 * navigation.js
 *
 * Handles toggling the navigation menu for small screens.
 */
( function() {
	var container, button, menu;

	container = document.getElementById( 'site-navigation' );
	if ( ! container ) {
		return;
	}

	button = container.getElementsByTagName( 'button' )[0];
	if ( 'undefined' === typeof button ) {
		return;
	}

	menu = container.getElementsByTagName( 'ul' )[0];

	// Hide menu toggle button if menu is empty and return early.
	if ( 'undefined' === typeof menu ) {
		button.style.display = 'none';
		return;
	}

	menu.setAttribute( 'aria-expanded', 'false' );

	if ( -1 === menu.className.indexOf( 'nav-menu' ) ) {
		menu.className += ' nav-menu';
	}

	button.onclick = function() {
		if ( -1 !== container.className.indexOf( 'toggled' ) ) {
			container.className = container.className.replace( ' toggled', '' );
			button.setAttribute( 'aria-expanded', 'false' );
			menu.setAttribute( 'aria-expanded', 'false' );
		} else {
			container.className += ' toggled';
			button.setAttribute( 'aria-expanded', 'true' );
			menu.setAttribute( 'aria-expanded', 'true' );
		}
	};
} )();



$('.mobile-menu-icon').click(function(event) {
	event.preventDefault();
    $( '.wide-nav' ).toggleClass( "mobile-nav-panel" );
});


$('.dash-nav .menu-item a').not('#all').click(function(event) {
	event.preventDefault();
	var cover = $(this).attr("href");
    $( '.covers' ).not(cover).slideUp( 'fast' );
    $( '.dash-nav .menu-item' ).removeClass( "current-menu-item" );
    $( this ).parent('.menu-item').addClass( "current-menu-item" );
    $( cover ).slideDown( 'fast' );


});

$('#all').click(function(event) {
	event.preventDefault();

	$(".covers").each(function() {

        if( $(this).is(':visible') ) {

        } else {
            $(this).slideDown('fast');
        }
    });

    $( '.dash-nav .menu-item' ).removeClass( "current-menu-item" );
    $( this ).parent('.menu-item').addClass( "current-menu-item" );
});

