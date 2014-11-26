<?php

class FormValidationRegex extends FormValidation
{
	protected $message = "Please check the format of this value";

	private $pattern;


	public function __construct($pattern, $message = null)
	{
		parent::__construct($message);

		$this->pattern = $pattern;
	}


	public function evaluate_subject($subject)
	{
		return preg_match($this->pattern, $subject) == 1;
	}
}

?>