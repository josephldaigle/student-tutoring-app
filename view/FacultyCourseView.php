<?php

/**
 * Description of FacultyCourseView
 *
 * Displays the current course list of a faculty member
 * 
 * @author saml
 */
class FacultyCourseView extends View{
    private $courseList;
    
    public function __construct($courseList) {
        parent::__construct();
        
        $this->courseList = $courseList;
    }
    
    public function output(){
        $html = parent::get_header();
        
        if (!array_filter($this->courseList)) {
            //no courses where found
            $html .= "<p>No courses found.</p>";
            
        } else {
            $courseSubject = $this->courseList['SUBJ'];
            $courseTitle = $this->courseList['TITLE'];
            $courseNumber = $this->courseList['NUMB'];
            $courseCrn = $this->courseList['CRN'];
            $courseDays = $this->courseList['DAYS'];
            $courseTimes = $this->courseList['TIMES'];
        
        //display professor's courses
            $html .= <<<HTML
                    <p>Please select a course to view.</p>
                    <table name="enrolled-courses">
                    <caption><h2>Select a course</h2></caption>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Title</th>
                            <th>Time</th>
                            <th>Subject</th>
                            <th>Number</th>
                            <th>CRN</th>
                        </tr>
                    </thead>
HTML;
        
            //output courses
            for($i =0; $i < count($this->courseList['CRN']); $i++){
                $html .= <<<HTML
                    <tr>
                        <td>
                            <a href="?action=show-report&course-crn=$courseCrn[$i]">
                                <i class="fa fa-arrow-circle-right go"></i>
                            </a>
                        </td>
                        <td>$courseTitle[$i]</td>
                        <td>$courseDays[$i] $courseTimes[$i]</td>
                        <td>$courseSubject[$i]</td>
                        <td>$courseNumber[$i]</td>
                        <td>$courseCrn[$i]</td>
                    </tr>
HTML;
            }

            $html .= "</table>";
        }
        
        //get the page footer
        $html .= parent::get_footer();
        
        //return the view
        return $html;
    }
}
