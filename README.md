# REDCap Capture Form Save Submit Time

## Description

This External Module captures the date/time when a Data Entry Form and/or Survey is saved/submitted into a text field. The format of the date/time will match with the datetime validation of the text field. 
<br><br>
The user can specify the conditions that triggers the module to capture the time.
<br><br>
Clicking on "Click here for advanced settings for surveys" unlocks additional conditions. The **Select time capture setting in survey:** setting allows user to specify whether to capture the time **when the survey is saved on any page** or **just the page that contains the field**. The user can then select the **Survey buttons (Previous page, Next page/Submit, Save & Return Later)** that will trigger the time capturing.
Finally, the user can specify **whether to capture the time when the survey is first saved/submitted**(i.e. if the field is empty) or **for every saves/submits**. 

## Notes

* It is recommended to add make the field READONLY and/or HIDDEN to prevent respondants interfering with the field.

* If you wish to capture the time when a survey is saved on a particular page, don't forget to put the field on the page that you would like to capture the time for.

* The "Delete data for THIS FORM only" button in Data Entry counts as a save, and will therefore capture the time into the text field after deleting the data if the "Capture time for Data Entry Form Save" option is enabled. To prevent this, please untick this option first before deleting the data.
