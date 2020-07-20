<?php

namespace MCRI\CaptureFormSaveSubmitTime;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;
use REDCap;

class CaptureFormSaveSubmitTime extends AbstractExternalModule {

	public function redcap_save_record($project_id, $record=null, $instrument, $event_id, $group_id=null, $survey_hash=null, $response_id=null, $repeat_instance=1) {
		
		$email_to = AbstractExternalModule::getProjectSetting('captime_email');
		$email_from = AbstractExternalModule::getSystemSetting('captime_system_email');
		$email_cc_list = AbstractExternalModule::getSystemSetting('captime_cc_email');
		$instrument_settings = $this->getSubSettings('instrument_settings');
		foreach($instrument_settings as $setting) {
			if ($setting['captime_instrument'] == $instrument) {
				$capTimeField = $setting['captime_field'];
				if (!is_null($survey_hash)) {
					if (($setting['captime_survey_setting'] === 'captime_survey_setting_all' && (($setting['captime_survey_save'] == true && $_POST['submit-action'] == 'submit-btn-savereturnlater') || ($setting['captime_survey_submit'] == true && $_POST['submit-action'] === 'submit-btn-saverecord') || ($setting['captime_survey_prev'] == true && $_POST['submit-action'] === 'submit-btn-saveprevpage'))) || ($setting['captime_survey_setting'] === 'captime_survey_setting_spec' && (($setting['captime_survey_pagesave'] == true && $_POST['submit-action'] === 'submit-btn-savereturnlater' && isset($_POST[$capTimeField])) || ($setting['captime_survey_pagesubmit'] == true && $_POST['submit-action'] === 'submit-btn-saverecord' && $_POST[$instrument.'_complete'] == 2 && isset($_POST[$capTimeField])) || ($setting['captime_survey_pageprev'] == true && $_POST['submit-action'] === 'submit-btn-saveprevpage' && isset($_POST[$capTimeField]))))) {
						$capTimeFreq = $setting['captime_freq'];
						$this->capTime($capTimeField, $capTimeFreq, $email_from, $email_to, $email_cc_list, $project_id, $record, $event_id);
					}
				} elseif (is_null($survey_hash)) {
					if ($setting['captime_form_save'] == true && isset($_POST['submit-action']) && $_POST['submit-action'] != 'submit-btn-deleteform') {
						$capTimeFreq = $setting['captime_freq'];
						$this->capTime($capTimeField, $capTimeFreq, $email_from, $email_to, $email_cc_list, $project_id, $record, $event_id);
					}
				}
			}
		}
	}
	
	protected function capTime($field, $freq, $email_from, $email_to, $email_cc_list, $project_id, $record, $event_id) {
		$timeFormat = REDCap::getDataDictionary($project_id, 'array', 'false')[$field]['text_validation_type_or_show_slider_number'];
		$timePrev = REDCap::getData(intval($project_id), 'array', $record, $field)[$record][$event_id][$field];
		if (($freq === 'captime_freq_first' && $timePrev == null) || $freq === 'captime_freq_all') {
			if ($timeFormat === 'date_dmy' || $timeFormat === 'date_mdy' || $timeFormat === 'date_ymd') {
				$timeNow = date('Y-m-d');
			} elseif ($timeFormat === 'datetime_dmy' || $timeFormat === 'datetime_mdy' || $timeFormat === 'datetime_ymd') {
				$timeNow = date('Y-m-d H:i');
			} elseif ($timeFormat === 'datetime_seconds_dmy' || $timeFormat === 'datetime_seconds_mdy' || $timeFormat === 'datetime_seconds_ymd') {
				$timeNow = date('Y-m-d H:i:s');
			} elseif ($timeFormat === 'time') {
				$timeNow = date('H:i');
			}
			if (isset($timeNow) == false){
				$title = "ERROR in Capture Form Save/Submit Time external module: Time could not be obtained";
				$detail = "record=$record, event=$event_id, field=$field\nTime could not be obtained, please ensure the field is a text field with time validation configured";
				REDCap::logEvent($title, $detail, '', $record, $event_id, $project_id);
				REDCap::email($email, $email_from, $title, $detail, implode(",", $email_cc_list));
			} else {
				$saveResult = REDCap::saveData(intval($project_id), 'array', array($record => array($event_id => array($field => $timeNow))));
				if (count($saveResult['errors'])>0) {
					$title = "ERROR in Capture Form Save/Submit Time external module: Failed to capture time";
					$detail = "record=$record, event=$event_id, field=$field, value=$timeNow <br>saveResult=".print_r($saveResult, true);
					REDCap::logEvent($title, $detail, '', $record, $event_id, $project_id);
					REDCap::email($email_to, $email_from, $title, $detail, implode(",", $email_cc_list));
				}
			}
		}
	}
}

?>