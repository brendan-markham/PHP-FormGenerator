<?php

class FormValidationNumeric extends FormValidation
{
	protected $message = "Please enter a number";


	public function evaluate_subject($subject)
	{
		$result = true;

		// An empty field is still considered numeric.
		if (strlen($subject) > 0)
		{
			if (!is_numeric($subject))
			{
				$result = false;
			}
		}

		return $result;
	}
}

?>