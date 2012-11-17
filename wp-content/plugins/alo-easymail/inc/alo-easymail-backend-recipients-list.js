jQuery(document).ready( function($) {
	
	// 'Stop' and 'Restart' not yet available with periodicalupdater
	if ( easymailJs.updaterLibrary == 'periodicalupdater' ){
		jQuery('.easymail-recipients-pause-loop').remove();
		jQuery('.easymail-recipients-restart-loop').remove();		
	}
	
	jQuery.fn.easymailStartRecipientsLoop = function( send ) {
		var sendnow = false;
		if ( send == true ) sendnow = "yes";
		
		// Use requested updater library
		switch ( easymailJs.updaterLibrary ) {
			case 'periodicalupdater':
		
				jQuery.PeriodicalUpdater( easymailJs.em_ajaxurl, {
					method: 'post',         
					data: { 
						action: 			easymailJs.action, 
						newsletter:			easymailJs.newsletter, 
						_ajax_nonce: 		easymailJs.nonce, 
						txt_success_added: 	easymailJs.txt_success_added, 
						txt_success_sent: 	easymailJs.txt_success_sent, 
						sendnow: 			sendnow 
					},               
					minTimeout: 100,       // starting value for the timeout in milliseconds
					maxTimeout: 1000,       // maximum length of time between requests
					multiplier: 2,          // the amount to expand the timeout by if the response hasn't changed (up to maxTimeout)
					type: 'json',           // response type - text, xml, json, etc.  See $.ajax config options
					maxCalls: 0,            // maximum number of calls. 0 = no limit.
					autoStop: 3             // automatically stop requests after this many returns of the same data. 0 = disabled.
				}, function( data, success, xhr, handle ) {
					jQuery(this).easymailReturnFromUpdate( data, sendnow, handle );
				});
				break;
	
			default: // Default: smartupdater
		
				jQuery('#ajaxloop-response').smartupdater( {
						url : easymailJs.em_ajaxurl,
						data: { 
							action: 			easymailJs.action, 
							newsletter:			easymailJs.newsletter, 
							_ajax_nonce: 		easymailJs.nonce, 
							txt_success_added: 	easymailJs.txt_success_added, 
							txt_success_sent: 	easymailJs.txt_success_sent, 
							sendnow: 			sendnow 
						},
						type: 'POST',
						dataType: 'json',
						minTimeout: 100
					},
					function ( data ) {
						jQuery(this).easymailReturnFromUpdate( data, sendnow, false );
				});
					
		} // end library switch
		
		jQuery('.easymail-recipients-start-loop').hide();
		jQuery('.easymail-recipients-start-loop-and-send').hide();
		jQuery('.easymail-recipients-pause-loop').show();
		jQuery('.easymail-recipients-restart-loop').hide();

	}
	
	// After each periodic update...
	jQuery.fn.easymailReturnFromUpdate = function( data, sendnow, handle ) {
		jQuery('#alo-easymail-bar-outer').show();
		jQuery('#alo-easymail-bar-inner').css( 'width', data.perc + "%" );
		jQuery('#ajaxloop-response').empty();
		if ( data.n_done >= data.n_tot ) {
			var txt_succ = ( sendnow == "yes" ) ? easymailJs.txt_success_sent : easymailJs.txt_success_added ;
			jQuery('#ajaxloop-response').html( "<p>"+ txt_succ + "!</p>" );
			jQuery( '#alo-easymail-bar-inner').addClass ( 'stopped' );
			jQuery('.easymail-recipients-start-loop').hide();
			jQuery('.easymail-recipients-start-loop-and-send').hide();
			jQuery('.easymail-recipients-pause-loop').hide();
			jQuery('.easymail-recipients-restart-loop').hide();					
			jQuery(this).easymailUpdateColumStatus( easymailJs.newsletter );
			switch ( easymailJs.updaterLibrary ) {
				case 'periodicalupdater':
					if ( handle ) handle.stop();
					break;
				default:
					jQuery('#ajaxloop-response').smartupdaterStop();
			}
		} else {
			jQuery('#ajaxloop-response').html( data.perc + "% <small>(" + data.n_done + "/" + data.n_tot + ")</small>" );
		}
	}
	
	
	jQuery.fn.easymailSendMailTest = function() {
		var email = jQuery('#easymail-testmail').val();
		jQuery('#easymail-testmail-yes,#easymail-testmail-no').hide();
		jQuery('#easymail-testmail-loading').show();
		jQuery.post( easymailJs.ajaxurl, {
			action:			'easymail_send_mailtest',
			newsletter:		easymailJs.newsletter, 
			_ajax_nonce: 	easymailJs.nonce, 
			email: 			email
		   }, 
		   function ( response ) {
		   		jQuery('#easymail-testmail-loading').hide();
			  	if ( response == 'yes' ) {
			  		jQuery('#easymail-testmail-yes').show();
			 	} else {
			  		jQuery('#easymail-testmail-no').show();
			  	}
		   }
		);
	};

	jQuery.fn.easymailUpdateColumStatus = function( postId ) {
		var data = {
			action: 'alo_easymail_update_column_status',
			post_id: postId
		};
		jQuery.post( easymailJs.ajaxurl, data, function(response) {
			jQuery( '#alo-easymail-column-status-'+postId, window.parent.document ).html( response );
		});
		return false;
	};
	
	// Click Send Test Mail button
	jQuery('.easymail-send-testmail').live( "click", function() {
		jQuery(this).easymailSendMailTest();
	});	
		
	// Click Start "Put in queue" loop button
	jQuery('.easymail-recipients-start-loop').live( "click", function() {
		jQuery(this).easymailStartRecipientsLoop( false );
	});	

	// Click Start "Send now" loop button
	jQuery('.easymail-recipients-start-loop-and-send').live( "click", function() {
		jQuery(this).easymailStartRecipientsLoop( true );
	});	
	
	// Close thickbox and update status column
	jQuery('.easymail-recipients-close-popup').live( "click", function() {
		var postId = jQuery( this ).attr( 'rel' );
		jQuery(this).easymailUpdateColumStatus( postId );
		setTimeout( function(){ self.parent.tb_remove(); }, 100);
	});
	
	jQuery('.easymail-recipients-restart-loop').live( "click", function() {
		switch ( easymailJs.updaterLibrary ) {
			case 'periodicalupdater':
				//jQuery.PeriodicalUpdater.restart(); // TODO
				break;
			default:
				jQuery('#ajaxloop-response').smartupdaterRestart();
		}
		jQuery('.easymail-recipients-pause-loop').show();
		jQuery('.easymail-recipients-restart-loop').hide();
		jQuery( '#alo-easymail-bar-inner').removeClass ( 'stopped' );
		return false;
	});

	jQuery('.easymail-recipients-pause-loop').live( "click", function() {
		switch ( easymailJs.updaterLibrary ) {
			case 'periodicalupdater':
				// jQuery.PeriodicalUpdater // TODO
				//console.log ( jQuery.PeriodicalUpdater );
				break;
			default:
				jQuery('#ajaxloop-response').smartupdaterStop();
		}
		jQuery('.easymail-recipients-restart-loop').show();
		jQuery('.easymail-recipients-pause-loop').hide();
		jQuery( '#alo-easymail-bar-inner').addClass ( 'stopped' );		
		return false;
	});	
});

