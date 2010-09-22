<?php

    class Operator {

        const NEGATION = 'not';

        const DISJUNCTION = 'or';

        const CONJUNCTION = 'and';

        const IMPLICATION = 'then';

        const EQUIVALANCE = 'equal';

        protected static $_render_map = array(
            self::NEGATION    => '&not;',
            self::DISJUNCTION => '&or;',
            self::CONJUNCTION => '&amp;',
            self::IMPLICATION => '&rarr;',
            self::EQUIVALANCE => '&harr;',
        );

        public static function toHtml($op) {
            return self::$_render_map[$op];
        }

    }

?>