<?php

/**
 * Description of TutorialDao
 *
 * @author Joseph Daigle
 */

interface TutorialDao {
    
    public function save_tutorial($tutorial);

    public function get_pidm($gcid);
    
    public function get_current_term();
}
