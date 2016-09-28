<?php

namespace core\classes;

trait RequestAssistant
{
    protected $selectItems = '*';
    protected $conditions = [];
    protected $binding = [];
    protected $order = '';
    protected $alterConditions = [];
    protected $allowed = ['>', '!=', '<', '>=', '<=', '='];

    public $result;

    public function getAll()
    {
        $result = [];
        if (!$this->binding && !$this->order && !$this->selectItems) {
            $stmt = $this->connection->prepare('SELECT * FROM ' . $this->tableName);
            $stmt->execute();
            $rows = $stmt->fetchAll();
            foreach ($rows as $row) {
                $this->result = $row;
                $result[] = clone $this;
            }
            return $result;
        } else die('Another method(s) was called!');
    }

    public function __call($name, $arguments)
    {
        if (strpos(strtolower($name), 'where') === false) {
            die('Invalid function called!');
        }
        $or = '';
        $temp = '';
        $name = strtolower($name);
        if (is_int(strpos($name, 'or'))) {
            $temp = str_replace('or', '', $name);
            $or = 'or';
        }
        $temp = str_replace('where', '', $temp ? $temp : $name);
        if (strpos($temp, 'and')) {
            $fields = explode('and', $temp);
            $params = [];
            foreach ($fields as $key => $value) {
                $params[$fields[$key]] = $arguments[$key];
            }
            $this->where($or, $params);
        } elseif ($temp === '') {
            $this->where($or, ...$arguments);
        } else {
            $this->where($or, strtolower($temp), ...$arguments);
        }

        return $this;
    }

    public function select(...$fields)
    {
        $this->selectItems = implode(',', (is_array($fields[0])) ? $fields[0] : $fields);
        return $this;
    }

    public function where($or, ...$params)
    {
        $conditions = [];

        if (is_array($params[0])) {
            if (!isset($params[0][0])) {    // если массив ассоциативный
                foreach ($params[0] as $key => $value) {
                    $bind = $this->bindings($key, $value);
                    array_push($conditions, $key . '=' . $bind);
                }

            } else {
                die('Invalid params (isArray)');
            }
        } else {
            $count = count($params);
            if (($count === 4) && (strtolower($params[1]) == 'between')) {//если условие "between"
                $from = $this->bindings($params[0], $params[2]);
                $to = $this->bindings($params[0], $params[3]);
                array_push($conditions, $params[0] . ' BETWEEN ' . $from . ' AND ' . $to);
            } elseif ($count === 2) {
                $bind = $this->bindings($params[0], $params[1]);
                array_push($conditions, $params[0] . '=' . $bind);
            } elseif ($count === 3) {//если вид запроса ('id','>=',1)
                if (!in_array($params[1], $this->allowed)) {
                    die('Invalid params id=>1 ');
                }
                $bind = $this->bindings($params[0], $params[2]);
                array_push($conditions, $params[0] . $params[1] . $bind);
            } else {
                die('Invalid params');
            }
        }
        foreach ($conditions as $key => $value) {
            if ($or) {
                if (isset($this->alterConditions[$key])) {
                    $key = ' ' . $key;//@todo ????
                }
                $this->alterConditions[$key] = $value;
            } else {
                if (isset($this->conditions[$key])) {
                    $key = ' ' . $key;//@todo ????
                }
                $this->conditions[$key] = $value;
            }
        }
        return $this;
    }

    public function bindings($field, $param)
    {
        $bind = ':' . $field;
        if (isset($this->binding[$bind])) {
            $i = 1;
            $bindNumber = $bind . $i;
            while (isset($this->binding[$bindNumber])) {
                $i++;
            }
            $this->binding[$bindNumber] = $param;
            return $bindNumber;
        } else {
            $this->binding[$bind] = $param;
            return $bind;
        }

    }

    public function whereBetween($field, $min, $max)
    {
        $this->where('', $field, 'between', $min, $max);
        return $this;
    }

    public function orWhereBetween($field, $min, $max)
    {
        $this->where('or', $field, 'between', $min, $max);
        return $this;
    }

    public function orderBy(...$params)
    {
        $count = 1;
        if (is_array($params[0])) {                    //если в параметрах массив
            foreach ($params[0] as $key => $value) {
                if ($count === 1) {
                    if (is_numeric($key)) {
                        $this->order .= $value;
                    } elseif (strtolower($value) == 'desc') {
                        $this->order .= $key . ' DESC';
                    } else {
                        $this->order .= $key;
                    }
                    $count++;
                    continue;
                } elseif (is_numeric($key)) {
                    $this->order .= ',' . $value;
                } elseif (strtolower($value) == 'desc') {
                    $this->order .= ',' . $key . ' DESC';
                } else {
                    $this->order .= ',' . $key;
                }
                $count++;
            }
        } else {
            foreach ($params as $key => $value) {
                if ($count === 1) {
                    $this->order .= $value;
                } elseif (strtolower($value) === 'desc') {
                    $this->order .= ' DESC';
                } elseif (strtolower($value) === 'asc') {
                    $this->order .= ' ASC';
                } else {
                    $this->order .= ',' . $value;
                }
                $count++;
            }
        }

        return $this;
    }

    protected function prepare()
    {
        $where = $orWhere = $order = '';
        if ($this->conditions) {
            $where = ' WHERE ';
            $countWhere = 1;
            foreach ($this->conditions as $value) {
                if ($countWhere === 1) {
                    $where .= $value;
                    $countWhere++;
                    continue;
                }
                $where .= ' AND ' . $value;
            }
        }
        if ($this->alterConditions) {
            $orWhere = ' OR ';
            $countOrWhere = 1;
            foreach ($this->alterConditions as $value) {
                if ($countOrWhere === 1) {
                    $orWhere .= $value;
                    $countOrWhere++;
                    continue;
                }
                $orWhere .= ' AND ' . $value;
            }
        }
        if ($this->order) {
            $order = ' ORDER BY ' . $this->order;
        }
        return [$where, $orWhere, $order];
    }

    public function get()
    {
        $result = [];
        $prepare = $this->prepare();
        $stmt = $this->connection->prepare('SELECT ' . $this->selectItems . ' FROM ' . $this->tableName .
            $prepare[0] . $prepare[1] . $prepare[2]);

        $stmt->execute($this->binding);
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
            $this->result = $row;
            $result[] = clone $this;
        }
        $this->selectItems = '*';//@todo ??           обнуляю значения на дефолтные
        $this->conditions = [];
        $this->alterConditions = [];
        $this->order = '';
        return $result;
    }
}