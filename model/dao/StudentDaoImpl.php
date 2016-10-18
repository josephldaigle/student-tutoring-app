<?php
/**
 * Description of StudentDaoImpl
 *
 * @author Joseph Daigle
 */
class StudentDaoImpl implements StudentDao {
    private $bannerDB;      //connection object
   
    
    /**
     * Sets up the database connection object that will be used to access student
     * model.
     * 
     * @throws Exception
     */
    public function __construct() {

        try {
            //get the db connection file path
            $connection_file = $_SERVER['DOCUMENT_ROOT'] . '\includes\connection.inc.php';

            //load the file
            require_once($connection_file);
            
            //fetch connection object
            $this->bannerDB = dbConnect('read');
            if (!$this->bannerDB) {
                throw new Exception(oci_error());
            }

        } catch (Exception $ex) {
            die("Fatal error in StudentDAO: Cannot establish connection to " .
                    "the Banner Database. Please contact Information Technology ".
                    " at (678) 359-5008, if you feel you have reached this message in error.");
        }
    }
    
    /**
     * Looks in banner db for a student.
     * @return mixed    a fully loaded Student if the student is found, else the failure message.
     */
    public function load_student_record($gcid) {
        //check if student is active
        $active = $this->is_active_student($gcid);

        if (!$active) {
            //student not active
            return null;
        } else {
            //student is active
            $student = new Student();
            $student->set_gcid($gcid);
            $student->set_pidm($active[0]);
            $student->set_last_name($active[1]);
            $student->set_first_name($active[2]);
            
            $student->set_course_schedule($this->get_course_schedule($student));
            $student->set_class_level($this->get_student_class_level($student->get_pidm()));
            $student->set_major($this->get_student_major($student->get_pidm()));
            $student->set_student_portrait($this->get_student_portrait($student));
            
            return $student;
        }
        
}
       
    /**
     * Checks if student is registered for classes in the current term.
     * 
     * @param type $gcid
     * @return mixed - boolean if false, otherwise returns the results of the query
     * in an array object.
     */
    public function is_active_student($gcid) {
        
        try {
            //query for active student
            $qry = "select  pidm, last_name, first_name
                    from v_person v 
                    where v.id = :GCID
                    and student.registered_in_term(v.pidm, fg_current_term()) = 'Y'";

            //Setup prepared statement
            $stid = oci_parse($this->bannerDB, $qry);
            
            //bind data to query object
            oci_bind_by_name($stid, ':GCID', $gcid);

            //execute query
            $r = oci_execute($stid);
            
            //return false if query fails to commit
            if (!$r) {
                $r =  "Failed to retrieve records from Banner.";
                //TODO log statement that db query did not retrieve results
            } else {
                $r = oci_fetch_array($stid, OCI_EXACT_FETCH);
            }

            //release connection objects and return false
            oci_free_statement($stid);
            
            return $r;
            
        } catch (Exception $e) {
            //close connections and return false on error
            oci_free_statement($stid);

            return false;
        }
    }
       
    /**
     * Retrieve the students course schedule from the banner DB.
     * @todo Description: IMPLEMENT LOGIC FOR NO RESULTS.
     */
    public function set_course_schedule() {
        //fetch course schedule from db
        $schedule = $this->student_dao->fetch_course_schedule($this->student);
        
        //inject course schedule into student object
        $this->student->set_course_schedule($schedule);
        
        return;
    }
    
    /**
     * 
     * @param type $student
     * @return boolean|array
     */
    public function get_course_schedule($student) {
        try {
            //query for course schedule
            $qry = "select distinct subj, numb, course, term, title, crn 
                    from (
                        select * 
                        from v_current_enrolled 
                        where pidm = :PIDM 
                        and term= fg_current_term())";
                    
            //Setup prepared statement
            $stid = oci_parse($this->bannerDB, $qry);
            
            //bind data to query object
            oci_bind_by_name($stid, ':PIDM', $student->get_pidm());

            //execute query
            $r = oci_execute($stid);
            
            //return false if query fails to commit
            if (!$r) {
                return false;
            } else {
                $schedule = array();
                while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
                    array_push($schedule, $row);
                }
                return $schedule;
            }

            //release connection objects and return false
            oci_free_statement($stid);
            
            return false;
            
        } catch (Exception $e) {
            //close connections and return false on error
            if ($stid) { oci_free_statement($stid); }
            
            return false;
        }
    }
    
    /**
     * Fetches the student's graduation level (freshman, sophmore, etc)
     * from Banner DB.
     *
     * @param type $pidm
     * @return string $level
     */
    public function get_student_class_level($pidm) {
        try {
            $qry = "select f_class_calc_fnc(:PIDM, 'US', fg_current_term()) from dual";
                    
            //Setup prepared statement
            $stid = oci_parse($this->bannerDB, $qry);
            
            //bind data to query object
            oci_bind_by_name($stid, ':PIDM', $pidm);

            //execute query
            $r = oci_execute($stid);
            
            //return false if query fails to commit
            if (!$r) {
                return false;
            } 
                
            $row = oci_fetch_row($stid);

            switch ($row[0]) {
                case 'FR':
                    oci_free_statement($stid);
                    return 'Freshman';
                case 'SO':
                    oci_free_statement($stid);
                    return 'Sophmore';
                case 'JR':
                    oci_free_statement($stid);
                    return 'Junior';
                case 'SR':
                    oci_free_statement($stid);
                    return 'Senior';
                default:
                    return null;
            }
            
        } catch (Exception $e) {
            //close connections and return false on error
            oci_free_statement($stid);
            
            return false;
        }
    }
    
    
    /**
     * Fetches a student's declared major from the Banner DB.
     * @param string $pidm the students Banner GUID
     * @return boolean
     */
    public function get_student_major($pidm) {
        try {
            
            $qry = "select MAJR_DESC_1 
                    from V_STUDENT
                    where PIDM = :PIDM";

            //Setup prepared statement
            $stid = oci_parse($this->bannerDB, $qry);
            
            //bind data to query object
            oci_bind_by_name($stid, ':PIDM', $pidm);

            //execute query
            $r = oci_execute($stid);
            
            //return false if query fails to commit
            if (!$r) {
                $r =  "Failed to retrieve records from Banner.";
                //TODO log statement that db query did not retrieve results
            } else {
                $r = oci_fetch_row($stid);
                $r = $r[0];
            }

            //release connection objects and return false
            oci_free_statement($stid);
            
            return $r;
                        
        } catch (Exception $e) {
            //close connections and return false on error
            oci_free_statement($stid);
            
            return false;
        }

    }
     
    /**
     * Fetches the students portrait data from Banner.
     * @param type $student
     * @return string
     */
    public function get_student_portrait($student) {
        //inject the students photo
        $pidm = substr($student->get_pidm(), -7);
        
        $html = <<<HTML
        <img class="student-photo" src="/images/idimage.asp?id=$pidm" alt="student-photo" />
HTML;
        
        //form student snapshot
        $html .= "<ul class=\"student-snapshot\"><li><span class=\"student-snapshot-label\">Name:</span>" . $student->get_last_name() . ", " . $student->get_first_name() . "</li><li>" .
            "<span class=\"student-snapshot-label\">GCID:</span>" . $student->get_gcid() . "</li><li>" .
            "<span class=\"student-snapshot-label\">Major:</span>" . $student->get_major() . "</li><li>" .
            "<span class=\"student-snapshot-label\">Status:</span>" . $student->get_class_level() . "</li></ul>";
        
        return $html;
    }
    
    public function fetchStudentTutorials($studentGCID) {
        try {
            
            $qry = "SELECT tut.student_gcid,
                        student.first_name || ' ' || student.last_name student_name,
                        to_char(tut.tutorial_start_time, 'mm/dd/yyyy') tutorial_date,
                        tut.tutor_gcid,
                        tutor.first_name || ' ' || tutor.last_name tutor_name,
                        tut.course_subject,
                        tut.course_number,
                        tut.course_title,
                        tut.course_crn,
                        round( ((tut.tutorial_end_time - tut.tutorial_start_time) * 24 * 60))  \"duration (minutes)\",
                        tut.recorder_gcid,
                        recorder.first_name || ' ' || recorder.last_name recorder_name,
                        to_char(tut.recorded_date, 'mm/dd/yyyy') recorded_on
                    FROM baninst1.wsctutor tut
                        LEFT JOIN v_person tutor ON tut.tutor_gcid = tutor.id
                        LEFT JOIN v_person student ON tut.student_gcid = student.id
                        LEFT JOIN v_person recorder ON tut.recorder_gcid = recorder.id
                    WHERE tut.course_term = fg_current_term()
                        AND tut.student_gcid = :GCID
                    ORDER BY tutorial_date desc";

            //Setup prepared statement
            $stid = oci_parse($this->bannerDB, $qry);
            
            //bind data to query object
            oci_bind_by_name($stid, ':GCID', $studentGCID);

            //execute query
            $r = oci_execute($stid);
            
            //return false if query fails to commit
            if (!$r) {
                $r =  "Failed to retrieve records from Banner.";
                //TODO log statement that db query did not retrieve results
            } else {
                oci_fetch_all($stid, $r);
            }

            //release connection objects and return false
            oci_free_statement($stid);
            
            return $r;
                        
        } catch (Exception $e) {
            //close connections and return false on error
            oci_free_statement($stid);
            
            return false;
        }
    }

}
