<?php

/**
 * This view outputs the form used to find a student that has been tutored.
 *
 * @author Joseph Daigle
 */
class StudentLookupView extends View{
    
    private $error_message;
  
    public function __construct() {
        parent::__construct();   
    }
    
    public function set_error_message($message) {
        if (!is_null($message) && !empty($message)) {
            $this->error_message = $message;
        }
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
                    <span>Welcome to the Student Tutoring Application. Use the
                        form below to find a student, and record time spent
                        tutoring the student.</span>
HTML;
        }
        
        //inject the content
        $html .= <<<HTML
                
                <form id="student-lookup-form" method="post" action="./?action=find-student" >

                <span class="form-row">
                    <fieldset>
                        <legend>Student Lookup</legend>
                        
                        <label for="student-gcid-gcid">Who would you like to record a tutoring session for?</label>
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
        
        //get the page footer
        $html .= parent::get_footer();
        
        //return the view
        return $html;
    }
}
