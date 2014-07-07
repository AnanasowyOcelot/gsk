<?php
  
Class Core_Form_FieldSelect extends Core_Form_Field
{
    private $options = array();
    
    public function addOption($value, $name) {
        $option = array(
            'value' => $value,
            'name' => $name
        );
        $this->options[] = $option;
    }
    
    public function getOptions() {
        return $this->options;
    }
}
