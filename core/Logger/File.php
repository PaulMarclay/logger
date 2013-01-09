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

    class Logger_File extends Logger_Primitive {
        
        public static function put($data, $indent = 0, $color='black') {
            // $fp = fopen(Api::getPath('log').'imagina.log','a');
            //     fwrite($fp, str_repeat(" ", $indent).$data."\r\n");
            // fclose($fp);

            self::$_data .= str_repeat(" ", $indent).$data."\r\n";
        }

        public static function dump() {
            self::toFile();
        }

        public static function toFile() {
            if (!($fp = fopen(Api::getPath('log').'imagina.log','a'))){
                throw new Php_Exception("Impossible open '{Api::getPath('log')}imagina.log' for write!.");
            }
                fwrite($fp, self::getLog()."\r\n");
            fclose($fp);
        }

        
    }