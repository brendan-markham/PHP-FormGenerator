<?php

class FormTel extends FormField
{
	/**
	 * @todo Validate telephone number.
	 */
	public function __construct(array $attributes = array(), $validation = array())
	{
		$attributes = array_merge(array(
			"type" => "tel",
			"class" => "text tel",
		), $attributes);

		parent::__construct("input", $attributes, null, $validation);
	}
}

?>