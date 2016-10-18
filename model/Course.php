<?php

/**
 * This class models a course schedule for a student.
 * Used by Student.php.
 *
 * @author Joseph Daigle
 */
class Course {
    
    private $title;
    private $number;
    private $crn;
    private $subject;
    private $professor;
    
    public function get_title(){
        return $this->title;
    }
    
    public function get_number(){
        return $this->number;
    }
    
    public function get_crn(){
        return $this->crn;
    }
    
    public function get_subject(){
        return $this->subject;
    }
    
    public function get_professor(){
        return $this->professor;
    }
    
    public function set_title($title){
        $this->title = $title;
    }
    
    public function set_number($number){
        $this->number = $number;
    }
    
    public function set_subject($subject){
        $this->subject = $subject;
    }
    
    public function set_crn($crn){
        $this->crn = $crn;
    }
    
    public function set_professor($professor){
        $this->professor = $professor;
    }
    
}
