<?php

    abstract class Parser_Rule_Abstract {

        private $_rule;

        private $_callback;

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

    }

?>