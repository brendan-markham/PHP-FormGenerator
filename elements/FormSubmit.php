<?php

class FormSubmit extends FormInput
{
	public function __construct(array $attributes = array())
	{
		$attributes = array_merge(array(
			"type" => "submit",
			"class" => "button submit",
		), $attributes);

		parent::__construct($attributes);
	}
}

?>