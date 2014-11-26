<?php

class HTMLElement
{
	protected $name;
	protected $attributes = array();
	protected $content;
	protected $text_only;


	public function __construct($name, array $attributes = array(), $content = null, $text_only = null)
	{
		$this->name = $name;
		$this->attributes = $attributes;
		$this->content = $content;
		$this->text_only = is_null($text_only) ? (is_string($content) ? true : false) : $text_only;
	}


	public function get_name()
	{
		return $this->name;
	}


	public function get_attributes()
	{
		return $this->attributes;
	}


	public function get_attribute($name)
	{
		$value = isset($this->attributes[$name]) ? $this->attributes[$name] : null;

		if (is_null($value))
		{
			$attr_key = array_search(strval($name), $this->attributes, true);
			if ($attr_key !== false)
			{
				$value = $this->attributes[$attr_key];
			}
		}

		return $value;
	}


	public function get_content()
	{
		return $this->content;
	}


	public function set_attributes(array $attributes)
	{
		$this->attributes = $attributes;
	}


	public function set_attribute($key_or_value, $value = null)
	{
		if (is_null($value))
		{
			$this->attributes[] = $key_or_value;
		}
		else
		{
			$this->attributes[$key_or_value] = $value;
		}
	}


	public function set_content($content)
	{
		$this->content = $content;
	}


	public function generate($tabs = 0)
	{
		return self::generate_tabs($tabs)."<".rtrim("{$this->name} {$this->get_inline_attributes()}").(is_null($this->content) ? " />" : ">".($this->text_only ? $this->content : PHP_EOL.$this->content.self::generate_tabs($tabs))."</{$this->name}>").PHP_EOL;
	}


	protected function get_inline_attributes()
	{
		$string = "";

		foreach ($this->attributes as $k => $v)
		{
			if (is_int($k))
			{
				$k = $v;
			}
			$string .= "{$k}=\"".htmlspecialchars($v)."\" ";
		}

		return rtrim($string);
	}


	public static function generate_tabs($tabs)
	{
		return str_repeat("\t", $tabs);
	}


	public function __toString()
	{
		return $this->generate();
	}
}

?>