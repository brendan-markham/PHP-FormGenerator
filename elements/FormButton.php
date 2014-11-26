<?php

class FormButton extends FormInput
{
	public function __construct(array $attributes = array())
	{
		$attributes = array_merge(array(
			"type" => "button",
			"class" => "button",
		), $attributes);

		parent::__construct($attributes);
	}
}

?>