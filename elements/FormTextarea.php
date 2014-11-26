<?php

class FormTextarea extends FormField
{
	public function __construct($attributes, $content = "", $validation = array())
	{
		$attributes = array_merge(array(
			"class" => "text textarea",
			"cols" => 30,
			"rows" => 5
		), $attributes);

		// Force text-only element.
		$content = self::format_value($content);

		parent::__construct("textarea", $attributes, $content, $validation);
	}


	public function get_value()
	{
		return strlen($this->content) == 0 ? null : $this->content;
	}


	public function set_value($value)
	{
		$this->content = self::format_value($value);
	}


	private static function format_value($value)
	{
		return is_null($value) ? "" : (string)$value;
	}
}

?>