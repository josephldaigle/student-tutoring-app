<?php
/**
 *  This interface provides database access methods for the 
 * Student Tutorial Application.
 * 
 * @author Joseph Daigle
 */

interface StudentDao {
    
    /**
     * Fetches the student's record from Banner DB.
     * 
     * @param type $gcid the student's Gordon College ID (929)
     */
    public function load_student_record($gcid);
    
    /**
     * Check if a student is registered for the current term.
     * Uses Banner DB Function - fg_current_term().
     * 
     * @param type $gcid the student's Gordon College ID (929)
     */
    public function is_active_student($gcid);
    
    /**
     * Fetches the course schedule from BannerDB
     * 
     * @param type $student the student to retrieve the schedule for
     */
    public function get_course_schedule($student);
    
    /**
     * Fetches the students class status (Freshman, sophmore, etc.)
     * @param type $pidm students Banner ID
     */
    public function get_student_class_level($pidm);
    
    /**
     * Fetches the student's declare major
     * @param type $pidm students Banner ID
     */
    public function get_student_major($pidm);
}
