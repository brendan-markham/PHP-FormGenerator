<?php

/**
 * @version	0.1.9
 * @author Brendan Markham
 * @copyright 2014
 * @license http://opensource.org/licenses/mit-license.php
 */
class FormGenerator
{
	protected $config = array();

	protected $form;
	protected $form_inner = array();
	protected $generated = false;
	protected $tabs = 0;
	protected $tabindex_offset = 0;

	public $submitted = false;
	public $show_errors = true;
	protected $data = array();
	protected $errors = array();
	protected $valid = false;

	protected $active = array();
	protected $active_reset = array(
		"fieldset" => null,
		"row" => null,
		"rows" => array(),
		"controls" => array()
	);


	/**
	 * @version 0.1.7
	 */
	public function __construct($form_id_or_attributes = null, $form_action = null)
	{
		$this->config = include "config.php";

		// Create baseline attributes for form.
		$form_attributes = array(
			"class" => "generated",
			"enctype" => "multipart/form-data",
		);

		// Merge with user provided attributes.
		if (is_string($form_id_or_attributes))
		{
			$form_attributes["id"] = $form_id_or_attributes;
		}
		else if (is_array($form_id_or_attributes))
		{
			$form_attributes = $form_id_or_attributes;
		}
		if (is_string($form_action))
		{
			$form_attributes["action"] = $form_action;
		}
		else if (!isset($form_attributes["action"]) && isset($_SERVER["PHP_SELF"]))
		{
			$form_attributes["action"] = $_SERVER["PHP_SELF"];
		}

		$this->form = new Form($form_attributes);

		// Append form id to method if required.
		if ($this->config["anchor_action"] && strpos($this->form->get_action(), "#") === false)
		{
			$this->form->set_action($this->form->get_action()."#{$this->form->get_id()}");
		}

		$this->active = $this->active_reset;

		switch (strtolower($this->form->get_method()))
		{
			case "get":
				if ($this->config["nested_fields"])
				{
					$this->data = isset($_GET[$this->form->get_id()]) ? $_GET[$this->form->get_id()] : array();
				}
				else
				{
					$this->data = $_GET;
				}
				break;

			default:
				if ($this->config["nested_fields"])
				{
					$this->data = isset($_POST[$this->form->get_id()]) ? $_POST[$this->form->get_id()] : array();
				}
				else
				{
					$this->data = $_POST;
				}
				break;
		}

		if (!empty($this->data))
		{
			$this->submitted = true;

			if ($this->config["stripslashes"])
			{
				$this->data = self::stripslashes_recursive($this->data);
			}
		}
	}


	/**
	 * Return an associative array of data assigned to this form.
	 *
	 * @param string $key
	 * @return array
	 */
	public function get_data($key = null)
	{
		$data = $this->data;

		if (!is_null($key))
		{
			if (isset($data[$key]))
			{
				$data = $data[$key];
			}
			else
			{
				$data = null;
			}
		}

		return $data;
	}


	/**
	 * Fetch all form errors that have occurred since generation.
	 *
	 * @return array
	 */
	public function get_errors()
	{
		return $this->errors;
	}


	/**
	 * Return boolean flag for whether form has been submitted.
	 *
	 * @since 0.1.3
	 * @return boolean
	 */
	public function is_valid()
	{
		return $this->valid;
	}


	/**
	 * Set one or many values in the form data array.
	 *
	 * @since 0.1.3
	 * @param mixed $data_or_key
	 * @param mixed $value
	 */
	public function set_data($data_or_key = array(), $value = null)
	{
		if (is_array($data_or_key))
		{
			$this->data = $data_or_key;
		}
		else
		{
			$this->data[$data_or_key] = $value;
		}
	}


	/**
	 * The first form element will begin with a tabindex value of n+1.
	 *
	 * @since 0.1.6
	 * @param int $offset
	 */
	public function set_tabindex_offset($offset = 0)
	{
		$this->tabindex_offset = $offset;
	}


	/* ====================================================================== */


	public function fieldset($legend = null, $fieldset_attributes = array())
	{
		if ($this->is_row_active())
		{
			$this->create_row();
		}
		if ($this->is_fieldset_active())
		{
			$this->create_fieldset();
		}

		$this->active["fieldset"] = array(
			"legend" => $legend,
			"attributes" => $fieldset_attributes,
		);

		return $this;
	}


	public function end_fieldset()
	{
		if ($this->is_row_active())
		{
			$this->create_row();
		}
		$this->create_fieldset();

		return $this;
	}


	/**
	 * @version 0.1.3
	 */
	public function row($name = "div", array $attributes = array("class" => "form-group"), $content = null)
	{
		if ($this->is_row_active())
		{
			$this->create_row();
		}

		$this->active["row"] = array(
			"name" => $name,
			"attributes" => $attributes,
			"content" => $content,
		);

		return $this;
	}


	/**
	 * @version 0.1.7
	 */
	public function control(FormField $field, $label = null, $help = null)
	{
		$field_basename = $field->get_name();

		if (is_string($label))
		{
			$label = new FormLabel($label);
		}

		if (is_string($help))
		{
			$help = new HTMLElement("span", array("class" => "help"), $help);
		}

		// Check for multi-dimensional name.
		$first_square_bracket = strpos($field_basename, "[");

		// Keeping things organised is such a hassle...
		if ($this->config["nested_fields"])
		{
			if ($first_square_bracket !== false)
			{
				$field_basename = substr($field_basename, 0, $first_square_bracket)."][".substr($field_basename, $first_square_bracket + 1); //<* Note: Basename has changed!
				$field->set_name("{$this->form->get_id()}[{$field_basename}");
			}
			else
			{
				$field->set_name("{$this->form->get_id()}[{$field_basename}]");
			}
		}

		if ($this->config["auto_tabindex"] && is_null($field->get_attribute("tabindex")))
		{
			$field->set_attribute("tabindex", $this->tabindex_offset + FormField::get_instances());
		}

		// If data has been set then update input values.
		if (!empty($this->data))
		{
			$value = null;

			// Value requires array traversal.
			if ($first_square_bracket !== false)
			{
				$dimensions = explode("[", $field_basename);
				$first_dimension = str_replace("]", "", array_shift($dimensions));
				if (isset($this->data[$first_dimension]))
				{
					$value = $this->data[$first_dimension];
					foreach ($dimensions as $dimension)
					{
						$dimension = str_replace("]", "", $dimension);
						if (strlen($dimension) <= 0)
						{
							break;
						}
						else if (!isset($value[$dimension]))
						{
							$value = null;
							break;
						}
						$value = $value[$dimension];
					}
				}
			}

			// Value is in flat array.
			else if (isset($this->data[$field_basename]))
			{
				$value = $this->data[$field_basename];
			}

			if (!is_null($value))
			{
				// Only set value if one has not already been provided.
				if (is_null($field->get_value()))
				{
					$field->set_value($value);
				}
				else
				{
					$field->set_submitted($value);
				}
			}
		}

		if ($this->submitted && $this->show_errors)
		{
			if ($errors = $field->validate())
			{
				$this->errors[$field->get_id()] = $errors;
				$field->set_attribute("class", ltrim($field->get_attribute("class")." error"));
			}
		}

		$this->active["controls"][] = new FormControl($field, $label, $help);

		return $this;
	}


	private function create_fieldset()
	{
		$legend = null;
		if (is_string($this->active["fieldset"]["legend"]))
		{
			$legend = new FormLegend($this->active["fieldset"]["legend"]);
		}

		$this->form_inner[] = new FormFieldset($this->active["rows"], $legend, $this->active["fieldset"]["attributes"]);

		$this->active = $this->active_reset;
	}


	/**
	 * @version 0.1.8
	 */
	private function create_row()
	{
		$parent = null;
		if (!is_null($this->active["row"]["name"]))
		{
			$parent = new HTMLElement($this->active["row"]["name"], $this->active["row"]["attributes"], $this->active["row"]["content"]);
		}

		$row = new FormRow($this->active["controls"], $parent, $this->is_fieldset_active() ? $this->config["fieldset_row_wrapper"] : null);

		if ($this->is_fieldset_active())
		{
			$this->active["rows"][] = $row;
		}
		else
		{
			$this->form_inner[] = $row;
		}

		$this->active["row"] = null;
		$this->active["controls"] = array();
	}


	private function is_row_active()
	{
		return !is_null($this->active["row"]);
	}


	private function is_fieldset_active()
	{
		return !is_null($this->active["fieldset"]);
	}


	/* ====================================================================== */


	/**
	 * Generate form HTML.
	 *
	 * @version 0.1.4
	 * @param int $tabs			How much whitespace (left) will be used for the opening form tag.
	 * @throws LogicException	When form has already been generated.
	 * @throws LogicException	If no control has been added.
	 */
	public function generate($tabs = 0)
	{
		if ($this->generated)
		{
			throw new LogicException("Form has already been generated");
		}

		$this->tabs = $tabs;

		// Create remaining rows and fieldset (if any).
		if ($this->is_row_active())
		{
			$this->create_row();
		}
		if ($this->is_fieldset_active())
		{
			$this->create_fieldset();
		}

		if (empty($this->form_inner))
		{
			throw new LogicException("You must add at least one row before generating a form");
		}

		$html = "";
		foreach ($this->form_inner as $inner)
		{
			$html .= $inner->generate($tabs + 1);
		}
		$this->form->set_content($html);
		$this->generated = true;

		if ($this->submitted)
		{
			$this->valid = empty($this->errors) ? true : false;
		}

		return $this;
	}


	/**
	 * @version 0.1.4
	 */
	public function __toString()
	{
		$output = null;

		if (!$this->generated)
		{
			try
			{
				$this->generate();
			}
			catch (Exception $e)
			{
				$output = $e->getMessage();
			}
		}

		if (is_null($output))
		{
			$output = $this->form->generate($this->tabs);
		}

		return $output;
	}


	/* ====================================================================== */


	private static function stripslashes_recursive($array)
	{
		foreach ($array as $k => $v)
		{
			if (is_array($v))
			{
				$array[$k] = self::stripslashes_recursive($v);
			}
			else
			{
				$array[$k] = stripslashes($v);
			}
		}

		return $array;
	}
}

?>