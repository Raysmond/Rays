<?php
/**
 * Model
 *
 * @author: Raysmond
 * @created: 2013-12-20
 */

class Model extends RModel
{
    public static $rules = [];
    public static $primary_key = "id";
    public static $table = '';
    public static $relation = [];
    public static $mapping = [];
    private $errors = [];

    /**
     * Support massive data assignments
     * @param array $assignments
     */
    public function __construct($assignments = [])
    {
        $this->assign($assignments);
    }

    public function assign($assignments = [])
    {
        if (!empty($assignments)) {
            foreach ($this::$mapping as $objCol => $dbCol) {
                if (isset($assignments[$objCol])) {
                    $this->$objCol = $assignments[$objCol];
                }
            }
        }
    }

    /**
     * Get validation rules
     * @param string $apply the type of the rules
     * @return array
     */
    public static function getRules($apply = '')
    {
        $class = get_called_class();
        if ($apply === '') {
            return $class::$rules;
        }
        $rules = [];
        foreach ($class::$rules as $field => $rule) {
            $rule["field"] = $field;
            if (!isset($rule['apply'])) {
                $rules[] = $rule;
            } else {
                if (is_array($rule['apply']) && in_array($apply, $rule['apply'])) {
                    $rules[] = $rule;
                } else if (!empty($rule["apply"]) && $rule["apply"] == $apply) {
                    $rules[] = $rule;
                }
            }
        }
        return $rules;
    }

    /**
     * Run validation and save the entity if it passed the validation.
     * @param string $applyRule
     * @return bool|Id false if validation failed or the ID of the saved entity
     */
    public function validate_save($applyRule = '')
    {
        if ($this->validate($applyRule)) {
            return parent::save();
        } else {
            return false;
        }
    }

    /**
     * Validate the entity
     * @param string $applyRule
     * @return bool
     */
    public function validate($applyRule = '')
    {
        $rules = self::getRules($applyRule);
        if (!empty($rules)) {
            $validation = new RFormValidationHelper($rules);
            if (!$validation->run($this->getDataArray())) {
                $this->errors = $validation->getErrors();
                return false;
            }
        }
        return true;
    }

    /**
     * Get the data array of the object
     * @return array
     */
    public function getDataArray()
    {
        $data = [];
        foreach ($this::$mapping as $objCol => $dbCol) {
            $data[$objCol] = $this->$objCol;
        }
        return $data;
    }

    /**
     * Delete all data record with constraint
     * @param string $constraint
     * @param array $args
     * @return mixed
     */
    public function deleteAll($constraint = "", $args = [])
    {
        return self::where($constraint, $args)->delete();
    }

    /**
     * Get validation errors
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}