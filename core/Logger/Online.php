<?php
    /*
    *   LOGGER version 1.0
    *
    *   Imagina - Plugin.
    *
    *
    *   Copyright (c) 2012 Dolem Labs
    *
    *   Authors:    Paul Marclay (paul.eduardo.marclay@gmail.com)
    *
    */
    
    class Logger_Online extends Logger_Primitive {
        
        public static function put($data, $indent = 0, $color='black') {
            self::$_data .= "<p style='padding-left:$indent px;color:$color; margin:0px'>$data</p>";
        }
        
        public static function dump() {
           echo '<script type="text/javascript">$(function(){$("#logs").easydrag();});</script>';
           echo '<div id="logs" style="position:fixed; top:30%;background-color:gray;text-align:left;opacity:0.93;padding-left:10px;">';
           echo '<div style="background-color:lightgray;"><div style="display:inline;" onClick="$(\'#logs\').css(\'display\', \'none\');">X</div> - Logs</div>';
           echo '<div>'; print_r(self::getLog()); 
           echo '</div></div>';

        }
    }