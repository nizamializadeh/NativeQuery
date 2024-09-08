<?php
class DB {
    public static function raw($expression) {
        return new RawExpression($expression);
    }

}