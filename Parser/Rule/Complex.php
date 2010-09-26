<?php

    class Parser_Rule_Complex extends Parser_Rule_Abstract {

        public static function create() {
            return new self();
        }

        public function match(& $string) {
            self::_logMatchStart($this->ID, $string);
            $rule = $this->get();

            foreach ($rule as $subrule)
            {
                if (false === $subrule->match($string)) {
                    self::_logMatchEnd($this->ID, $string, false);
                    return false;
                }
            }

            $this->_callback();
            self::_logMatchEnd($this->ID, $string, true);
            return true;
        }

    }

?>