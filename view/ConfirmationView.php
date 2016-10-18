<?php
/**
 * Class ConfirmationView.
 *
 * @author Joseph Daigle
 */
class ConfirmationView extends View {
    
    public function output() {
        
        $html = parent::get_header();
        
        $html .= <<<HTML
                <div>
                    <p>The tutorial has been successfully saved!</p>
                </div>
                <span class="button"><a href="/student-tutoring-app/?action=init">Add Another Tutorial</a></span>
HTML;
        
        $html .= parent::get_footer();
        
        return $html;
    }
}