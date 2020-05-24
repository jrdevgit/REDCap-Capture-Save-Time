# REDCap-Log Form Save Submit Time

## Description

This External Module logs the date/time when a form is saved or submitted to a text field. The format of the date/time will match with the validation of the text field.

## Configuration

* **Select which instrument to add the logging functionality:**
	Each form can have more than one field set up if desired (eg. One field to log the time when a form is saved, another to log when a form is submitted).
	
* **Select which field to log the time:**
	The field must have a date/time validation configured, the time logged will follow the validation. It is recommended to set the field to read-only.
	
* **Select the form type to apply the logging:**
	Select whether to log the time in a survey, data entry form, or both. 
	
* **Select the trigger to log the time:**
	Select whether to log the time upon saving or submitting a form, or both. <br>
	For Data Entry forms, "Form Submit" just applies to the Save option "Save and Mark this form as complete", while "Form Save" applies to all other save options. <br>
	For Survey forms, "Form Submit" applies to the "Submit" button, while "Form Save" applies to the "Save & Return Later" button. <br>
	For Survey forms, "Form Submit" only works when the form is successfully completed.
	
* **Select how often to log the time:**
	Select whether to log just the first time or everytime that the form is saved/submitted. This works for surveys that allow participants to return to a completed form.
	

	
	

