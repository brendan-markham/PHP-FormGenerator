<?php

class FormPassword extends FormInput
{
	public function __construct(array $attributes = array(), $validation = array())
	{
		$attributes = array_merge(array(
			"type" => "password",
			"class" => "text password",
		), $attributes);

		parent::__construct($attributes, $validation);
	}
}

?>