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

    public function __call($name, $arguments)
    {
        $or = false;
        $temp = '';
        if (str_replace('or', '', $name)) {
            $temp = strtolower(str_replace('or', '', $name));
            $or = true;
        }
        $temp = str_replace('where', '', $temp ?? $name);
        if (strpos($temp, 'And')) {
            $fields = explode('And', $temp);
            $params = [];
            foreach ($fields as $key => $value) {
                $params[$fields[$key]] = $arguments[$key];
            }
            $this->where($or, $params);
        } elseif ($temp === '') {
            $this->where($or, $arguments);
        } else {
            $this->where($or, strtolower($temp), $arguments);
        }
        return $this;
    }


    public function select(...$fields)
    {
        $this->selectItems = implode(',', (is_array($fields[0])) ? $fields[0] : $fields);
        return $this;
    }

    public function where(...$params)
    {
        $or = false;
        $conditions = [];
        if ($params[0] === true) {
            $params[0] = $params[1][0];
            unset($params[1]);
            $or = true;
        }

        if (is_array($params[0])) {
            if (!isset($params[0][0])) {                                                                // если массив ассоциативный
                foreach ($params[0] as $key => $value) {
                    $temp = $this->bindings($key, $value);
                    array_push($conditions, $key . '=' . $temp);
                }
            } else  die('Invalid params');
        } else {
            $count = count($params);
            if (($count === 4) && (strtolower($params[1]) == 'between')) {                            //если условие "between"
                $from = $this->bindings($params[1], $params[3]);
                $to = $this->bindings($params[1], $params[4]);
                array_push($conditions, $params[0] . ' BETWEEN ' . $from . ' AND ' . $to);
            } elseif ($count === 2) {
                $temp = $this->bindings($params[0], $params[1]);
                array_push($conditions, $params[0] . '=' . $temp);
            } elseif ($count === 3) {                                                    //если вид запроса ('id','>=',1)
                if (!in_array($params[1], $this->allowed)) {
                    die('Invalid params');
                }
                $temp = $this->bindings($params[0], $params[2]);
                array_push($conditions, $params[0] . $params[1] . $temp);
            } else die('Invalid params');
        }
        foreach ($conditions as $key => $value) {
            if ($or) {
                $this->alterConditions[$key] = $value;
            } else {
                $this->conditions[$key] = $value;
            }
        }
        return $this;
    }

    public function bindings($field, $param)
    {
        $bind = ':' . $field;
        if (isset($this->binding[$bind])) {
            for ($i = 1; $i < 10; $i++) {
                if (!isset($this->binding[$bind . $i])) {
                    $this->binding[$bind . $i] = $param;
                    break;
                }
            }
            return $bind . $i;
        } else {
            $this->binding[$bind] = $param;
            return $bind;
        }

    }

    public function whereBetween($field, $min, $max)
    {
        $this->where($field, 'between', $min, $max);
        return $this;
    }

    public function orderBy(...$params)
    {
        $count = 1;
        if (is_array($params[0])) {                             //если в параметрах массив
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
                    $count++;
                } elseif (strtolower($value) === 'desc') {
                    $this->order .= ' DESC';
                    $count++;
                } elseif (strtolower($value) === 'asc') {
                    $this->order .= ' ASC';
                    $count++;
                } else {
                    $this->order .= ',' . $value;
                    $count++;
                }
            }
        }
        return $this;
    }
}