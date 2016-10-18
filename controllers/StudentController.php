<?php
/**
 * StudentController.
 * 
 * Controller for the student lookup feature.
 *
 * @author Joseph Daigle
 */
class StudentController {
    private $student;
//    private $validator;
    private $view;
    private $student_dao;
    
    public function __construct() {
//        $this->validator = new StudentLookupValidator();
//        $this->validator = '';
    }
    
    public function do_request($http_request) {
        //route request
        switch($http_request->get_arg('action')) {
            case 'init':
                $this->view = new StudentLookupView();
                echo $this->view->output();
                break;
            
            case 'find-student':
                //search for student using user-entered gcid
                $gcid = $http_request->get_arg('student-gcid');
                
                //load student object from dao
                $this->student_dao = new StudentDaoImpl();
                $this->student = $this->student_dao->load_student_record($gcid);

                //if student doesn't exists, show search screen
                if (!$this->student) {
                    //could not find student - display form to user.
                    $this->view = new StudentLookupView();
                    $this->view->set_error_message("I'm sorry, but I can't find that student." .
                            " Please check that you are using the correct GCID (929xxxxxx).");
                    echo $this->view->output();
                }
                
                //show the course selection screen
                $this->view = new StudentCourseView($this->student);
                echo $this->view->output();
                
                //save student to session for use later in proc
                $_SESSION['student'] = $this->student;
                
                break;
            
            default:
                $this->view = new ResourcesNotAvailableView();
                echo $this->view->output();
                break;
        }
    }
       
}
