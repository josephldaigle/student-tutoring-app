<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FacultyReportController
 *
 * @author saml
 */
class FacultyReportController {
    private $view;
    private $dao;

    
    public function __construct(){
        $this->dao = new FacultyReportDaoImpl();
    }
    
     public function do_request($http_request) {
        switch($http_request->get_arg('action')) {
            
            case 'faculty-courses'://show faculty course list
                
                $_SESSION['FACULTYPIDM'] = $http_request->get_arg('user');
                //fetch teacher's schedule from banner
                $courseList = $this->dao->fetchFacultyCourseList($_SESSION['FACULTYPIDM']);
                
                $this->view = new FacultyCourseView($courseList);

                echo $this->view->output();
                
                break;
            
            case 'show-report'://show student's tutoring sessions for course
                
                $crn = $http_request->get_arg('course-crn');
                
                $tutoringSessionsList = $this->dao->fetchTutoringSessionsList($crn, $_SESSION['FACULTYPIDM']);

                $this->view = new FacultyReportView($tutoringSessionsList);
                
                echo $this->view->output();
                //need to create view and models
                break;
            
            default:
                $this->view = new ResourcesNotAvailableView();
                echo $this->view->output();
                break;
            
        }
    }
}
