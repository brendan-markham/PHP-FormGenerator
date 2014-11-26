<?php

class FormLegend extends HTMLElement
{
	public function __construct($content, array $attributes = array())
	{
		parent::__construct("legend", $attributes, $content);
	}
}

?>