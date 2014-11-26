<?php

abstract class FormValidation
{
	protected $message = "Input provided is invalid";


	public function __construct($message = null)
	{
		if (!is_null($message))
		{
			$this->message = $message;
		}
	}


	public function get_message()
	{
		return $this->message;
	}


	public function evaluate($value)
	{
		$failed = 0;
		$subjects = !is_array($value) ? array($value) : $value;

		foreach ($subjects as $subject)
		{
			if (!$this->evaluate_subject($subject))
			{
				++$failed;
			}
		}

		return $failed == 0 ? true : false;
	}


	public abstract function evaluate_subject($subject);
}

?>