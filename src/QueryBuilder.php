<?php

class QueryBuilder {
    protected $table;
    protected $fields = '*';
    protected $conditions = [];
    protected $bindings = [];
    protected $joins = [];
    protected $groupBy = [];
    protected $having = [];
    protected $orderBy = [];
    protected $limit;
    protected $offset;

    public function table($table) {
        $this->table = $table;
        return $this;
    }

    public function select($fields = '*') {
        if (is_array($fields)) {
            $this->fields = implode(', ', $fields);
        } else {
            $this->fields = $fields;
        }
        return $this;
    }

    public function where($field, $operator, $value) {
        if ($value instanceof RawExpression) {
            $this->conditions[] = "$field $operator $value";
        } else {
            $this->conditions[] = "$field $operator ?";
            $this->bindings[] = $value;
        }
        return $this;
    }
    public function orWhere($field, $operator = null, $value = null) {
        if ($field instanceof \Closure) {
            $query = new self();
            $field($query);
            $this->conditions[] = '(' . implode(' AND ', $query->conditions) . ')';
            $this->bindings = array_merge($this->bindings, $query->bindings);
        } else {
            $this->conditions[] = "OR $field $operator ?";
            $this->bindings[] = $value;
        }
        return $this;
    }
    public function whereBetween($field, $value1, $value2) {
        $this->conditions[] = "$field BETWEEN ? AND ?";
        $this->bindings[] = $value1;
        $this->bindings[] = $value2;
        return $this;
    }
    public function whereNull($field) {
        $this->conditions[] = "$field IS NULL";
        return $this;
    }
    public function whereNotNull($field) {
        $this->conditions[] = "$field IS NOT NULL";
        return $this;
    }
    public function whereIn($field, $values) {
        $this->conditions[] = "$field IN (". implode(', ', $values). ")";
        return $this;
    }
    public function whereNotIn($field, $values) {
        $this->conditions[] = "$field NOT IN (". implode(', ', $values). ")";
        return $this;
    }


    public function join($table, $first, $operator, $second) {
        $this->joins[] = "JOIN $table ON $first $operator $second";
        return $this;
    }

    public function leftJoin($table, $first, $operator, $second) {
        $this->joins[] = "LEFT JOIN $table ON $first $operator $second";
        return $this;
    }
    public function rightJoin($table, $first, $operator, $second) {
        $this->joins[] = "RIGHT JOIN $table ON $first $operator $second";
        return $this;
    }
    public function groupBy($fields) {
        if (is_array($fields)) {
            $this->groupBy = array_merge($this->groupBy, $fields);
        } else {
            $this->groupBy[] = $fields;
        }
        return $this;
    }

    public function having($field, $operator, $value) {
        $this->having[] = "$field $operator ?";
        $this->bindings[] = $value;
        return $this;
    }

    public function orderBy($field, $direction = 'ASC') {
        $this->orderBy[] = "$field $direction";
        return $this;
    }

    public function limit($limit) {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset) {
        $this->offset = $offset;
        return $this;
    }
    public function count() {
        $this->fields = "COUNT(*)";
        return $this;
    }

    public function avg($column) {
        $this->fields = "AVG($column)";
        return $this;
    }

    public function min($column) {
        $this->fields = "MIN($column)";
        return $this;
    }

    public function max($column) {
        $this->fields = "MAX($column)";
        return $this;
    }

    public function sum($expression) {
        if ($expression instanceof RawExpression) {
            $this->fields = "SUM($expression)";
        } else {
            $this->fields = "SUM(?)";
            $this->bindings[] = $expression;
        }
        return $this;
    }

    public function get() {
        $query = "SELECT $this->fields FROM $this->table";

        if (!empty($this->joins)) {
            $query .= ' ' . implode(' ', $this->joins);
        }

        if (!empty($this->conditions)) {
            $query .= " WHERE " . implode(' AND ', $this->conditions);
        }

        if (!empty($this->groupBy)) {
            $query .= " GROUP BY " . implode(', ', $this->groupBy);
        }

        if (!empty($this->having)) {
            $query .= " HAVING " . implode(' AND ', $this->having);
        }

        if (!empty($this->orderBy)) {
            $query .= " ORDER BY " . implode(', ', $this->orderBy);
        }

        if ($this->limit) {
            $query .= " LIMIT $this->limit";
        }

        if ($this->offset) {
            $query .= " OFFSET $this->offset";
        }

        $statement = Database::connect()->prepare($query);
        $statement->execute($this->bindings);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
