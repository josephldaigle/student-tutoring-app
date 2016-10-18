<?php
/**
 * Description of TutorialLookupController
 *
 * @author Joe Daigle
 */
class TutorialLookupController {
    private $view;
    private $dao;

    public function __construct(){
//        $this->dao = new FacultyReportDaoImpl();
    }
    
     public function do_request($http_request) {
        switch($http_request->get_arg('action')) {
            
            case 'find-student-tutorials': //show tutorial lookup view
                
                $this->view = new TutorialLookupView();
                echo $this->view->output();
                
                break;
            
            case 'find-tutorial-records': //show student's tutoring sessions
                $validator = new StudentLookupValidator();
                $studentGCID = $validator->validate_gcid($http_request->get_arg('student-gcid'));
                
                $this->view = new TutorialLookupView();

                if (is_array($studentGCID)) {
                    //validation failed
                    $this->view->set_error_message($studentGCID['message']);
                    die("Error");
                } else {
//                                    die("here");

                    $this->dao = new StudentDaoImpl();
                    $studentTutorials = $this->dao->fetchStudentTutorials($studentGCID);
//                    die(var_dump($studentTutorials));
                    $this->view->setStudentTutorials($studentTutorials);
//                    die("tutorials set in view");
                }
                echo $this->view->output();

                break;
            
            default:
                $this->view = new ResourcesNotAvailableView();
                echo $this->view->output();
                break;
            
        }
    }
}
