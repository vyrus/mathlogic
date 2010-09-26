<?php

    class Parser_Rule_Or extends Parser_Rule_Abstract {

        public static function create() {
            return new self();
        }

        public function match(& $string) {
            $rule = $this->get();

            foreach ($rule as $subrule)
            {
                if ($subrule->match($string)) {
                    $this->_callback();
                    return true;
                }
            }

            return false;
        }

    }

?>