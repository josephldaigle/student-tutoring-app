<?php

/**
 * This view outputs the form used to find a student's tutoring records.
 *
 * @author Joseph Daigle
 */
class TutorialLookupView extends View {
    
    private $studentTutorials;
    private $error_message;
  
    public function __construct() {
        parent::__construct();   
    }
    
    public function set_error_message($message) {
        if (!is_null($message) && !empty($message)) {
            $this->error_message = $message;
        }
    }
    
    public function setStudentTutorials($tutorials) {
        if (!is_null($tutorials) && !empty($tutorials)) {
            $this->studentTutorials = $tutorials;
        }
    }
    
    protected function displayStudentTutorials() {
        if (!isset($this->studentTutorials)) {
            return;
        }
        
        if (empty($this->studentTutorials)) {
            return;
        }
        
        if (is_null($this->studentTutorials)) {
            return;
        }
        
        return $this->wrapStudentTutorialsHTML();

    }
    
    protected function wrapStudentTutorialsHTML() {
        $totalSessions = count($this->studentTutorials['STUDENT_GCID']);
        $html = "<div><p>Showing results for:</p></div>
                <h2>{$this->studentTutorials['STUDENT_NAME'][0]}</h2><br/>
                <p><strong>Total Sessions: </strong>{$totalSessions}</p>
            <table class=\"output_table\" summary=\"student tutoring records\"
            dir=\"ltr\">
                    <tbody>
                        <tr>
                            <th>Date Tutored</th>
                            <th>Tutor GCID</th>
                            <th>Name</th>
                            <th>Subject</th>
                            <th>Number</th>
                            <th>Title</th>
                            <th>CRN</th>
                            <th>Recorder GCID</th>
                            <th>Name</th>
                        </tr>";
        
        for($i = 0; $i < count($this->studentTutorials['STUDENT_GCID']); $i++) {
            $html .= "<tr>
                        <td>{$this->studentTutorials['TUTORIAL_DATE'][$i]}</td>
                        <td>{$this->studentTutorials['TUTOR_GCID'][$i]}</td>
                        <td>{$this->studentTutorials['TUTOR_NAME'][$i]}</td>
                        <td>{$this->studentTutorials['COURSE_SUBJECT'][$i]}</td>
                        <td>{$this->studentTutorials['COURSE_TITLE'][$i]}</td>
                        <td>{$this->studentTutorials['COURSE_CRN'][$i]}</td>
                        <td>{$this->studentTutorials['RECORDER_GCID'][$i]}</td>
                        <td>{$this->studentTutorials['RECORDER_NAME'][$i]}</td>
                        <td>{$this->studentTutorials['RECORDED_ON'][$i]}</td>
                    </tr>";
        }
        
        $html .= "</tbody></table>";
        
        return $html;
    }
    
    public function output() {
        //get the page header
        $html = parent::get_header();
        
        //check for message to display
        if (isset($this->error_message)) {
            //inject error messages
            $html .= <<<HTML
                    <div class="user-message">
                        $this->error_message
                    </div>
HTML;
        } else {
            //inject welcome message
            $html .= <<<HTML
                    <span>Enter the student's GCID to find records for that student.</span>
HTML;
        }
        
        //inject the content
        $html .= <<<HTML
                
                <form id="tutorial-lookup-form" method="post" action="./?action=find-tutorial-records" >

                <span class="form-row">
                    <fieldset>
                        <legend>Tutorial Lookup</legend>
                        
                        <label for="student-gcid-gcid">Who would you like to search for?</label>
                        <input name="student-gcid" type="text" pattern="^(929)([0-9]{6})" max-length="9"  
                               title="Please enter the student's GCID (929xxxxxx)." required="required" 
                               aria-required="required" placeholder="929xxxxxx"
                                   value="{$_POST['student-gcid']}" />
                        
                        <input type="submit" class="button" value="Submit" />                    
                        <input type="reset" class="button" value="Reset" />
                    </fieldset>
                </span>
            </form>
HTML;
                                   
        $html .= $this->displayStudentTutorials();
        
        //get the page footer
        $html .= parent::get_footer();
        
        //return the view
        return $html;
    }
}
