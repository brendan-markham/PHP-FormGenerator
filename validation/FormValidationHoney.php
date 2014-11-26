<?php

class FormValidationHoney extends FormValidation
{
	protected $message = "This field must be left blank";


	public function evaluate_subject($subject)
	{
		return strlen($subject) < 1;
	}
}

?>