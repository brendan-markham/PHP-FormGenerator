<?php

class FormEmail extends FormInput
{
	public function __construct(array $attributes = array(), $validation = array())
	{
		$attributes = array_merge(array(
			"type" => "email",
			"class" => "text email",
		), $attributes);

		if (empty($validation))
		{
			$validation = new FormValidationEmail();
		}

		parent::__construct($attributes, $validation);
	}
}

?>