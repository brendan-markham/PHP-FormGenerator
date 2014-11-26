<?php

class FormFile extends FormInput
{
	public function __construct(array $attributes = array(), $validation = array())
	{
		$attributes = array_merge(array(
			"type" => "file",
			"class" => "file",
		), $attributes);

		parent::__construct($attributes, $validation);
	}
}

?>