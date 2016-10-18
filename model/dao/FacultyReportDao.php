<?php
/**
 *
 * @author saml
 */
interface FacultyReportDao {
    
    public function fetchFacultyCourseList($id);
    
    public function fetchTutoringSessionsList($crn, $pidm);
}