<?php

class FormRow
{
	protected $parent;
	protected $controls = array();


	public function __construct(array $controls, HTMLElement $parent = null, HTMLElement $wrapper = null)
	{
		$this->parent = $parent;
		$this->controls = $controls;
		$this->wrapper = $wrapper;
	}


	public function generate($tabs = 0)
	{
		$html = "";

		foreach ($this->controls as $control)
		{
			if (!is_null($this->wrapper))
			{
				$this->wrapper->set_content($control->generate($tabs + 2));
				$html .= $this->wrapper->generate($tabs + 1);
			}
			else
			{
				$html .= $control->generate($tabs + 1);
			}
		}

		if (!is_null($this->parent))
		{
			$this->parent->set_content($this->parent->get_content().$html);
			$html = $this->parent->generate($tabs);
		}

		return $html;
	}


	public function __toString()
	{
		return $this->generate();
	}
}

?>