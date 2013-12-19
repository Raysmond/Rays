<?php
/**
 * RFormValidation helper class file.
 *
 * @author: Raysmond
 */

class RFormValidationHelper
{
    protected $_fields = array();
    protected $_labels = array();
    protected $_rules = array();
    protected $_errors = array();

    public function __construct($rules = array())
    {
        foreach ($rules as $rule) {
            if (!isset($rule['field']))
                continue;
            if (!isset($rule['label'])) {
                $rule['label'] = $rule['field'];
            }
            if (!isset($rule['rules'])) {
                $rule['rules'] = '';
            }
            $_field = $rule['field'];
            $_label = $rule['label'];
            $_rule = $rule['rules'];
            unset($rule['field']);
            unset($rule['label']);
            unset($rule['rules']);
            $this->setRules($_field, $_label, $_rule, $rule);
        }
    }

    public function setRules($field = '', $label = '', $rules = '', $extra = '')
    {
        if ($rules != '') {
            $rules = explode('|', $rules);
            for ($i = 0; $i < count($rules); $i++) {
                if (($pos = strpos($rules[$i], '[')) > 0) {
                    $rules[$i] = array(
                        'rule' => substr($rules[$i], 0, $pos),
                        'param' => substr($rules[$i], $pos + 1, strlen($rules[$i]) - $pos - 2)
                    );
                }
            }
        } else $rules = array();
        $rule = array(
            'field' => $field,
            'label' => $label,
            'rules' => $rules,
        );
        if ($extra != '') {
            $rule = array_merge($rule, $extra);
        }
        $this->_labels[$field] = $label;
        array_push($this->_fields, $field);
        array_push($this->_rules, $rule);
    }

    public function getError($field, $label, $param)
    {
        if ($field == 'required') {
            return $label . " cannot be empty!";
        } elseif ($field == 'is_email') {
            return $label . " must be an email form, like username@example.com!";
        } elseif ($field == 'min_length') {
            return $label . " length must be greater than " . $param . "!";
        } elseif ($field == 'max_length') {
            return $label . " length must be shorter than " . $param . "!";
        } elseif ($field == 'unique') {
            return $label . " must be unique. Please try another!";
        } elseif ($field == 'equals') {
            return $label . " must be equal to " . $this->_labels[$param] . "!";
        }elseif($field=='is_number'||$field=='number'){
            return $label . " must be a number.";
        }
    }


    public function run()
    {
        $isValid = true;
        for ($i = 0; $i < count($this->_rules); $i++) {
            $rule = $this->_rules[$i];
            if (!empty($rule['rules'])) {
                foreach ($rule['rules'] as $r) {
                    $field = $rule['field'];
                    // $r is array like
                    // $r = array('rule'=>'min_length','param'=>5)
                    if (is_array($r)) {
                        $method = $r['rule'];
                        if (method_exists($this, $method) && ($this->$method($_POST[$field], $r['param']) == false)) {
                            $error = array();
                            if (!isset($rule['errors'][$method]))
                                $error[$method] = $this->getError($method, $rule['label'], $r['param']);
                            else
                                $error[$method] = $rule['errors'][$method];

                            if (!isset($this->_errors[$field])) $this->_errors[$field] = array();
                            array_push($this->_errors[$field], $error);
                            $isValid = false;
                            continue;
                        }
                    } else {
                        // The method is not a object method,it's a common php method like 'trim'
                        if (!method_exists($this, $r) && function_exists($r))
                            $r = $_POST[$field] = $r(@$_POST[$field]);

                        else if (method_exists($this, $r)) {
                            if($this->$r($_POST[$field]) == true){
                                continue;
                            }
                            //echo '<-yes';
                            $error = array();
                            if (!isset($rule['errors'][$r]))
                                $error[$r] = $this->getError($r, $rule['label'], '');
                            else
                                $error[$r] = $rule['errors'][$r];
                            if (!isset($this->_errors[$field])) $this->_errors[$field] = array();
                            array_push($this->_errors[$field], $error);
                            $isValid = false;
                            continue;
                        }
                    }
                }
            }
        }
        return $isValid;
    }

    /**
     * Required rule
     * @param $str string
     * @return bool
     */
    public function required($str)
    {
        if (!is_array($str)) {
            return (trim($str) == '') ? false : true;
        } else
            return !empty($str);
    }

    public function regex_match($str, $regex)
    {
        return (!preg_match($regex, $str)) ? false : true;
    }

    public function equals($str, $field)
    {
        if (isset($_POST[$field])){
            return ($str == $_POST[$field]) ? true : false;
        }
        else
            return false;
    }

    public function min_length($str, $len)
    {
        if (!$this->is_number($len))
            return false;
        return (strlen($str) < $len) ? false : true;
    }

    public function max_length($str, $len)
    {
        if (!$this->is_number($len))
            return false;
        return (strlen($str) > $len) ? false : true;
    }

    public function is_email($mail)
    {
        return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $mail)) ? false : true;
    }

    public function unique($str, $tableAndField = '')
    {
        if ($tableAndField == '' || !(strpos('.', $tableAndField) > 0))
            return false;
        $pos = strpos('.', $tableAndField);
        $tableName = substr($tableAndField, 0, $pos);
        $fieldName = substr($tableAndField, $pos + 1);
        if ($tableName == '' || $fieldName == '')
            return false;
        // need to be implemented
        // how to tell whether the field data is unique in the database

        return true;
    }


    public function number($val)
    {
        return $this->is_number($val);
    }

    public function is_number($val)
    {
        return preg_match("/[^0-9]/", $val) ? false : true;
    }

    public function getErrors()
    {
        return $this->_errors;
    }

}