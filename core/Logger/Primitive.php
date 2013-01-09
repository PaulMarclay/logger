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
    
    class Logger_Primitive extends Ancestor {
        
        // -- Vars
        
        protected static $_sqlConnections   = 0;
        protected static $_sqlQueryes       = 0;
        protected static $_views            = 0;
        protected static $_partials         = 0;
        protected static $_data             = '';

        // -- Getters
        
        public static function getSqlQueryes() {
            return self::$_sqlQueryes;
        }
        
        public static function getViews() {
            return self::$_views;
        }
        
        public static function getPartials() {
            return self::$_partials;
        }

        public static function getSqlConnections() {
            return self::$_sqlConnections;
        }

        public static function getLog() {
            return self::$_data;
        }
        
        // -- Setters
        
        public static function setSqlQueryes($value) {
            self::$_sqlQueryes = $value;
        }
        
        public static function setViews($value) {
            self::$_views = $value;
        }
        
        public static function setPartials($value) {
            self::$_partials = $value;
        }

        public static function setSqlConnections($value) {
            self::$_sqlConnections = $value;
        }
        
        // -- Misc methods
        
        public static function incrementSqlQueryes($value) {
            self::setSqlQueryes(self::getSqlQueryes() + $value);
        }
        
        public static function incrementViews($value) {
            self::setViews(self::getViews() + $value);
        }
        
        public static function incrementPartials($value) {
            self::setPartials(self::getPartials() + $value);
        }
        
        public static function incrementSqlConnections($value) {
            self::setSqlConnections(self::getSqlConnections() + $value);
        }
        
    }