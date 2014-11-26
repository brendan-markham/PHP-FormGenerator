<?php

class FormFieldset extends HTMLElement
{
	protected $legend;
	protected $rows;


	public function __construct(array $rows, FormLegend $legend = null, array $attributes = array())
	{
		$this->legend = $legend;
		$this->rows = $rows;

		parent::__construct("fieldset", $attributes);
	}


	public function generate($tabs = 0)
	{
		$html = "";

		if (!is_null($this->legend))
		{
			$html .= $this->legend->generate($tabs + 1);
		}

		foreach ($this->rows as $row)
		{
			$html .= $row->generate($tabs + 1);
		}

		$this->content = $html;

		return parent::generate($tabs);
	}
}

?>