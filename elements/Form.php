<?php

class Form extends HTMLElement
{
	private static $instances = 0;


	public function __construct(array $attributes = array())
	{
		$attributes = array_merge(array(
			"id" => self::generate_id(),
			"method" => "post",
		), $attributes);

		parent::__construct("form", $attributes);
	}


	public function get_id()
	{
		return $this->attributes["id"];
	}


	public function get_method()
	{
		return $this->attributes["method"];
	}


	public function get_action()
	{
		return $this->attributes["action"];
	}


	public function set_method($method)
	{
		$this->attributes["method"] = $method;
	}


	public function set_action($action)
	{
		$this->attributes["action"] = $action;
	}


	private static function generate_id()
	{
		return strtolower(__CLASS__)."-".++self::$instances;
	}
}

?>