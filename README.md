# REDCap Capture Form Save Submit Time

## Description

This External Module captures the date/time when a Data Entry Form and/or Survey is saved/submitted into a text field. The format of the date/time will match with the validation of the text field. The user can specify which save options (e.g. "Next Page", "Save & Return Later" etc) will trigger the module to capture the time in the config.

## Notes

* **Select which field to capture the time:**
	The field must be a text field and have a date/time validation configured, the time logged will follow the validation. It is recommended to set the field to read-only and hidden.
	
* **Capture time for Data Entry Form Save:**
	If ticked, the module will capture the time when any of the Save options are clicked in Data Entry.

* **Select time capture setting in survey:**
	Select whether to capture the time when any survey page is saved/submitted OR when the page containing the field is saved/submitted. Following that, select the buttons that will trigger the time to be captured when clicked (Save & Return Later, Next Page, or Previous Page).
	
* **Select how often to capture the time:**
	Select whether to capture just the first time or everytime that the form is saved/submitted. This also works for surveys that have already been completed (and allow participants to return to a completed form).
	
## Tips

* The **"Submit"** button on the last page of the survey is equivalent to **"Next Page"** button on regular survey pages. 
  To capture the time for submitting the survey, put the field on the last page of the survey and select the **"Capture time for just the page containing the field"** and **"Capture time for Survey Next Page/Submit"** options.
  
* To make capturing the time of submitting a survey more accurate, an additional checkbox asking for **"Capture time only when the survey is submitted with a 'Completed' status"** will appear. When this field is ticked, the module will only capture the time of submitting the form when it has been successfully submitted.
  This field will only appear when both the following conditions are met:
	* **Select time capture setting in survey** = Capture time for just the page containing the field. 
	* **Capture time for Survey Next Page/Submit** = True/Ticked.
 
	
* The "Delete data for THIS FORM only" button in Data Entry counts as a save, and will therefore capture the time into the specified text field if the "Capture time for Data Entry Form Save" option is enabled. To prevent this, please untick this option first before deleting the data.

