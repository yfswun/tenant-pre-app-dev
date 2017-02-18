jQuery(document).ready( function docReady($) {

	$.ajax({
		// url: "http://test.asianinc.org/tenantapp/",
		url: "http://localhost/tenantapp/",
		success: function( data ) {
			alert( 'Your home page has ' + $(data).find('div').length + ' div elements.');
		}
	})

})