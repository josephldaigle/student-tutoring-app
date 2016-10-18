<?php
/**
 * MainController.
 * 
 * Responsible for routing, and instantiation of sub-controllers.
 *
 * @author Joseph Daigle
 */


class MainController {
    
    private static $instance;
    private static $HttpRequest;
    
    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Singleton The *Singleton* instance.
     */
    public static function getInstance() {

        if (null === static::$instance) {
            static::$instance = new static();
        }
        
        return static::$instance;
    }
    
    /**
     * Routes an HTTP Request to the appropriate controller.
     */
    public static function route() {
        //create HttpRequest object
        self::$HttpRequest = new HttpRequest();
        
        //check authorization
        if (self::check_access() === false) {
            $view = new AccessDeniedView();
            echo $view->output();
            exit();
        }
        
        $action = self::$HttpRequest->get_arg('action');
        //route the request to the appropriate sub-controller
        switch ($action) {
            case 'init':            //go to the student lookup form.
                $controller = new StudentController();
                $controller->do_request(self::$HttpRequest);
                break;
            
            case 'find-student':    //user entered GCID to lookup
                $controller = new StudentController();
                $controller->do_request(self::$HttpRequest);
                break;
            
            case 'select-course':   //user chose a course
                //show the tutorial form
                $controller = new TutorialController();
                $controller->do_request(self::$HttpRequest);
                break;
            
            case 'save-tutorial':   //user submitted tutorial form
                //attempt save tutorial and show result page
                $controller = new TutorialController();
                $controller->do_request(self::$HttpRequest);
                break;
            
            case 'access-denied':
                $view = new AccessDeniedView();
                echo $view->output();
                break;
            
            case 'confirmation':
                $view = new ConfirmationView();
                echo $view->output();
                break;
            
            case 'faculty-courses':
                $controller = new FacultyReportController();
                $controller->do_request(self::$HttpRequest);
                break;
            
            case 'show-report':
                $controller = new FacultyReportController();
                $controller->do_request(self::$HttpRequest);
                break;
            
            case 'find-student-tutorials':
                $controller = new TutorialLookupController();
                $controller->do_request(self::$HttpRequest);
                break;
            
            case 'find-tutorial-records':
                $controller = new TutorialLookupController();
                $controller->do_request(self::$HttpRequest);
                break;
            
            default:    //user has requested resource that doesn't exist.
                $view = new ResourcesNotAvailableView();
                echo $view->output();
                break;
        }
    }
    
    
    /**
     * Checks whether or not the current user accessed the application
     * appropriately.
     */
    private static function check_access() {
        $access = self::$HttpRequest->get_arg('user');
        if ($access) {
            $_SESSION['LOGGED_USER'] = $access;
            return true;
        }
        
        if (isset($_SESSION['LOGGED_USER']) ){
            return true;
        }
        
        return false;        
    }

    
    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
    }

    
    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

    
    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {
    }
}
