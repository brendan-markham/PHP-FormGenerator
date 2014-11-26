<?php

class FormField extends HTMLElement
{
	protected $validation = array();
	protected $errors = array();

	private static $instances = 0;


	public function __construct($name, array $attributes = array(), $content = null, $validation = array())
	{
		$id = self::generate_id();
		$attributes = array_merge(array(
			"id" => $id,
			"name" => $id,
		), $attributes);

		parent::__construct($name, $attributes, $content);

		$this->validation = is_array($validation) ? $validation : array($validation);

		// If required attribute has been set, automatically add server-side
		// 'required' validation.
		if ($this->get_attribute("required"))
		{
			$add_validation = true;
			foreach ($this->validation as $v)
			{
				if ($v instanceof FormValidationRequired)
				{
					$add_validation = false;
					break;
				}
			}

			if ($add_validation)
			{
				$this->validation[] = new FormValidationRequired();
			}
		}
	}


	public function get_id()
	{
		return $this->attributes["id"];
	}


	public function get_name()
	{
		return $this->attributes["name"];
	}


	public function get_value()
	{
		$value = null;

		if (isset($this->attributes["value"]))
		{
			$value = $this->attributes["value"];
		}

		return $value;
	}


	public function set_name($name)
	{
		$this->attributes["name"] = $name;
	}


	public function set_value($value)
	{
		$this->attributes["value"] = $value;
	}


	public function set_submitted($value)
	{}


	public function validate()
	{
		$errors = array();

		foreach ($this->validation as $v)
		{
			if (!$v->evaluate($this->get_value()))
			{
				$errors[] = new HTMLElement("span", array("class" => "error"), $v->get_message());
			}
		}

		return $this->errors = $errors;
	}


	public function is_valid()
	{
		return empty($this->errors) ? true : false;
	}


	public function generate($tabs = 0)
	{
		$errors = "";
		foreach ($this->errors as $error)
		{
			$errors .= $error->generate($tabs);
		}

		return parent::generate($tabs).$errors;
	}


	private static function generate_id()
	{
		return strtolower(__CLASS__)."-".++self::$instances;
	}


	public static function get_instances()
	{
		return self::$instances;
	}
}

?>