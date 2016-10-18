<?php
/**
 * StudentLookupValidator.
 * 
 * Provides validation methods and functions for Student Lookup module.
 *
 * @author Joseph Daigle
 */
class StudentLookupValidator extends Validator {
     public function validate_gcid($gcid) {
        $filter =  filter_var($gcid, FILTER_VALIDATE_INT, array('options' => array(
                                                                        'min_range' => 929000001,
                                                                        'max_range' => 929999999)));
        
        if (is_null($filter) || empty($filter)) {
            return array('valid' => false, 'message' => 'Please enter a value for the tutor\'s GCID');
        } else {
            return $gcid;
        }
        
     }    
}