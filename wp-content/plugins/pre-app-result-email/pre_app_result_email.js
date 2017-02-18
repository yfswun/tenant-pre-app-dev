jQuery( document ).on( 'click', '#email-link', function emailLinkClick() {

	var pageTitle = jQuery('.entry-title');
	var firstName = jQuery("#first_name").text();
	var lastName = jQuery("#last_name").text();
	var email = jQuery("#email").data("email");
	var submission_id = jQuery("#pre-app-sub").data("sub-id");
	var contentHTML = '';
	var resultHTML = '';

	jQuery( '.entry-content' ).each( function eachEntryContent(index) {
		contentHTML = jQuery(this).html();
		resultHTML += contentHTML;
	})

	jQuery.ajax({
		url: TPA_email.ajax_url,
		type: 'POST',
		data: {
			'action' : 'send_email_sub_result',
			'title_text' : pageTitle.text(),
			'first_name' : firstName,
			'last_name' : lastName,
			'email' : email,
			'sub_id' : submission_id,
			'resultHTML' : resultHTML,
			'TPA-nonce': TPA_email.TPA_email_nonce
		},
		success: function successFunc( response ) {
			if ( response == 'Success' ) {
				alert( "An email has been sent to your email address." );
			}
		}
	});

	return false;
})