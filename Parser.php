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
            $on_not_var = array($this, 'onNotVar');
            $on_var_op_var = array($this, 'onVarOpVar');
            $on_not_expr = array($this, 'onNotExpression');

            $negation = Parser_Rule_Regex::create();
            $negation->set('![\s]*')
                     ->setCallback(array($this, 'onNegation'));
            $negation->ID = 'NEGATION';

            $operator = Parser_Rule_Regex::create();
            $operator->set('(?:&|\||->|<->)[\s]*')
                     ->setCallback(array($this, 'onOperator'));
            $operator->ID = 'OPERATOR';

            $variable = Parser_Rule_Regex::create();
            $variable->set('[a-z]+[\s]*')
                     ->setCallback(array($this, 'onVariable'));
            $variable->ID = 'VARIABLE';

            $expr = Parser_Rule_Or::create();
            $not_expr = Parser_Rule_Complex::create();
            $expr_op_expr = Parser_Rule_Complex::create();

            $expr->set(array($variable, $not_expr, $expr_op_expr))
                 ->setCallback(array($this, 'onExpr'));
            $expr->ID = 'EXPRESSION';

            $not_expr->set(array($negation, $expr))
                     ->setCallback(array($this, 'onNotExpr'));
            $not_expr->ID = 'NOT EXPR';

            $expr_op_expr->set(array($expr, $operator, $expr))
                         ->setCallback(array($this, 'onExprOpExpr'));
            $expr_op_expr->ID = 'EXRP OP EXPR';

            /*
            $not_expr = Parser_Rule_Complex::create();
            $expr_op_expr = Parser_Rule_Complex::create();
            $expr = Parser_Rule_Or::create();

            $not_expr->set(array($negation, $expr))
                     ->setCallback($on_not_expr);
            $expr_op_expr->set(array($expr, $operator, $expr));
            $expr->set(array($not_var, $var_op_var, $not_expr, $expr_op_expr));
            */

            $expr->match($string);

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

        public function onNegation($params) {
            $op = '!';
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

        public function onExpr($params) {
            print_r($this->_stack);
        }

        public function onNotExpr($params) {
            print_r($this->_stack);
        }

        public function onExprOpExpr($params) {
            print_r($this->_stack);
        }

    }

?>