<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FacultyReportDao
 *
 * @author saml
 */
class FacultyReportDaoImpl implements FacultyReportDao {
    private $conn;
    
    public function __construct(){
        try {
            //get the db connection file path
            $connection_file = $_SERVER['DOCUMENT_ROOT'] . '\includes\connection.inc.php';

            //load the file
            require_once($connection_file);
            
            //fetch connection object
            $this->conn = dbConnect('read');
            
            if (!$this->conn) {
                throw new Exception(oci_error());
            }
            
            $qry = "ALTER SESSION SET CURRENT_SCHEMA =  baninst1";
         
            $changeSchemaQuery = oci_parse($this->conn, $qry);
            $ee = oci_execute($changeSchemaQuery);

            $qry = "ALTER SESSION SET NLS_DATE_FORMAT = 'hh:mi Mon-dd'";

            $changeDateTimeFormat = oci_parse($this->conn, $qry);
            $ee = oci_execute($changeDateTimeFormat);
                       
                                    
        } catch (Exception $ex) {
            die("Fatal error in StudentDAO: Cannot establish connection to " .
                    "the Banner Database. Please contact Information Technology ".
                    " at (678) 359-5008, if you feel you have reached this message in error.");
        }
    }
    
    public function fetchFacultyCourseList($pidm) {
        try{
            $qry = "  SELECT   subj,
                               title,
                               numb,
                               crn,
                               days,
                               times
                        FROM   v_course
                       WHERE   term_code = fg_current_term() AND inst_pidm = :PIDM
                    ORDER BY   title, crn ASC";

            //prepared statement
            $stid = oci_parse($this->conn, $qry);
            
            //bind data
            oci_bind_by_name($stid, ':PIDM', $pidm);

            //exec query
            oci_execute($stid);
            
            oci_error($stid);
                        
            $nrows = oci_fetch_all($stid, $results);
            
            oci_free_statement($stid);
            
            return $results;
        
        } catch(Exception $e){
            
        }//end catch      
    }//end fetchFacultyCourseList()
        
    public function fetchTutoringSessionsList($crn, $pidm) {
        try{
            $qry = "  SELECT   v.last_name, v.first_name, tut.*, pers2.lfm_name tutor_name
                        FROM         (SELECT   wst.student_gcid,
                                               wst.tutor_gcid,
                                               wst.tutorial_start_time,
                                               wst.tutorial_end_time,
                                               wst.tutorial_notes
                                        FROM   (SELECT   subj,
                                                         title,
                                                         numb,
                                                         crn
                                                  FROM   v_course
                                                 WHERE   term_code = fg_current_term()) fac,
                                               wsctutor wst
                                       WHERE   fac.crn = wst.course_crn AND wst.course_crn = :CRN)
                                     tut
                                  INNER JOIN
                                     v_person v
                                  ON CAST (tut.student_gcid AS VARCHAR2 (9)) = v.id
                               LEFT JOIN
                                  v_person pers2
                               ON pers2.id = CAST (tut.tutor_gcid AS VARCHAR2 (9))
                    ORDER BY   v.last_name, v.first_name, tutorial_start_time";
        
            //prepared statement
            $stid = oci_parse($this->conn, $qry);
            
            //bind data
            oci_bind_by_name($stid, ':CRN', $crn);
            oci_bind_by_name($stid, ':PIDM', $pidm);

            //exec query
            oci_execute($stid);
            
            $nrows = oci_fetch_all($stid, $results);
            
            oci_free_statement($stid);

            return $results;
        
        } catch(Exception $e){
            
        }//end catch      
    }//end fetchFacultyCourseList()
        
}


