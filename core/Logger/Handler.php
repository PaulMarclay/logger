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

    class Logger_Handler {
        
        public static function errorHandler($code, $message, $file, $line) {
            Api::getLog()->put('Error: '.$message.' '.$file.' '.$line.' ('.$code.')', 4, 'orange');
            
            $msg        = "File: $file\nLine: $line\nProblem: $message\n\n";
            $sourceCode = "<?php\n".self::getContextFileLineError($file, $line, false)."\n?>";
            
            $h = new Php_Highlight();
            $h->loadString($sourceCode);
            $sourceCode = $h->toHtml(true, true, null, true, $line - 2, $line);
            
            Api::getSession()->setLastErrorMessage($msg);
            Api::getSession()->setLastErrorSourceCode($sourceCode);
            
//            Controller_Router::getController()->redirect('/application/show_error');
            Controller_Router::router('/application/show_error');
        }
        
        public static function exceptionHandler($exception) {
            Api::getLog()->put('Exception: '.$exception->getMessage(),4,'red');

            // --
            // these are our templates
            $traceline = "#%s %s(%s): %s(%s)";
            $msg = "PHP Fatal error:  Uncaught exception '%s' with message '%s' in %s:%s\nStack trace:\n%s\n  thrown in %s on line %s";

            // alter your trace as you please, here
            $trace = $exception->getTrace();
            $showParameters = 1;
            if ($showParameters == 0) {
                foreach ($trace as $key => $stackPoint) {
                    // I'm converting arguments to their type
                    // (prevents passwords from ever getting logged as anything other than 'string')
                    $trace[$key]['args'] = array_map('gettype', $trace[$key]['args']);
                }
            }
            
            // build your tracelines
            $result = array();
            $cnt=0;
            foreach ($trace as $key => $stackPoint) {
                $cnt++;
                if ($showParameters == 1) {
                    $ret = '';
                    $keys = array_keys($stackPoint['args']);
                    $end = end($keys);
                    foreach($stackPoint['args'] as $key => $item) {
                        if (is_object($item)) {
                            $ret .= gettype($item);
                        } elseif (is_array($item)) {
                            
                                
                            try {
                                $ret .= 'array("';
                                $ret .= implode('","', $item);
                                $ret .= '")';
                            } catch (Exception $e) {
                                die('ups!');
                            }
                        } elseif (is_numeric($item)) {
                            $ret .= $item;
                        } else {
                            $ret .= "\"$item\"";
                        }
                        if ($key != $end) {
                            $ret .= ',';
                        }
                    }
                }
                
                $result[] = sprintf(
                    $traceline,
                    $cnt,
                    ((isset($stackPoint['file']) ? $stackPoint['file'] : '--' )),
                    ((isset($stackPoint['line']) ? $stackPoint['line'] : '--' )),
                    ((isset($stackPoint['function']) ? $stackPoint['function'] : '--')),
                    $ret
                );
            }

            $result = array_reverse($result);

            // write tracelines into main template

            $msg = self::getContextFileLineError($exception->getFile(),$exception->getLine())."\n\n".$msg;
            
            $msg = sprintf(
                $msg,
                get_class($exception),
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine(),
                implode("\n", $result),
                $exception->getFile(),
                $exception->getLine()
            );

            // --
            
            $file       = $exception->getFile();
            $line       = $exception->getLine();
            $sourceCode = "<?php\n".self::getContextFileLineError($file, $line, false)."\n?>";
            
            $h = new Php_Highlight();
            $h->loadString($sourceCode);
            $sourceCode = $h->toHtml(true, true, null, true, $line - 2, $line);
            
            Api::getSession()->setLastErrorMessage($msg);
            Api::getSession()->setLastErrorSourceCode($sourceCode);
            
//            Controller_Router::getController()->redirect('/application/show_exception?error_message='.urlencode($msg));
            Controller_Router::router('/application/show_exception');
            
        }
        
        public static function shutdownHandler() {
            if ($error = self::checkForErrors()){
                Api::getLog()->put('Error: '.$error['message'].' '.$error['file'].' '.$error['line'].' ('.$error['type'].')', 4, 'orange');
            }
            
            Api::getLog()->put("Exit\n", 0, 'green');
            Api::getLog()->dump();
            
            if ($error) {
                self::errorHandler($error['type'], $error['message'], $error['file'], $error['line']);
            }
        }

        public static function getContextFileLineError($filePath, $line, $includeLineNumbers = true) {
            $fileContent    = file($filePath);
            $fileContent    = array_slice($fileContent, ($line - 3), 6);
            $fileContent[2] = str_replace("\n", ' ', $fileContent[2]);
            $fileContent[2] .= " // <<---- Hey, wake up!, the problem is here!!!\n";

            $k = $line - 3;
            foreach ($fileContent as $key => $lineContent) {
                $fileContent[$key] = str_replace("\n", ' ', $fileContent[$key]);
                if ($includeLineNumbers) {
                    $k++;
                    if ($k == $line ) {
                        $fileContent[$key] = sprintf("[%s]\t%s",  $k, $lineContent);
                    } else {
                        $fileContent[$key] = sprintf("%s\t%s",  $k, $lineContent);
                    }
                } else {
                    $fileContent[$key] = sprintf("%s", $lineContent);
                }
            }

            return implode("", $fileContent);
        }
        
        public static function checkForErrors() {
            if ($error = error_get_last()){
                switch($error['type']){
                    case E_ERROR:
                    case E_CORE_ERROR:
                    case E_COMPILE_ERROR:
                    case E_USER_ERROR:
                    case E_RECOVERABLE_ERROR:
                    case E_CORE_WARNING:
                    case E_COMPILE_WARNING:
                    case E_PARSE:
                        return $error;
                        break;
                }
            }
            
            return null;
        }
    }