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

    public function getAll()//@todo return массив обьектов, если был вызван любой другой метод, то кидать exception (where(), select(), orderBy())
    {
        $stmt = $this->connection->prepare('SELECT * FROM ' . $this->tableName);
        $stmt->execute();
        $row = $stmt->fetchAll();
        return $row;
    }

    public function __call($name, $arguments) //@todo принимает любой метод, например ->adffd() и попадает на 44 строку (else)
    {
        $or = false;
        $temp = '';
        if (str_replace('or', '', $name)) { //@todo всегда true
            $temp = strtolower(str_replace('or', '', $name));//@todo повтор
            $or = true;
        }
        //@todo $or всегда true, неправильное условие
        $temp = str_replace('where', '', $temp ?? $name);//@todo $temp всегда isset
        if (strpos($temp, 'And')) {//@todo всегда false из за 29 строки
            $fields = explode('And', $temp);
            $params = [];
            foreach ($fields as $key => $value) {
                $params[$fields[$key]] = $arguments[$key];
            }
            $this->where($or, $params);
        } elseif ($temp === '') {//@todo попадет если будет вызван метод or() и все
            $this->where($or, $arguments);
        } else { //@todo вызываеть на любой вызов кроме or()
            $this->where($or, strtolower($temp), $arguments);
        }

        return $this;
    }

    public function select(...$fields)
    {
        $this->selectItems = implode(',', (is_array($fields[0])) ? $fields[0] : $fields);
        return $this;
    }

    public function where(...$params)//@todo должен быть метод orWhere()
    {
        $or = false;
        $conditions = [];

        if ($params[0] === true) {//@todo в случае с __call() первый параметр будет true or false
            $params[0] = $params[1][0];
            unset($params[1]);
            $or = true;
        }
        if (is_array($params[0])) {
            if (!isset($params[0][0])) { // если массив ассоциативный
                foreach ($params[0] as $key => $value) {
                    $temp = $this->bindings($key, $value);
                    array_push($conditions, $key . '=' . $temp);
                }
            } else  {
                die('Invalid params');
            }
        } else {
            $count = count($params);
            if (($count === 4) && (strtolower($params[1]) == 'between')) {//если условие "between"
                $from = $this->bindings($params[0], $params[2]);
                $to = $this->bindings($params[0], $params[3]);
                array_push($conditions, $params[0] . ' BETWEEN ' . $from . ' AND ' . $to);
            } elseif ($count === 2) {
                $temp = $this->bindings($params[0], $params[1]);
                array_push($conditions, $params[0] . '=' . $temp);
            } elseif ($count === 3) {//если вид запроса ('id','>=',1)
                if (!in_array($params[1], $this->allowed)) {
                    die('Invalid params');
                }
                $temp = $this->bindings($params[0], $params[2]);
                array_push($conditions, $params[0] . $params[1] . $temp);
            } else {
                die('Invalid params');//@todo метод  ->orWhere('id', 100000) попадает вот сюда
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
            while (isset($this->binding[$bind . $i])) {
                $i++;
            }
            $this->binding[$bind . $i] = $param;//@todo $bind . $i повторение
            return $bind . $i;
        } else {
            $this->binding[$bind] = $param;
            return $bind;
        }

    }

    public function whereBetween($field, $min, $max) //@todo orWhereBetween()??
    {
        $this->where($field, 'between', $min, $max);
        return $this;
    }

    public function orderBy(...$params)
    {
        $count = 1;
        if (is_array($params[0])) {//если в параметрах массив
            foreach ($params[0] as $key => $value) {
                if ($count === 1) {
                    if (is_numeric($key)) {
                        $this->order .= $value;
                    } elseif (strtolower($value) == 'desc') {
                        $this->order .= $key . ' DESC';
                    } else {
                        $this->order .= $key;
                    }
                    $count++;//@todo увеличится 2 раза
                } elseif (is_numeric($key)) {
                    $this->order .= ',' . $value;
                } elseif (strtolower($value) == 'desc') {
                    $this->order .= ',' . $key . ' DESC';
                } else {
                    $this->order .= ',' . $key;
                }
                $count++;
            }
            /*foreach ($params[0] as $key => $value) { //@todo нет дублирования
                if (is_numeric($key)) {
                    $order = $value;
                } else {
                    $order = $key . ' ' . $value;
                }
                $this->order .= ($count === 1) ? $order :',' . $order;
                $count++;
            }*/
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
                /*if ($count === 1) { //@todo на одно условие меньше
                    $this->order .= $value;
                } elseif (in_array(strtolower($value), ['desc', 'asc'])) {
                    $this->order .= $value;
                } else {
                    $this->order .= ',' . $value;
                }*/
                $count++;
            }
        }

        return $this;
    }

    public function get()
    {
        $result = [];
        $where = $orWhere = $order = '';//@todo если поэтапно собирать запрос сразу в одну переменню то это переменные не нужны
        if ($this->conditions) {//@todo 195-218 вынести логику
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
            $order = ' ORDER BY ';
        }
        $stmt = $this->connection->prepare('SELECT ' . $this->selectItems . ' FROM ' . $this->tableName .
            $where . $orWhere . $order . $this->order);//@todo если !$where то $orWhere не конкатенировать
        foreach ($this->binding as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute($this->binding);//@todo второй биндинг
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
            $this->result = $row;
            $result[] = clone $this;
        }
        $this->selectItems = '*';//@todo ??
        $this->conditions = [];
        $this->alterConditions = [];
        $this->order = '';
        return $result;
    }
}