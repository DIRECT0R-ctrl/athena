<?php
class Validator {
    private $errors = [];
    private $data;
    
    public function __construct($data) {
        $this->data = $data;
    }
    
    public function validate($rules) {
        foreach ($rules as $field => $ruleSet) {
            $rulesArray = explode('|', $ruleSet);
            $value = $this->data[$field] ?? null;
            
            foreach ($rulesArray as $rule) {
                if ($rule === 'required' && empty($value)) {
                    $this->addError($field, "The {$field} field is required.");
                }
                
                if (strpos($rule, 'min:') === 0) {
                    $min = (int) str_replace('min:', '', $rule);
                    if (strlen($value) < $min) {
                        $this->addError($field, "The {$field} must be at least {$min} characters.");
                    }
                }
                
                if (strpos($rule, 'max:') === 0) {
                    $max = (int) str_replace('max:', '', $rule);
                    if (strlen($value) > $max) {
                        $this->addError($field, "The {$field} must not exceed {$max} characters.");
                    }
                }
                
                if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "The {$field} must be a valid email address.");
                }
                
                if ($rule === 'unique:users') {
                    // i will implement this later with database check please do not forget
                }
            }
        }
        
        return empty($this->errors);
    }
    
    private function addError($field, $message) {
        $this->errors[$field][] = $message;
    }
    
    public function getErrors() {
        return $this->errors;
    }
    
    public function getFirstError($field) {
        return $this->errors[$field][0] ?? null;
    }
}
