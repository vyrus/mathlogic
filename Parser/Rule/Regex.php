<?php

    class Parser_Rule_Regex extends Parser_Rule_Abstract {

        protected $_matches;

        public static function create() {
            return new self();
        }

        public function match(& $string) {
            $regex = $this->get();

            if (false === ($token = $this->_getToken($regex, $string))) {
                return false;
            }

            $this->_callback(array('token' => $token));

            return true;
        }

        protected function _getToken($regex, & $string) {
            $regex = '/^' . $regex . '/i';
            $cb = array($this, '_getTokenCallback');

            $string = preg_replace_callback($regex, $cb, $string, 1, $count);

            if (!$count) {
                return false;
            }

            $token = array_shift($this->_matches);
            return $token;
        }

        protected function _getTokenCallback($matches) {
            $this->_matches = $matches;
            return '';
        }

    }

?>