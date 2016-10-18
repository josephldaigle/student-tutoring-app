<?php
/**
 * Tutorial.
 * 
 * An instance of a student being tutored in an academic
 * subject.
 *
 * @author Joseph Daigle
 */
class Tutorial {
    private $tutor_gcid;            
    private $student_gcid;
    private $recorder_gcid;         //gcid of the person who entered the tutorial into the app
    private $session_notes;         
    private $start_time;
    private $end_time;
    private $crn;                   //course number
    private $course_subject;         
    private $term;                  //the current term
    private $course_title;
    private $course_number;
    
    public function __construct() {
        
        //set the term
        $tutorial_dao = new TutorialDaoImpl();
        $this->term = $tutorial_dao->get_current_term();
    }
      
    function get_tutor_gcid() {
        return $this->tutor_gcid;
    }

    function get_student_gcid() {
        return $this->student_gcid;
    }

    function get_session_notes() {
        return $this->session_notes;
    }

    function get_start_time() {
        return $this->start_time;
    }

    function get_end_time() {
        return $this->end_time;
    }

    function get_crn() {
        return $this->crn;
    }
    
    function get_duration() {
        return $this->start_time - $this->end_time;
    }

    function set_tutor_gcid($tutor_gcid) {
        $this->tutor_gcid = $tutor_gcid;
    }

    function set_student_gcid($student_gcid) {
        $this->student_gcid = $student_gcid;
    }

    function set_session_notes($session_notes) {
        $this->session_notes = $session_notes;
    }

    function set_start_time($start_time) {
        $this->start_time = $start_time;
    }

    function set_end_time($end_time) {
        $this->end_time = $end_time;
    }

    function set_crn($crn) {
        $this->crn = $crn;
    }
    function get_recorder_gcid() {
        return $this->recorder_gcid;
    }

    function get_course_subject() {
        return $this->course_subject;
    }

    function get_term() {
        return $this->term;
    }

    function set_recorder_gcid($recorder_gcid) {
        $this->recorder_gcid = $recorder_gcid;
    }

    function set_course_subject($course_subject) {
        $this->course_subject = $course_subject;
    }
    
    function get_course_title() {
        return $this->course_title;
    }

    function set_course_title($course_title) {
        $this->course_title = $course_title;
    }

    function get_course_number() {
        return $this->course_number;
    }

    function set_course_number($course_number) {
        $this->course_number = $course_number;
    }

    /**
     * Hidden to prevent changing the term. This is
     * set in the class constructor, and will always be the
     * current term.
     */
//    function set_term($term) {
//        $this->term = $term;
//    }
}
