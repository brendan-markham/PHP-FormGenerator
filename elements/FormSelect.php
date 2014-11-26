<?php

class FormSelect extends FormField
{
	protected $options = array();
	protected $selected = array();
	protected $option_prefix = "&nbsp;&nbsp;";


	public function __construct(array $options = array(), $selected = array(), array $attributes = array(), array $validation = array())
	{
		$this->options = $options;
		$this->set_selected($selected);

		parent::__construct("select", $attributes, null, $validation);
	}


	public function set_value($value)
	{
		$this->set_selected($value);
	}


	public function set_options(array $options)
	{
		$this->options = $options;
	}


	public function set_selected($selected)
	{
		$this->selected = is_array($selected) ? $selected : array($selected);
	}


	public function get_value()
	{
		$selected = $this->selected;

		return array_shift($selected);
	}


	public function generate($tabs = 0)
	{
		$this->content = $this->generate_options($this->options, $tabs + 1);

		return parent::generate($tabs);
	}


	private function generate_options($options, $tabs, $level = 0)
	{
		$html = "";

		foreach ($options as $value => $content)
		{
			if (is_array($content))
			{
				$html .= $this->generate_options($content, $tabs, $level + 1);
			}
			else
			{
				$option_attributes = array("value" => $value, "class" => "level-{$level}");
				$selected_key = array_search(strval($value), $this->selected, true);
				if ($selected_key !== false && !is_null($this->selected[$selected_key]))
				{
					$option_attributes[] = "selected";
				}

				$option = new HTMLElement("option", $option_attributes, str_repeat($this->option_prefix, $level).$content);
				$html .= $option->generate($tabs);
			}
		}

		return $html;
	}
}

?>