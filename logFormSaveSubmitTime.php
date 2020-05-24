<?php

namespace BRACE\logFormSaveSubmitTime;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;
use REDCap;

class logFormSaveSubmitTime extends AbstractExternalModule {

	public function redcap_save_record($project_id, $record=null, $instrument, $event_id, $group_id=null, $survey_hash=null, $response_id=null, $repeat_instance=1) {
		$instrument_settings = $this->getSubSettings('instrument_settings');
		foreach($instrument_settings as $setting) {
			if ($setting['logtime_instrument'] == $instrument) {
				$logTimeCondition = $setting['logtime_cond'];
				if (!is_null($survey_hash)) {
					if ($setting['logtime_form'] === 'logtime_form_survey' || $setting['logtime_form'] === 'logtime_form_both') {
						if (($logTimeCondition === 'logtime_cond_save' && $_POST['submit-action'] === 'submit-btn-savereturnlater') || ($logTimeCondition === 'logtime_cond_submit' && $_POST['submit-action'] === 'submit-btn-saverecord' && $_POST[$instrument.'_complete']=='2') || $logTimeCondition === 'logtime_cond_any') {
							$logTimeField = $setting['logtime_field'];
							$logTimeFreq = $setting['logtime_freq'];
							$this->logTime($logTimeField, $logTimeFreq, $project_id, $record, $event_id);
						}
					}
				} elseif (is_null($survey_hash)) {
					if ($setting['logtime_form'] === 'logtime_form_dataentry' || $setting['logtime_form'] === 'logtime_form_both') {
						if (($logTimeCondition === 'logtime_cond_save' && ($_POST['submit-action'] !== 'submit-btn-savecompresp' && !is_null($_POST['submit-action']))) || ($logTimeCondition === 'logtime_cond_submit' && ($_POST['submit-action'] === 'submit-btn-savecompresp' && $_POST[$instrument.'_complete']=='2')) || $logTimeCondition === 'logtime_cond_any') {
							$logTimeField = $setting['logtime_field'];
							$logTimeFreq = $setting['logtime_freq'];
							$this->logTime($logTimeField, $logTimeFreq, $project_id, $record, $event_id);
						}
					}
				}
			}
		}
	}
	
	public function logTime($field, $freq, $project_id, $record, $event_id) {
		$timeFormat = REDCap::getDataDictionary($project_id, 'array', 'false')[$field]['text_validation_type_or_show_slider_number'];
		if (($freq === 'logtime_freq_first' && REDCap::getData(intval($project_id), 'array', $record, $field)[$record][$event_id][$field] == null) || $freq === 'logtime_freq_all') {
			if ($timeFormat === 'date_dmy' || $timeFormat === 'date_mdy' || $timeFormat === 'date_ymd') {
				$timeNow = date('Y-m-d');
			} elseif ($timeFormat === 'datetime_dmy' || $timeFormat === 'datetime_mdy' || $timeFormat === 'datetime_ymd') {
				$timeNow = date('Y-m-d H:i');
			} elseif ($timeFormat === 'datetime_seconds_dmy' || $timeFormat === 'datetime_seconds_mdy' || $timeFormat === 'datetime_seconds_ymd') {
				$timeNow = date('Y-m-d H:i:s');
			} elseif ($timeFormat === 'time') {
				$timeNow = date('H:i');
			}
			$saveResult = REDCap::saveData(intval($project_id), 'array', array($record => array($event_id => array($field => $timeNow))));
			if (count($saveResult['errors'])>0) {
				$title = "ERROR in LogSurveySubmitTime external module: could not save value to field";
				$detail = "record=$record, event=$event_id, field=$logTimeField, value=$timeNow <br>saveResult=".print_r($saveResult, true);
				REDCap::logEvent($title, $detail, '', $record, $event_id, $project_id);
				$this->sendAdminEmail($title, $detail);
				print_r($saveResult);
			}
		}
	}
}

?>