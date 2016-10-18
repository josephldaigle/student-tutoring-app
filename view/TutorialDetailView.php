<?php
/**
 * Description of EnterTutorialView
 *
 * @author Joseph Daigle
 */
class TutorialDetailView extends View {
    private $student;   //holds student 
    
    public $user_inputs;
        
    public function __construct($student) {
        parent::__construct();
        
        $this->student = $student;
    }
      
    public function output() {
        //get the page header
        echo parent::get_header();
               
        //insert user feedback
        echo $this->insert_notifications();
        echo "<div style=\"display:inline; float:right;\">";
        echo '<span class="student-portrait"><h2>Student</h2>' . $this->student->get_student_portrait() . "</span>";
        echo "<span><table><caption><h2>Current Course</h2></caption>";
        
        echo "<tr><th>CRN</th>";
        echo "<th>COURSE NUMBER</th>";
        echo "<th>TITLE</th></tr>";
        echo "<tr><td>" . $_SESSION['selected-course']['CRN'] . "</td>";
        echo "<td>" . $_SESSION['selected-course']['COURSE'] . "</td>";
        echo "<td>" . $_SESSION['selected-course']['TITLE'] . "</td></tr>";
        
        echo "</table></span>";
        
        echo '</div>';
        
        //tutorial detail form
        echo '<form id="student-tutorial-form" method="post" action="./?action=save-tutorial" >
                    <fieldset>
                    <legend>Tutorial Details</legend>
                    
                    <span class="form-row" >
                        <label for="tutor">Who tutored this student?</label><br/>';
        
        if (isset($this->user_inputs['tutor'])) {
            switch ($this->user_inputs['tutor']) {
                case 1:     
                    echo '<input type="radio" name="tutor" value="1" checked="checked">I am the tutor.<br/>
                        <input type="radio" name="tutor" value="0">The tutor\'s GCID is:
                        <label for"tutor-gcid"></label>
                            <input name="tutor-gcid" type="text" pattern="^(929)([0-9]{6})" max-length="9"  
                               title="Please enter the student\'s GCID (929xxxxxx)." />';
                    break;
                case 0:
                    echo '<input type="radio" name="tutor" value="1" >I am the tutor.<br/>
                        <input type="radio" name="tutor" value="0" checked="checked">The tutor\'s GCID is:
                         <label for"tutor-gcid"></label>
                            <input name="tutor-gcid" type="text" pattern="^(929)([0-9]{6})" max-length="9"  
                               title="Please enter the student\'s GCID (929xxxxxx)." value="' . $this->user_inputs['tutor-gcid'] . '"/>';
                    break;
                default:
                    break;
            }
        } else {
            echo '<input type="radio" name="tutor" value="1" >I am the tutor.<br/>
                        <input type="radio" name="tutor" value="0">The tutor\'s GCID is:
            <input name="tutor-gcid" type="text" pattern="^(929)([0-9]{6})" max-length="9"  
                               title="Please enter the student\'s GCID (929xxxxxx)." />';
        }
        
            $html = <<<HTML
                    </span>
                    <hr/>
                    <span class="form-row" >
                        <label for="tutorial-date">Date</label>
                        <input class="date-input" name="tutorial-date-month" type="text" placeholder="mm" maxlength="2" 
                            pattern="\d{1,2}" required="required" aria-required="required" value="{$this->user_inputs['tutorial-date-month']}"/>
                        <input class="date-input" name="tutorial-date-day" type="text" placeholder="dd" 
                            pattern="\d{1,2}" maxlength="2" required="required" aria-required="required" value="{$this->user_inputs['tutorial-date-day']}" >/
                        <input class="date-input" name="tutorial-date-year" type="text" placeholder="yyyy" 
                            pattern="\d{4}" maxlength="4" required="required" aria-required="required" value="{$this->user_inputs['tutorial-date-year']}" >
                    </span>
                    
                    <span class="form-row">
                        <label for="tutorial-start-time">Start Time</label>
                        <input class="time-input" name="tutorial-start-time-hours" type="text" placeholder="09" maxlength="2"
                            pattern="\d{1,2}" title="Please use a number between 1 and 12." required="required" aria-required="required" 
                                value="{$this->user_inputs['tutorial-start-time-hours']}" />
                        <input class="time-input" name="tutorial-start-time-minutes" type="text" placeholder="30" maxlength="2"
                            pattern="(^[0-5]{1}[0-9]{1}$)" required="required" aria-required="required" 
                                value="{$this->user_inputs['tutorial-start-time-minutes']}" />
HTML;
                    if (isset($this->user_inputs['start-period'])) {
                        switch($this->user_inputs['start-period']) {
//                            case 'AM':
//                                $html .= '<input type="radio" name="start-period" value="AM" checked="checked">AM
//                                          <input type="radio" name="start-period" value="PM">PM';
//                                break;
                            case 'PM': 
                                $html .= '<input type="radio" name="start-period" value="AM">AM
                                          <input type="radio" name="start-period" value="PM" checked="checked">PM';
                                break;
                            default: 
                                $html .= '<input type="radio" name="start-period" value="AM" checked="checked">AM
                                          <input type="radio" name="start-period" value="PM">PM';
                                break;
                        }
                    } else {
                        $html .= '<input type="radio" name="start-period" value="AM" checked="checked">AM
                                        <input type="radio" name="start-period" value="PM">PM';
                    }
                     
                    $html .= <<<HTML
                    </span>
                    
                    <span class="form-row">
                        <label for="tutorial-end-time">End Time</label>
                        <input class="time-input" name="tutorial-end-time-hours" type="text" placeholder="10" maxlength="2"
                            pattern="\d{1,2}" title="Please use a number between 1 and 12." required="required" aria-required="required"
                                value="{$this->user_inputs['tutorial-end-time-hours']}" />:
                        <input class="time-input" name="tutorial-end-time-minutes" type="text" placeholder="30" placeholder="30" maxlength="2"
                            pattern="(^[0-5]{1}[0-9]{1}$)" required="required" aria-required="required" 
                                value="{$this->user_inputs['tutorial-end-time-minutes']}" />
HTML;
                    if (isset($this->user_inputs['end-period'])) {
                        switch($this->user_inputs['end-period']) {
//                            case 'AM':
//                                $html .= '<input type="radio" name="end-period" value="AM" checked="checked">AM
//                                          <input type="radio" name="end-period" value="PM">PM';
//                                break;
                            case 'PM': 
                                $html .= '<input type="radio" name="end-period" value="AM">AM
                                          <input type="radio" name="end-period" value="PM" checked="checked">PM';
                                break;
                            default: 
                                $html .= '<input type="radio" name="end-period" value="AM" checked="checked">AM
                                        <input type="radio" name="end-period" value="PM">PM';
                                break;
                        }
                    } else {
                        $html .= '<input type="radio" name="end-period" value="AM" checked="checked">AM
                                        <input type="radio" name="end-period" value="PM">PM';
                    }
                        
                    $html .= <<<HTML
                        </span>
                        <hr/>
                        <span class="form-row">
                            <label for="tutorial-notes" class="align-top">Session notes:</label><br/>
                            <textarea name="tutorial-notes" placeholder="Record your session notes."required="required" 
                                aria-required="required" >{$this->user_inputs['tutorial-notes']}</textarea>
                        </span>

                        <span class="form-row">
                            <input type="submit" class="button" value="Submit" />                     
                            <input type="reset" class="button" value="Reset" />
                        </span>

                        </fieldset>

                        <span>

                        </span>
                    </form>
HTML;
                              
        //get the page footer
        $html .= parent::get_footer();
        
        //return the view
        return $html;
    }
    
    public function set_user_inputs($inputs) {
        $this->user_inputs = $inputs;
    }
    
    
    private function insert_notifications() {
        
        $notification = parent::get_notification();
        
        if ($notification) {
            $html = '<div class="user-message"><ul>';
            
            if (is_array($notification)) {
                foreach ($notification as $val) {
                    $html .= '<li>' . $val . '</li>';
                }    
            } else {
               $html .= $notification; 
            }
            
            $html .= '</ul></div>';
            
            return $html;
        }
        
        return '';
    }
    
}
 