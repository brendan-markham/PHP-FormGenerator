<?php

class FormValidationSpam extends FormValidation
{
	const NEEDLE = "http";

	protected $message = "Please remove links from your message";


	public function evaluate_subject($subject)
	{
		$result = false;

		if (stripos($subject, self::NEEDLE) !== false)
		{
			$result = true;
		}

		return $result;
	}
}

?>