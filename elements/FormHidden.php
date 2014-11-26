<?php

class FormHidden extends FormField
{
	public function __construct(array $attributes = array(), $validation = array())
	{
		$attributes = array_merge(array(
			"type" => "hidden",
			"class" => "hidden",
		), $attributes);

		parent::__construct("input", $attributes, null, $validation);
	}
}

?>