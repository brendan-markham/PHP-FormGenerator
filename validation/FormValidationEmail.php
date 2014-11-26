<?php

class FormValidationEmail extends FormValidation
{
	protected $message = "Please enter a valid email address";


	public function evaluate_subject($subject)
	{
		$result = false;

		// If email field is not mandatory, pass validation.
		if ($subject == "")
		{
			$result = true;
		}

		// Best to rely on built-in filters.
		else if (version_compare(PHP_VERSION, "5.3.0") >= 0)
		{
			if (filter_var($subject, FILTER_VALIDATE_EMAIL) !== false)
			{
				$result = true;
			}
		}

		// Otherwise fallback on best regex you can find.
		else
		{
			if (preg_match("/^([a-z0-9\\+_\\-]+)(\\.[a-z0-9\\+_\\-]+)*@([a-z0-9\\-]+\\.)+[a-z]{2,6}$/ix", $subject) > 0)
			{
				$result = true;
			}
		}

		return $result;
	}
}

?>