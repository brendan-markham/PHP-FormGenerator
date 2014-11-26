<?php

/**
 * Note: Removing any keys that came with this component will cause errors!
 */
return array(
	// Force browser to navigate to form after submit.
	"anchor_action" => false,

	// Add tabindex attribute to every input by default.
	"auto_tabindex" => true,

	// Add all input names inside array with key of form id.
	"nested_fields" => true,

	// Treat input values before use.
	"stripslashes" => false,

	// Fieldset row wrapper HTML element (use 'NULL' if unwanted).
	"fieldset_row_wrapper" => new HTMLElement("span", array("class" => "wrapper")),
);