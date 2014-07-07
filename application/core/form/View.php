<?php

Class Core_Form_View
{
    /**
    * @var Core_Form_Form
    */
    protected $form = null;
    
    public function __construct(Core_Form_Form $form) {
        $this->form = $form;
    }
    
    /**
    * @return string html
    */
    public function formHeader() {
        $html = '<form name="' . $this->form->name . ' action="' . $this->form->action . '">';
        return $html;
    }
    
    /**
    * @return string html
    */
    public function formFooter() {
        return '</form>';
    }
    
    /**
    * wyswietla caly formularz
    * 
    * @return string html
    */
    public function formularz() {
        $html = '';
        
        $html .= $this->formHeader();
        
        $pola = $this->form->getAllFields();
        foreach($pola as $pole) {
            $html .= $this->wiersz($pole->name);
        }
        
        $html .= $this->formFooter();
        
        return $html;
    }
    
    /**
    * wyswietla wiersz (label, pole i pozostale elementy)
    * 
    * @param string $fieldName
    * @return string html
    */
    public function wiersz($fieldName) {
        $html = '<label>' . $this->label($fieldName) . '' . $this->required($fieldName) . ': ' . $this->pole($fieldName) . '</label>';
        return $html;
    }
    
    /**
    * wyswietla label dla pola
    * 
    * @param string $fieldName
    * @return string
    */
    public function label($fieldName) {
        $pole = $this->form->getField($fieldName);
        return $pole->label;
    }
    
    /**
    * wyswietla znacznik "wymagane"
    * 
    * @param string $fieldName
    * @return string html
    */
    public function required($fieldName) {
        $pole = $this->form->getField($fieldName);
        if($pole->isRequired) {
            return ' <span style="color:#ff0000;">*</span>';
        } else {
            return '';
        }
    }
    
    /**
    * wyswietla pole
    * 
    * @param string $fieldName
    * @return string html
    */
    public function pole($fieldName) {
        $pole = $this->form->getField($fieldName);
        
        if($pole instanceof Core_Form_FieldHidden) {
            return $this->wyswietlPoleHidden($pole);
        } elseif($pole instanceof Core_Form_FieldText) {
            return $this->wyswietlPoleText($pole);
        } elseif($pole instanceof Core_Form_FieldPassword) {
            return $this->wyswietlPolePassword($pole);
        } elseif($pole instanceof Core_Form_FieldCheckbox) {
            return $this->wyswietlPoleCheckbox($pole);
        } elseif($pole instanceof Core_Form_FieldSelect) {
            return $this->wyswietlPoleSelect($pole);
        } elseif($pole instanceof Core_Form_FieldTextarea) {
            return $this->wyswietlPoleTextarea($pole);
        }
        return 'XXXXXXXXXXXXXXXXX';
    }
    
    protected function wyswietlPoleHidden(Core_Form_FieldHidden $pole) {
        return '<input type="hidden" name="' . $pole->name . '" value="' . $pole->value . '" />';
    }
    
    protected function wyswietlPoleText(Core_Form_FieldText $pole) {
        return '<input type="text" name="' . $pole->name . '" value="' . $pole->value . '" />';
    }
    
    protected function wyswietlPolePassword(Core_Form_FieldPassword $pole) {
        return '<input type="password" name="' . $pole->name . '" value="' . $pole->value . '" />';
    }
    
    protected function wyswietlPoleCheckbox(Core_Form_FieldCheckbox $pole) {
        $html = '';
        $html .= '<input type="checkbox" name="' . $pole->name . '" value="' . $pole->value . '"';
        if($pole->checked) {
            $html .= ' checked="checked"';
        }
        $html .= ' />';
        return $html;
    }
    
    protected function wyswietlPoleSelect(Core_Form_FieldSelect $pole) {
        $html = '';
        $html .= '<select name="' . $pole->name . '">';
        $opcje = $pole->getOptions();
        foreach($opcje as $opcja) {
            $html .= '<option value="' . $opcja['value'] . '">' . $opcja['name'] . '</option>';
        }
        $html .= '</select>';
        return $html;
    }
	
    protected function wyswietlPoleTextarea(Core_Form_FieldTextarea $pole) {
        $html = '';
        $html .= '<textarea name="' . $pole->name . '">' . $pole->value . '</textarea>';
        return $html;
    }
}
