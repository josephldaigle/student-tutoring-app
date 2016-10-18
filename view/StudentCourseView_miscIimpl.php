<?php
/**
 * StudentCourseView.
 * 
 * This view displays a students current courses, if registered and found
 * in Banner.
 *
 * @author Joseph Daigle
 * @date   18 May 2016
 */

class StudentCourseView extends View {
    private $student;
    
    public function __construct($student) {
        parent::__construct();
        $this->student = $student;
    }
    
    public function output() {
        //get the page header
        $html = parent::get_header();
        
        //fetch students schedule
        $schedule = $this->student->get_course_schedule();
        
        $html .= $this->student->get_student_portrait();
        
        //display students schedule
        $html .= <<<HTML
                <table name="enrolled-courses">
                <caption><h2>Select a course</h2></caption>
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Subject</th>
                        <th>Number</th>
                        <th>Term</th>
                        <th>CRN</th>
                    </tr>
                </thead>
HTML;

        foreach($schedule as $course) {
            $html .= <<<HTML
                <tr>
                    <td>
                        <a href="?action=select-course&course=$course[CRN]">
                            <i class="fa fa-arrow-circle-right go"></i>
                        </a>
                    </td>
                    <td>$course[TITLE]</td>
                    <td>$course[COURSE]</td>
                    <td>$course[SUBJ]</td>
                    <td>$course[NUMB]</td>
                    <td>$course[CRN]</td>
                </tr>
HTML;
        }
        
        $html .= "</table>";
        
        //get the page footer
        $html .= parent::get_footer();
        
        //return the view
        return $html;
    }
}
