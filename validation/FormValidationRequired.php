<?php

class FormValidationRequired extends FormValidation
{
	protected $message = "Please enter something and try again";


	public function evaluate_subject($subject)
	{
		$result = true;
		$value = str_replace(" ", "", trim($subject));

		if (strlen($value) <= 0)
		{
			$result = false;
		}

		return $result;
	}
}

?>