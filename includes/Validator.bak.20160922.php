<?php
/**
 * Validator.
 * 
 * Input validator containing methods for validating
 * frequently used data members.
 *
 * @author Joseph Daigle
 */
abstract class Validator {
    /**
     * Validates that $gcid conforms to the 929 pattern implemented by GSC.
     * @param type $gcid
     * @return reference filter_var() PHP Doc
     */
    protected function validate_gcid($gcid) {
        $filter =  filter_var($gcid, FILTER_VALIDATE_INT, array('options' => array(
                                                                        'min_range' => 929000001,
                                                                        'max_range' => 929999999)));
        
        if (is_null($filter) || empty($filter)) {
            return array('valid' => false, 'message' => 'Please enter a value for the tutor\'s GCID');
        }
        
    }
    
    /**
     * Validates that $month, $day, and $year provided can form
     * a valid date. Includes checks for number of days in month,
     * including leap years.
     * @param int $month
     * @param int $day 
     * @param int $year must be between 2000 and 2025
     */
    protected function validate_date($month, $day, $year) {
        $result = array();
                
        //check month
        if ($month < 1 || $month > 12) {
            $result['valid'] = false;
            $result['message'] = 'Inavlid month entered. Please enter a valid month(1-12).';
            return $result;
        }
        
        //check year
        if ($year < 2000 || $year > 2025) {
            $result['valid'] = false;
            $result['message'] = 'Invalid year entered. The year must be between 2000 and 2025.';
            return $result;
        }
        
        //check day
        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        if ($day < 1 || $day > $days_in_month) {
           $result['valid'] = false;
           $result['message'] = 'Invalid date entered. The date entered should be between 1 and 31, and must agree with the month and year entered.';
           return $result;
        }
        
        $date = date('m/d/y', mktime(0, 0, 0, $month, $day, $year));
        
        return $date;
               
    }
   
    protected function validate_time($hours, $minutes, $period) {
        $result = array();
        $message = 'Invalid time entered: Please enter a time between 8:00AM and 9:00PM';
        
        switch (strcasecmp($period, 'AM')) {
            case 0:         //AM
                if ($hours < 8 || $hours > 11) {
                  $result['valid'] = false;
                  $result['message'] = $message;
                  return $result;
                }
                break;
            
            default:        //PM
                //convert to 24hr
                if ($hours < 12) {
                    $hours += 12;
                }
                
                //if hours not within acceptable range
                if ($hours < 12 || $hours > 19) {
                    $result['valid'] = false;
                    $result['message'] = $message;
                    return $result;
                }
                break;
        }
        
        if ($minutes < 0 || $minutes > 59) {
            $result['valid'] = false;
            $result['message'] = $message;
            return $result;
        }
        
        $time = date('H:i', mktime($hours, $minutes, 0, 0, 0, 0));

        return $time;
        
    }
    
    protected function validate_text($text) {
        $result = filter_var($text, FILTER_SANITIZE_STRING);
        
        if ($result) {
            return $result;
        }
    }
}
