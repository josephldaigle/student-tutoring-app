<?php

/**
 * Description of FacultyReportView
 * Displays student's tutorial sessions for the selected course
 * 
 * @author saml
 */
class FacultyReportView extends View{
    private $sessionList;
    
    public function __construct($sessions) {
        parent::__construct();
        $this->sessionList = $sessions;
    }
    
    public function output(){
        
        $sessionStudentLastName = $this->sessionList['LAST_NAME'];
        $sessionStudentFirstName = $this->sessionList['FIRST_NAME'];
        $sessionStudentGcid = $this->sessionList['STUDENT_GCID'];
        $sessionNotes = $this->sessionList['TUTORIAL_NOTES'];
        $sessionTutorName = $this->sessionList['TUTOR_NAME'];
        $sessionStartTime = $this->sessionList['TUTORIAL_START_TIME'];
        $sessionEndTime = $this->sessionList['TUTORIAL_END_TIME'];
        
        $html = parent::get_header();
        
        $html .= <<<HTML
                <a href="https://apps.gordonstate.edu/student-tutoring-app/?action=faculty-courses&user=$_SESSION[FACULTYPIDM]">Return to Course List</a>
                
HTML;
        if(count($sessionStudentLastName) > 0){
                //display professor's courses
        $html .= <<<HTML
                <p>Below are all the study sessions of students from the selected class</p>
                <table name="enrolled-courses" >
                <caption><h2>Study Sessions</h2></caption>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Student GCID</th>
                        <th>Tutor</th>
                        <th>Sessions Times</th>
                        <th>Session Notes</th>
                    </tr>
                </thead>
HTML;
        
        
        for($i =0; $i < count($this->sessionList['LAST_NAME']); $i++){
//            $html .= <<<HTML
//                <tr>
//                    <td style="min-width: 7em;">$sessionStudentLastName[$i], $sessionStudentFirstName[$i]</td>
//                    <td>$sessionStudentGcid[$i]</td>
//                    <td style="min-width: 7em;">$sessionTutorName[$i]</td>
//                    <td style="min-width: 9em;">Start:$sessionStartTime[$i]<br />End: $sessionEndTime[$i]</td>
//                    <td style="word-break: all">$sessionNotes[$i]</td>
//                </tr>
//HTML;
            
             $html .= <<<HTML
                <tr>
                    <td>$sessionStudentLastName[$i], $sessionStudentFirstName[$i]</td>
                    <td>$sessionStudentGcid[$i]</td>
                    <td>$sessionTutorName[$i]</td>
                    <td>Start:$sessionStartTime[$i]<br />End: $sessionEndTime[$i]</td>
                    <td
HTML;
            if (strlen($sessionNotes[$i]) >= 42) {
                $html .= " style=\"word-break: break-all;\">$sessionNotes[$i]</td></tr>";
            } else {
            $html .= <<<HTML
                >$sessionNotes[$i]</td>
                </tr>
HTML;
            }
        }
        
        $html .= "</table>";
        }
        else{
            $html .= <<<HTML
                <p>There are no recorded tutoring sessions for this class.</p>
HTML;
        }
        
        //get the page footer
        $html .= parent::get_footer();
        
        //return the view
        return $html;
        
    }
}
