<?php
/**
 * Description of TutorialDaoImpl.
 * 
 * This class implements the TutorialDao Interface.
 *
 * @author Joseph Daigle
 */
class TutorialDaoImpl implements TutorialDao {
     private $bannerDB;      //connection object
     
     /**
     * Sets up the database connection object that will be 
      * used to access Tutorial model.
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
            $this->bannerDB = dbConnect('write');
            if (!$this->bannerDB) {
                throw new Exception(oci_error());
            }

        } catch (Exception $ex) {
            die("Fatal error in TutorialDao: Cannot establish connection to " .
                    "the Banner Database. Please contact Information Technology ".
                    " at (678) 359-5008, if you feel you have reached this message in error.");
        }
    }
    
    /**
     * Save the tutorial to the new database structure.
     * 
     * @param type $tutorial
     */
    public function save_tutorial($tutorial) {
         try {
            
            $qry = "INSERT INTO WSCTUTOR(TUTOR_GCID, 
                STUDENT_GCID, 
                RECORDER_GCID, 
                TUTORIAL_START_TIME, 
                TUTORIAL_END_TIME, 
                COURSE_CRN, 
                COURSE_SUBJECT, 
                COURSE_NUMBER, 
                COURSE_TITLE, 
                COURSE_TERM, 
                TUTORIAL_NOTES,
                RECORDED_DATE)
                        VALUES(:TUTOR_GCID_BV,
                        :STUDENT_GCID_BV,
                        :RECORDER_GCID_BV,
                        TO_DATE(:TUTORIAL_START_TIME_BV, 'YYYY/MM/DD HH:MI AM'),
                        TO_DATE(:TUTORIAL_END_TIME_BV, 'YYYY/MM/DD HH:MI AM'),
                        :COURSE_CRN_BV,
                        :COURSE_SUBJECT_BV,
                        :COURSE_NUMBER_BV,
                        :COURSE_TITLE_BV,
                        :COURSE_TERM_BV,
                        :TUTORIAL_NOTES_BV,
                        SYSDATE)";
            
            //Setup prepared statement
            $stid = oci_parse($this->bannerDB, $qry);
            
            //bind data to query object
            oci_bind_by_name($stid, ':TUTOR_GCID_BV', $tutorial->get_tutor_gcid());
            oci_bind_by_name($stid, ':STUDENT_GCID_BV', $tutorial->get_student_gcid());
            oci_bind_by_name($stid, ':RECORDER_GCID_BV', $tutorial->get_recorder_gcid());
            oci_bind_by_name($stid, ':TUTORIAL_START_TIME_BV', $tutorial->get_start_time());
            oci_bind_by_name($stid, ':TUTORIAL_END_TIME_BV', $tutorial->get_end_time());
            oci_bind_by_name($stid, ':COURSE_CRN_BV', $tutorial->get_crn());
            oci_bind_by_name($stid, ':COURSE_TERM_BV', $tutorial->get_term());
            oci_bind_by_name($stid, ':TUTORIAL_NOTES_BV', $tutorial->get_session_notes());
            oci_bind_by_name($stid, ':COURSE_TITLE_BV', $tutorial->get_course_title());
            oci_bind_by_name($stid, ':COURSE_NUMBER_BV', $tutorial->get_course_number());
            oci_bind_by_name($stid, ':COURSE_SUBJECT_BV', $tutorial->get_course_subject());
            
            //execute query
            $r = oci_execute($stid);
            
            //return false if query fails to commit
            if (!$r) {
//                die(var_dump(oci_error($stid)));
                return false;
            } else {
                return true;
            }

            //release connection objects and return false
            oci_free_statement($stid);
            
            return false;
            
        } catch (Exception $e) {
            //close connections and return false on error
            oci_free_statement($stid);
            
            return false;
        }
    }
    
    public function get_pidm($gcid) {
        try {
            
            $qry = "SELECT PIDM
                    FROM v_person
                    WHERE ID = :GCID";
            
            //Setup prepared statement
            $stid = oci_parse($this->bannerDB, $qry);
            
            //bind data to query object
            oci_bind_by_name($stid, ':GCID', $gcid);

            //execute query
            $r = oci_execute($stid);
            
            //return false if query fails to execute
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
            oci_free_statement($stid);
            
            return false;
        }
    }
    
    /**
     * fetch the current term from Banner
     * @return string
     */
    public function get_current_term() {
        try {
            //query for active student
            $qry = "select fg_current_term() from dual";

            //Setup prepared statement
            $stid = oci_parse($this->bannerDB, $qry);

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
            
            return $r[0];
            
        } catch (Exception $e) {
            //close connections and return false on error
            oci_free_statement($stid);

            return false;
        }
    }
}
