<?php
/**
 * TutorialController.
 *
 * This class handles validation and persistence for requests
 * to save a tutorial.
 *
 * @author Joseph Daigle
 */
class TutorialController {    
    
    public function do_request($http_request) {
        
        switch ($http_request->get_arg('action')) {
            case 'select-course':
                //set the course that has been selected and show the tutorial detail view.
                
                if (strcasecmp($http_request->get_arg('course'), 'misc') === 0) {
                    $_SESSION['selected-course']['CRN'] = '000';
                    $_SESSION['selected-course']['TITLE'] = 'Miscellaneous';
                    $_SESSION['selected-course']['SUBJ'] = 'MISC';
                    $_SESSION['selected-course']['NUMB'] = '000';
                    $_SESSION['selected-course']['COURSE'] = '000';
                } else {
                    $_SESSION['selected-course'] = $_SESSION['student']->get_course($http_request->get_arg('course'));
                }
                $view = new TutorialDetailView($_SESSION['student']);
                echo $view->output();
                break;
            
            case 'save-tutorial':
                //validate user inputs
                $validator = new TutorialValidator();
                $validation = $validator->validate_inputs($http_request->get_all_args());
                $errors = array();
                foreach($validation as $result) {
                    if(is_array($result)) {
                        //validation failed, collect error messages
                        $errors[] = $result['message'];
                    }
                }
                //if validation fails show form again
                if (isset($errors) && !empty($errors)) {
                    //instantiate view
                    $view = new TutorialDetailView($_SESSION['student']);
                    $view->set_user_inputs($http_request->get_all_args());
                    $view->set_notification($errors);
                    echo $view->output();
                    exit();
                }
                
                //validation passed, write tutorial to DB.
                $tutorial = $this->parse_tutorial($http_request->get_all_args());
                
                if ($this->save_tutorial($tutorial) === true) {
                    unset($_SESSION['student']);
                    unset($_SESSION['selected-course']);
                    
                    //redirect to confirmation -- redirect prevents page refreshing and resubmitting db insert
                    $host = HOST;
                    $app = 'student-tutoring-app';
                    $extra = 'confirmation';
                    
                    header("Location: http://$host/$app/?action=$extra");
                    exit();
                } else {
                    $view = new TutorialDetailView($_SESSION['student']);
                    $view->set_notification("Tutorial not saved due to database error.");
                    echo $view->output();
                }
                                
                break;
                
            default:
                $view = new ResourcesNotAvailableView();
                echo $view->output();
                break;
        }
        
    }
   
    /**
     * Parses an array into a Tutorial object.
     */
    //@todo - Complete this method
    public function parse_tutorial($args) {
        $tutorial = new Tutorial();
        
        ($args['tutor'] == 1) ? $tutorial->set_tutor_gcid($_SESSION['LOGGED_USER']) :
            $tutorial->set_tutor_gcid($args['tutor-gcid']);
        
         $startTime = $args['tutorial-date-year'] . 
                "/" . $args['tutorial-date-month'] . 
                "/" . $args['tutorial-date-day'] . 
                " " . $args['tutorial-start-time-hours'] . 
                ":" . $args['tutorial-start-time-minutes'] . 
                " " . $args['start-period'];
        $endTime = $args['tutorial-date-year'] . 
                "/" . $args['tutorial-date-month'] . 
                "/" . $args['tutorial-date-day'] . 
                " " . $args['tutorial-end-time-hours'] . 
                ":" . $args['tutorial-end-time-minutes'] . 
                " " . $args['end-period'];
        
        $tutorial->set_recorder_gcid($_SESSION['LOGGED_USER']);
        $tutorial->set_student_gcid($_SESSION['student']->get_gcid());
        $tutorial->set_start_time($startTime);
        $tutorial->set_end_time($endTime);
        $tutorial->set_crn($_SESSION['selected-course']['CRN']);
        $tutorial->set_course_subject($_SESSION['selected-course']['SUBJ']);
        $tutorial->set_course_title($_SESSION['selected-course']['TITLE']);
        $tutorial->set_course_number($_SESSION['selected-course']['NUMB']);
        $tutorial->set_session_notes($args['tutorial-notes']);
        return $tutorial;
    }
    
    /**
     * Save the tutorial to the database.
     */
    public function save_tutorial($tutorial) {
        try {
            $tutorial_dao = new TutorialDaoImpl();
            return $tutorial_dao->save_tutorial($tutorial) ? true : false;
        } catch (Exception $ex) {
            die("exception when opening banner db");
        }
        
    }

}