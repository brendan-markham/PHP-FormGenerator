<?php

class FormControl
{
	protected $label;
	protected $field;
	protected $help;


	public function __construct(FormField $field, FormLabel $label = null, HTMLElement $help = null)
	{
		// Add 'for' attribute to label.
		if (!is_null($label))
		{
			$label_attributes = array_merge(array(
				"for" => $field->get_id()
			), $label->get_attributes());
			$label->set_attributes($label_attributes);
		}

		$this->label = $label;
		$this->field = $field;
		$this->help = $help;
	}


	public function generate($tabs = 0)
	{
		$html = "";

		if (!is_null($this->label))
		{
			$html .= $this->label->generate($tabs);
		}
		$html .= $this->field->generate($tabs);
		if (!is_null($this->help))
		{
			$html .= $this->help->generate($tabs);
		}

		return $html;
	}


	public function __toString()
	{
		return $this->generate();
	}
}

?>