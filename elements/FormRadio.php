<?php

class FormRadio extends FormInput
{
	public function __construct(array $attributes = array(), $validation = array())
	{
		$attributes = array_merge(array(
			"type" => "radio",
			"class" => "radio",
			"value" => 1
		), $attributes);

		parent::__construct($attributes, $validation);
	}


	public function set_submitted($value)
	{
		$value = is_array($value) ? $value : array($value);

		if (in_array(strval($this->get_value()), $value))
		{
			$this->attributes["checked"] = "checked";
		}
	}
}

?>