<?php

    require_once 'Operator.php';
    require_once 'Expression.php';
    require_once 'Decorator/Interface.php';

    class OperatorDecorator implements Decorator_Interface {
        public function decorate($op) {
            return '<a href="" class="operation" onclick="return false;">' . $op . '</a>';
        }
    }

    class ExpessionDecorator implements Decorator_Interface {
        public function decorate($expr) {
            return '<span class="expr">' . $expr . '</span>';
        }
    }

    $p = 'P';
    $q = 'Q';

    $not_p = Expression::create();
    $not_p->setOperator(Operator::NEGATION)
          ->addOperand($p);

    $not_p_or_q = Expression::create();
    $not_p_or_q->setOperator(Operator::CONJUNCTION)
               ->addOperand($not_p)
               ->addOperand($q);

    $p_and_q = Expression::create();
    $p_and_q->setOperator(Operator::CONJUNCTION)
            ->addOperand($p)
            ->addOperand($q);

    $p_or_q = Expression::create();
    $p_or_q->setOperator(Operator::DISJUNCTION)
           ->addOperand($p)
           ->addOperand($q);

    $complex_1 = Expression::create();
    $complex_1->setOperator(Operator::DISJUNCTION)
              ->addOperand($not_p_or_q)
              ->addOperand($p_and_q);

    $complex_2 = Expression::create();
    $complex_2->setOperator(Operator::IMPLICATION)
              ->addOperand($p_or_q)
              ->addOperand($complex_1);

    $expressions = array(
        $not_p,
        $not_p_or_q,
        $p_and_q,
        $p_or_q,
        $complex_1,
        $complex_2
    );

    $decorator_op   = new OperatorDecorator();
    $decorator_expr = new ExpessionDecorator();

    foreach ($expressions as $key => $expr) {
        $expressions[$key] = $expr->setDecorator('operator', $decorator_op)
                                  ->setDecorator('expression', $decorator_expr);
    }

    include 'expression.tpl';

?>