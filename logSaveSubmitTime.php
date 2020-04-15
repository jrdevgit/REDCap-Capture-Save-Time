<?php

namespace BRACE\logSaveSubmitTime;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;
use REDCap;

class logSaveSubmitTime extends AbstractExternalModule {

    
    function redcap_survey_page_top($project_id, $record = null, $instrument, $event_id, $group_id = null, $survey_hash, $response_id = null, $repeat_instance = 1) {
        $this->logTime($instrument);
    }
	 
	 
	function logTime($instrument) {
		//$instrument;
		$instrument_settings = $this->getSubSettings('instrument_settings');
		//print_r($instrument_settings);
		foreach($instrument_settings as $num => $instance) {
			if ($instance['logtime_instrument'] == $instrument) {
				$logTimeField = $instance['logtime_field'];
				//print_r($instrument_settings);
				//print_r('<br>');
				//print_r($logTimeField);
				?>
				<script>
					var logSaveSubmitTime = logSaveSubmitTime || {};
					logSaveSubmitTime.field = <?php echo json_encode($logTimeField) ?>;
					<?php echo file_get_contents($this->getModulePath() . "logTime.js") ?>;
				</script>
				<?php
			}
		}
	}
}

?>