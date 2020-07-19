# REDCap Capture Form Save Submit Time

## Description

This External Module captures the date/time when a form is saved or submitted to a text field. The format of the date/time will match with the validation of the text field.

## Configuration

* **Select which instrument to add the time capture functionality:**
	Each form can have more than one field set up if desired (eg. One field to capture the time when a form is saved, another to capture when a form is submitted).
	
* **Select which field to capture the time:**
	The field must have a date/time validation configured, the time logged will follow the validation. It is recommended to set the field to read-only.
	
* **Capture time for Data Entry Form Save:**
	Capture the time when any of the Save options are clicked in Data Entry.

* **Select time capture setting in survey:**
	Select whether to capture the time when any survey page is saved/submitted OR when the page containing the field is saved/submitted. In the following branching logics, choose the condition(s) for capturing the time (i.e. When the "Save & Return Later" button is clicked, the "Previous Page" button is clicked, or the "Next Page" or "Submit" button is clicked).
	
* **Select how often to capture the time:**
	Select whether to capture just the first time or everytime that the form is saved/submitted. This also works for surveys that have already been completed (and allow participants to return to a completed form).
	
## Tip

* The "Submit" button on the last page of the survey is equivalent to "Next Page" button on regular survey pages. 
  To capture the time for submitting the survey, put the field on the last page of the survey and select the "Capture time for just the page containing the field" and "Capture time for Survey Next Page/Submit" options.
	
	

