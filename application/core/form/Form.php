<?php
  
Class Core_Form_Form
{
    public $name = '';
    public $action = '';
    private $fields = array();
    
    public function __construct() {
        
    }
    
    public function addField(Core_Form_Field $field) {
        $this->fields[] = $field;
    }
    
    /**
    * @param string $fieldName
    * @return Core_Form_Field
    */
    public function getField($fieldName) {
        foreach($this->fields as $field) {
            if($field->name == $fieldName) {
                return $field;
            }
        }
        return null;
    }
    
    /**
    * @return array
    */
    public function getAllFields() {
        return $this->fields;
    }
}
