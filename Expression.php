<?php

    class Expression {

        protected $_operator;

        protected $_operands;

        protected $_decorators = array();

        public static function create() {
            return new self();
        }

        public function setOperator($op) {
            $this->_operator = $op;
            return $this;
        }

        public function addOperand($opr) {
            $this->_operands[] = $opr;
            return $this;
        }

        public function getNumOperands() {
            return sizeof($this->_operands);
        }

        public function setDecorator($elem, Decorator_Interface $decorator) {
            $this->_decorators[$elem] = $decorator;
            return $this;
        }

        public function getDecorator($elem) {
            if (isset($this->_decorators[$elem])) {
                return $this->_decorators[$elem];
            }

            return false;
        }

        public function setOperatorDecorator(Decorator_Interface $decorator) {
            $this->_decorators->operator = $decorator;
        }

        public function render() {
            $operands = array();

            foreach ($this->_operands as $opr)
            {
                if ($opr instanceof Expression)
                {
                    $opr_rendered = $opr->render();

                    $num_oprs = $opr->getNumOperands();
                    if ($num_oprs > 1) {
                        $opr_rendered = '(' . $opr_rendered . ')';
                    }

                    $opr = $opr_rendered;
                }

                $operands[] = $opr;
            }

            $operator = Operator::toHtml($this->_operator);

            if (false !== ($decorator = $this->getDecorator('operator'))) {
                $operator = $decorator->decorate($operator);
            }

            switch (sizeof($operands))
            {
                case 1:
                    $format = '%s %s';
                    $params = array($operator, $operands[0]);
                    break;

                case 2:
                    $format = '%s %s %s';
                    $params = array($operands[0], $operator, $operands[1]);
                    break;
            }

            $expression = vsprintf($format, $params);

            if (false !== ($decorator = $this->getDecorator('expression'))) {
                $expression = $decorator->decorate($expression);
            }

            return $expression;
        }

    }

?>