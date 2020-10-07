# REDCap Capture Save Time

## Description

This External Module captures the date/time when a Data Entry Form and/or Survey is saved/submitted. The captured time will be stored in a selected text field, while the format of the date/time will match with the datetime validation of the text field. If any error occurs, an email will be sent to a mailbox specified by the user, notifying that the module has failed to capture the time.
<br><br>
There are plenty of options to the user to customize their conditions for capturing the time. Options include:

* Capture time for Data Entry - Save OR Mark Survey as Complete

* Capture time for Survey - Completion

* Capture time for Survey - Save & Return Later, Next Page, and/or Previous Page for all pages

* Capture time for Survey - Save & Return Later, Next Page, and/or Previous Page only for the page containing the field.

* Capture time 

## Notes

* It is recommended to add make the field READONLY and/or HIDDEN to prevent respondents interfering with the field.

* If you wish to capture the time when a particular survey page has been saved, don't forget to put the field on the page that you would like to capture the time for.

* Don't forget to add the time validation for the text field you wish to capture the time.