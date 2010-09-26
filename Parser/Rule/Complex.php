<?php

    class Parser_Rule_Complex extends Parser_Rule_Abstract {

        public static function create() {
            return new self();
        }

        public function match(& $string) {
            $rule = $this->get();

            foreach ($rule as $subrule)
            {
                if (false === $subrule->match($string)) {
                    return false;
                }
            }

            $this->_callback();
        }

    }

?>