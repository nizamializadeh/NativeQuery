<?php
class RawExpression {
    protected $expression;

    public function __construct($expression) {
        $this->expression = $expression;
    }

    public function __toString() {
        return $this->expression;
    }

}