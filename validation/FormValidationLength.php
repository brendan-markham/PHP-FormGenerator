<?php

class FormValidationLength extends FormValidation
{
	private $length = 0;
	private $limit = null;


	public function __construct($length, $limit = null, $message = null)
	{
		parent::__construct($message);

		$message_suffix = "";

		$this->length = abs($length);
		if ($limit)
		{
			$this->limit = abs($limit);
			$message_suffix = " and maximum of {$this->limit}";
		}

		if (is_null($message))
		{
			$this->message = "A minimum of {$this->length}{$message_suffix} characters is required";
		}
	}


	public function evaluate_subject($subject)
	{
		$result = false;
		$value_length = strlen($subject);

		if ($value_length >= $this->length)
		{
			if ($this->limit)
			{
				if ($value_length <= $this->limit)
				{
					$result = true;
				}
			}
			else
			{
				$result = true;
			}
		}

		return $result;
	}
}

?>