<?php

namespace core\classes;

trait RequestAssistant
{
    protected $selectItems = '*';
    protected $conditions = [];
    protected $order = '';
    protected $alterConditions = [];

    public function __call($name, $arguments)
    {
        $temp = str_replace('where', '', $name);
        if (!strpos($temp, 'And')) {
            $this->where(strtolower($temp), $arguments);
        } else {
            $fields = explode('And', $temp);
            $params = [];
            foreach ($fields as $key => $value) {
                $params[$fields[$key]] = $arguments[$key];
            }
            $this->where($params);

        }
    }


    public function select(...$fields)
    {
        if (is_array($fields[0])) {
            $this->selectItems = implode(',', $fields[0]);
            return true;
        } elseif (is_string($fields[0])) {
            $this->selectItems = implode(',', $fields);
            return true;
        } else {
            return false;
        }
    }

    public function where(...$params)
    {
        if (is_array($params[0])) {
            if (!isset($params[0][0])) {                                                                // если массив ассоциативный
                foreach ($params[0] as $key => $value) {
//                    if ($this->conditions) {
//                        $key = ' ' . $key;
//                    }
//                    if (isset($this->conditions[$key])){
//                        $key = ' '.$key;
//                    }
                    array_push($this->conditions ,[$key, '=', $value]);
                }
            } else {
                $this->conditions = [];
                die('Invalid params');
            }
        } else {
//            $params[0] = ' ' . $params[0];
//            if (isset($this->conditions[$params[0]])){
//                $params[0] = ' '.$params[0];
//            }
            if ((strtolower($params[1]) == 'between') && (count($params) === 4)) {             //если условие "between"
                array_push($this->conditions, [$params[0] , ' BETWEEN ' . $params[2] . ' AND ' . $params[3]);//
            } elseif
            (count($params) === 2
            ) {                                                        //если вид запроса ('id',1)
                array_push($this->conditions, $params[0] . '=' . $params[1]);
            } elseif
            (count($params) === 3
            ) {                                                        //если вид запроса ('id','>=',1)
                array_push($this->conditions, $params[0] . $params[1] . $params[2]);
            } else {
                $this->conditions = [];
                die('Invalid params');
            }
        }
        var_dump($this->conditions);
        return $this;

    }

    public function whereBetween($field, $min, $max)
    {
        $this->where($field, 'between', $min, $max);
        return $this;
    }

    public function orWhere(...$params)
    {
        if (is_array($params[0])) {
            if (!isset($params[0][0])) {                                                                // если массив ассоциативный
                foreach ($params[0] as $key => $value) {
                    if ($this->alterConditions) {
                        $key = ' ' . $key;
                    }
                    if (isset($this->alterConditions[$key])){
                        $key = ' '.$key;
                    }
                    $this->alterConditions[$key] = '=' . $value;
                }
            } else {
                $this->alterConditions = [];
                die('Invalid params');
            }
        } elseif ($this->alterConditions) {
            $params[0] = ' ' . $params[0];
            if (isset($this->alterConditions[$params[0]])){
                $params[0] = ' '.$params[0];
            }
            if ((strtolower($params[1]) == 'between') && (count($params) === 4)) {             //если условие "between"
                array_push($this->alterConditions, $params[0] . ' BETWEEN ' . $params[2] . ' AND ' . $params[3]);//
            } elseif
            (count($params) === 2
            ) {                                                        //если вид запроса ('id',1)
                array_push($this->alterConditions, $params[0] . '=' . $params[1]);
            } elseif
            (count($params) === 3
            ) {                                                        //если вид запроса ('id','>=',1)
                array_push($this->alterConditions, $params[0] . $params[1] . $params[2]);
            } else {
                $this->alterConditions = [];
                die('Invalid params');
            }
        }
        var_dump($this->alterConditions);
        return $this;
    }

    public function orderBy(...$params)
    {
        $this->order = 'ORDER BY ';
        if ((is_array($params[0])) && (!isset($params[1]))) {                                     //если в параметрах массив
            $count = 1;
            foreach ($params[0] as $key => $value) {
                if ($count === 1) {
                    if (is_integer($key)) {
                        $this->order .= $value;
                    } elseif (strtolower($value) == 'desc') {
                        $this->order .= $key . ' DESC';
                    } else {
                        $this->order .= $key;
                    }
                    $count++;
                    continue;
                }
                if (is_integer($key)) {
                    $this->order .= ',' . $value;
                } elseif (strtolower($value) == 'desc') {
                    $this->order .= ',' . $key . ' DESC';
                } else {
                    $this->order .= ',' . $key;
                }
                $count++;
                continue;
            }
        } else {
            $count = 1;
            foreach ($params as $key => $value) {
                if ($count === 1) {
                    $this->order .= $value;
                    $count++;
                    continue;
                }
                if (strtolower($value) === 'desc') {
                    $this->order .= ' DESC';
                    $count++;
                    continue;
                }
                if (strtolower($value) === 'asc') {
                    $this->order .= ' ASC';
                    $count++;
                    continue;
                }
                $this->order .= ',' . $value;
                $count++;
            }
        }
    }


}