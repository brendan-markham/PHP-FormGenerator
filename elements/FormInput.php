<?php

class FormInput extends FormField
{
	public function __construct(array $attributes = array(), $validation = array())
	{
		$attributes = array_merge(array(
			"type" => "text",
			"class" => "text",
		), $attributes);

		parent::__construct("input", $attributes, null, $validation);
	}
}

?>