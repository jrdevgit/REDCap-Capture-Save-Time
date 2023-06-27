<?php

namespace MCRI\CaptureSaveTime;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;
use REDCap;

class CaptureSaveTime extends AbstractExternalModule {
	
	public function redcap_survey_complete( int $project_id, string $record = NULL, string $instrument, int $event_id, int $group_id = NULL, string $survey_hash, int $response_id = NULL, int $repeat_instance = 1 ) {
		$email_to = AbstractExternalModule::getProjectSetting('captime_email');
		$email_from = AbstractExternalModule::getSystemSetting('captime_system_email');
		$instrument_settings = $this->getSubSettings('instrument_settings');
		foreach($instrument_settings as $setting) {
			if ($setting['captime_instrument'] == $instrument && $setting['captime_survey_complete'] == true) {
				$logic = $setting['captime_logic'];
				if (empty($logic) || REDCap::evaluateLogic($logic, $project_id, $record) === True) { 
					$capTimeField = $setting['captime_field'];
					$capTimeFreq = $setting['captime_freq'];
					$this->capTime($capTimeField, $capTimeFreq, $email_from, $email_to, $project_id, $record, $event_id);
				} elseif (is_null(REDCap::evaluateLogic($logic, $project_id, $record))) {
					$title = 'ERROR in Capture Save Time external module: Branching Logic';
					$detail = 'Please double check the branching logic: "'.$logic.'" for the Capture Save Time external module.';
					REDCap::logEvent('ERROR in Capture Save Time external module: Branching Logic', 'Please double check the branching logic: '.$logic, '', $record, $event_id, $project_id);
					REDCap::email(str_replace(" ","",implode(",", $email_to)), $email_from, $title, $detail);
				}
			}
		}
	}


	public function redcap_save_record( int $project_id, string $record = NULL, string $instrument, int $event_id, int $group_id = NULL, string $survey_hash = NULL, int $response_id = NULL, int $repeat_instance = 1 ) {
		$email_to = AbstractExternalModule::getProjectSetting('captime_email');
		$email_from = AbstractExternalModule::getSystemSetting('captime_system_email');
		$instrument_settings = $this->getSubSettings('instrument_settings');
		foreach($instrument_settings as $setting) {
			if ($setting['captime_instrument'] == $instrument) {
				$capTimeField = $setting['captime_field'];
				if (!is_null($survey_hash)) {
					if (($setting['captime_survey_save'] === true && $_POST['submit-action'] === 'submit-btn-savereturnlater') ||
					($setting['captime_survey_submit'] === true && $_POST['submit-action'] === 'submit-btn-saverecord') ||
					($setting['captime_survey_prev'] === true && $_POST['submit-action'] === 'submit-btn-saveprevpage') ||
					($setting['captime_survey_save_page'] === true && $_POST['submit-action'] === 'submit-btn-savereturnlater' && isset($_POST[$capTimeField])) ||
					($setting['captime_survey_submit_page'] === true && $_POST['submit-action'] === 'submit-btn-saverecord' && isset($_POST[$capTimeField])) ||
					($setting['captime_survey_prev_page'] === true && $_POST['submit-action'] === 'submit-btn-saveprevpage' && isset($_POST[$capTimeField]))) {
						$logic = $setting['captime_logic'];
						if (is_null($logic) || REDCap::evaluateLogic($logic, $project_id, $record) === True) {
							$capTimeFreq = $setting['captime_freq'];
							$this->capTime($capTimeField, $capTimeFreq, $email_from, $email_to, $project_id, $record, $event_id);
						} elseif (is_null(REDCap::evaluateLogic($logic, $project_id, $record))) {
							$title = 'ERROR in Capture Save Time external module: Branching Logic';
							$detail = 'Please double check the branching logic: "'.$logic.'" for the Capture Save Time external module.';
							REDCap::logEvent('ERROR in Capture Save Time external module: Branching Logic', 'Please double check the branching logic: '.$logic, '', $record, $event_id, $project_id);
							REDCap::email(str_replace(" ","",implode(",", $email_to)), $email_from, $title, $detail);
						}
					}
				} elseif (is_null($survey_hash)) {
					if (($setting['captime_form_save'] === true && $_POST['submit-action'] !== 'submit-btn-savecompresp' && count($_POST) > 3) || 
					($setting['captime_form_survey_complete'] === true && $_POST['submit-action'] === 'submit-btn-savecompresp')) {
						$logic = $setting['captime_logic'];
						if (is_null($logic) || REDCap::evaluateLogic($logic, $project_id, $record) === True) {
							$capTimeFreq = $setting['captime_freq'];
							$this->capTime($capTimeField, $capTimeFreq, $email_from, $email_to, $project_id, $record, $event_id);
						} elseif (is_null(REDCap::evaluateLogic($logic, $project_id, $record))) {
							$title = 'ERROR in Capture Save Time external module: Branching Logic';
							$detail = 'Please double check the branching logic: "'.$logic.'" for the Capture Save Time external module.';
							REDCap::logEvent('ERROR in Capture Save Time external module: Branching Logic', 'Please double check the branching logic: '.$logic, '', $record, $event_id, $project_id);
							REDCap::email(str_replace(" ","",implode(",", $email_to)), $email_from, $title, $detail);
						}
					}
				}
			}
		}
	}
	
	protected function capTime($field, $freq, $email_from, $email_to, $project_id, $record, $event_id) {
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
				$title = "ERROR in Capture Save Time external module: Time could not be obtained";
				$detail = "record=$record, event=$event_id, field=$field\nTime could not be obtained, please ensure the field is a text field with time validation configured";
				REDCap::logEvent($title, $detail, '', $record, $event_id, $project_id);
				REDCap::email(str_replace(" ","",implode(",", $email_to)), $email_from, $title, $detail);
			} else {
				$saveResult = REDCap::saveData(intval($project_id), 'array', array($record => array($event_id => array($field => $timeNow))));
				if (count($saveResult['errors'])>0) {
					$title = "ERROR in Capture Save Time external module: Failed to capture time";
					$detail = "record=$record, event=$event_id, field=$field, value=$timeNow <br>saveResult=".print_r($saveResult, true);
					REDCap::logEvent($title, $detail, '', $record, $event_id, $project_id);
					REDCap::email(str_replace(" ","",implode(",", $email_to)), $email_from, $title, $detail);
				}
			}
		}
	}
}

?>
