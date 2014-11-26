<?php

class FormLabel extends HTMLElement
{
	public function __construct($content, array $attributes = array(), $html = false)
	{
		parent::__construct("label", $attributes, $html ? $content : htmlspecialchars($content));
	}
}

?>