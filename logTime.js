
logSaveSubmitTime.setEventListeners = function() {
	$('button[name="submit-btn-savereturnlater"]').hide();
	$('button[name="submit-btn-saverecord"]').hide();
	$('.surveysubmit').find('td').eq(1).append(`<button type="button" name="logSaveSubmitTime-saverecord" tabindex="0" class="jqbutton nowrap ui-button ui-corner-all ui-widget" style="color: rgb(128, 0, 0); width: 100%; max-width: 140px; font-size: 20px;">Submit</button>`)
	$('.surveysubmit').find('td').eq(2).append(`<button type="button" name="logSaveSubmitTime-savereturnlater" tabindex="0" class="jqbutton ui-button ui-corner-all ui-widget">Save & Return Later</button>`)
	$('button[name="logSaveSubmitTime-saverecord"]').eq(0).attr('onclick', 'logSaveSubmitTime.logTime("submit-btn-saverecord")');
	$('button[name="logSaveSubmitTime-savereturnlater"]').eq(0).attr('onclick', 'logSaveSubmitTime.logTime("submit-btn-savereturnlater")');
}

logSaveSubmitTime.logTime = function(savemethod) {
	
	let today = new Date();
	let smallDates = [today.getMonth()+1, today.getDate(), today.getHours(), today.getMinutes(), today.getSeconds()]
	$.each(smallDates, function(i, field) {
		if (smallDates[i] < 10) {
			smallDates[i] = '0'+String(field)
		}
	})
	let date = today.getFullYear()+'-'+smallDates[0]+'-'+smallDates[1];
	let time = smallDates[2] + ":" + smallDates[3] + ":" + smallDates[4];
	let dateTime = date+' '+time;		
	document.getElementsByName(logSaveSubmitTime.field)[0].value = dateTime;
	
	
	$('#submit-action').val(savemethod);
	
	
		if ($('#submit-action').val() != "submit-btn-cancel"){
		// Determine esign_action
		var esign_action = "";
		if ($('#__ESIGNATURE__').length && $('#__ESIGNATURE__').prop('checked') && $('#__ESIGNATURE__').prop('disabled') == false) {
			esign_action = "save";
			// If form is not locked already or checked to be locked, then stop (because is necessary)
			if ($('#__LOCKRECORD__').prop('checked') == false) {
				simpleDialog('WARNING:\n\nThe "Lock Record" option must be checked before the e-signature can be saved. Please check the "Lock Record" check box and try again.');
				return false;
			}
		}

		// Set the lock action
		var lock_action = ($('#__LOCKRECORD__').prop("disabled") && (esign_action == "save" || esign_action == "")) ? 2 : 1;

		// "change reason" popup for existing records (and lock record, if user has rights)
		if (require_change_reason && record_exists && (dataEntryFormValuesChanged || $('#submit-action').val() == 'submit-btn-delete')) {
			$('#change_reason_popup').dialog({ bgiframe: true, modal: true, width: 500, zIndex: 4999, buttons: {
				'Save': function() {
					$('#change_reason_popup_error').css('display','none'); //Default state
					if ($("#change_reason").val().length < 1) {
						$('#change_reason_popup_error').toggle('blind',{},'normal');
						return false;
					}
					// Before submitting the form, add change reason values from dialog as form elements for submission
					$('#form').append('<input type="hidden" name="change-reason" value="'+$("#change_reason").val().replace(/"/gi, '&quot;')+'">');
					// Save locked value
					if ($('#__LOCKRECORD__').prop('checked')) {
						$('#change_reason_popup').dialog('destroy');
						saveLocking(lock_action,esign_action);
					// Not locked, so just submit form
					} else {
						formSubmitDataEntry();
					}
				}
			} });
		}
		// Do locking and/or save e-signature, then submit form
		else if ($('#__LOCKRECORD__').prop('checked') && (!$('#__LOCKRECORD__').prop("disabled") || esign_action == "save")) {
			saveLocking(lock_action,esign_action);
		}
		// Just submit form if neither using change_reason nor locking
		else {
			formSubmitDataEntry();
		}
	}
	// Clicked Cancel (requires form submission)
	else {
		formSubmitDataEntry();
	}
	
}

window.addEventListener('load', function() {
	logSaveSubmitTime.setEventListeners()
	console.log('hi');
})