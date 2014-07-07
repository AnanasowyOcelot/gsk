<?php

class Core_CMS_Module_ViewForm extends Core_Form_View
{

	/**
	 * @return html
	 */
	public function formHeader() {
		$html = '';
		$html .= parent::formHeader();
		$html .= '<div class="formularz">';
		return $html;
	}

	/**
	 * @return html
	 */
	public function formFooter() {
		$html = '';
		$html .= '</div>';
		$html .= parent::formFooter();
		return $html;
	}

	/**
	 * wyswietla wiersz (label, pole i pozostale elementy)
	 *
	 * @param string $fieldName
	 * @return html
	 */
	public function wiersz($fieldName) {
		$pole = $this->form->getField($fieldName);

		if($pole instanceof Core_Form_FieldHidden) {
			$html = $this->pole($fieldName);
		} else {
			$html = '
			<div class="wiersz">
				<label>' . $this->label($fieldName) . '' . $this->required($fieldName) . ':</label>
				<div class="fieldSet">
					<div class="field">
						<div class="fieldWrapper">' . $this->pole($fieldName) . '</div>
					</div>
				</div>
			</div>';
		}
		return $html;
	}

};
