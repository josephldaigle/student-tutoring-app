<?php
/**
 * TutorialValidator.
 *
 * @author Joseph Daigle
 */
class TutorialValidator extends Validator {
    
    /**
     * Uses the validation methods of TutorialValidator to 
     * validate $inputs
     * 
     * @param type $inputs
     * @return mixed
     */
    public function validate_inputs($inputs) {
        
        $results = array();
        
        //identify the tutor
        if (intval($inputs['tutor']) === 1) {
            $results['tutor-gcid'] = $_SESSION['LOGGED_USER'];
        } else {
            $results['tutor-gcid'] = $this->validate_gcid($inputs['tutor-gcid']);
        }
        
        $results['date'] = $this->validate_date($inputs['tutorial-date-month'], $inputs['tutorial-date-day'], $inputs['tutorial-date-year']);
        
        $results['start-time'] = $this->validate_time($inputs['tutorial-start-time-hours'], $inputs['tutorial-start-time-minutes'], $inputs['start-period']);
        $results['end-time'] = $this->validate_time($inputs['tutorial-end-time-hours'], $inputs['tutorial-end-time-minutes'], $inputs['end-period']);
        
        $results['notes'] = $this->validate_text($inputs['tutorial-notes']);

        if(!is_array($results['start-time']) && !is_array($results['end-time'])) {
            //both times are valid inputs - check if they are in sych (startime not after end time)
            if ($results['start-time'] > $results['end-time']) {
                
                $results['start-time'] = array(
                    'valid' => false,
                    'message' => 'Invalid time entered. Start time cannot be after end time.'
                );
            }
        }
        
        //remove valid values from validation results list, leaving only error messages for display in view.
        foreach($results as $key => $val) {
            if(!is_array($val)) {
               unset($results[$key]);
            }
        }
        
        return $results;
    }
}
