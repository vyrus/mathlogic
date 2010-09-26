<?php

    /**
    * @todo Incorporate parser logic into Expression itself.
    */
    class Parser {

        protected $_matches;

        protected $_rules;

        protected $_stack = array();

        public static function create() {
            return new self();
        }

        public function parse($string) {
            $dummy_cb = array($this, 'ok');
            $on_variable = array($this, 'onVariable');
            $on_operator = array($this, 'onOperator');
            $on_not_var = array($this, 'onNotVar');
            $on_var_op_var = array($this, 'onVarOpVar');
            $on_expression = array($this, 'onExpression');

            $negation = Parser_Rule_Regex::create();
            $negation->set('![\s]*');

            $operator = Parser_Rule_Regex::create();
            $operator->set('(?:&|\||->|<->)[\s]*')
                     ->setCallback($on_operator);

            $variable = Parser_Rule_Regex::create();
            $variable->set('[a-z]+[\s]*')
                     ->setCallback($on_variable);

            $not_var = Parser_Rule_Complex::create();
            $not_var->set(array($negation, $variable))
                    ->setCallback($on_not_var);

            $var_op_var = Parser_Rule_Complex::create();
            $var_op_var->set(array($variable, $operator, $variable))
                       ->setCallback($on_var_op_var);

            $expression = Parser_Rule_Or::create();
            $expression->set(array($not_var, $var_op_var));

            $rules = array(
                'expression' => $expression,
                'variable'   => $variable,
                'operator'   => $operator,
                'negation'   => $negation
            );

            $expression->match($string);

            return array('result' => false,
                         'string' => $string,
                         'stack'  => $this->_stack);
        }

        public function onVariable($params) {
            $var = trim($params['token']);
            array_push($this->_stack, $var);
        }

        public function onOperator($params) {
            $op = trim($params['token']);
            array_push($this->_stack, $op);
        }

        public function onNotVar($params) {
            $var = array_pop($this->_stack);

            $expr = Expression::create();
            $expr->setOperator(Operator::NEGATION)
                 ->addOperand($var);

            array_push($this->_stack, $expr);
        }

        public function onVarOpVar($params) {
            $var1 = array_pop($this->_stack);
            $op = array_pop($this->_stack);
            $var2 = array_pop($this->_stack);

            $op_map = array(
                '&'   => Operator::CONJUNCTION,
                '|'   => Operator::DISJUNCTION,
                '->'  => Operator::IMPLICATION,
                '<->' => Operator::EQUIVALANCE
            );

            $op = $op_map[$op];

            $expr = Expression::create();
            $expr->setOperator($op)
                 ->addOperand($var1)
                 ->addOperand($var2);

            array_push($this->_stack, $expr);
        }

        public function onExpression($params) {
            print_r($this->_stack);
        }

        public function ok($params) {
            $ok = true;
        }

    }

?>