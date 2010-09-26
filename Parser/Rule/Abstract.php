<?php

    abstract class Parser_Rule_Abstract {

        private $_rule;

        private $_callback;

        protected static $_debug = array();

        public function set($rule) {
            $this->_rule = $rule;
            return $this;
        }

        public function get() {
            return $this->_rule;
        }

        public function setCallback($cb) {
            $this->_callback = $cb;
        }

        protected function _callback($params = array()) {
            if (!isset($this->_callback)) {
                return;
            }

            call_user_func($this->_callback, $params);
        }

        abstract public function match(& $string);

        protected static function _logMatchStart($id, $string) {
            $record = array('event'   => 'start',
                            'rule_id' => $id,
                            'string'  => $string);

            self::$_debug[] = $record;
            file_put_contents('./debug', print_r($record, true), FILE_APPEND);
        }

        protected static function _logMatchEnd($id, $string, $result) {
            $result = ($result ? 'true' : 'false');

            $record = array('event'   => 'end',
                            'rule_id' => $id,
                            'string'  => $string,
                            'result'  => $result);

            self::$_debug[] = $record;
            file_put_contents('./debug', print_r($record, true), FILE_APPEND);
        }

        public static function getDebug() {
            return self::$_debug;
        }

    }

?>