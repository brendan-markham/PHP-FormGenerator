<?php

spl_autoload_register(function ($name)
{
	if (is_file($element_file = "elements/{$name}.php"))
	{
		include $element_file;
	}
	else if (is_file($validation_file = "validation/{$name}.php"))
	{
		include $validation_file;
	}
});

/*function __autoload($name)
{
	if (is_file($element_file = "elements/{$name}.php"))
	{
		include $element_file;
	}
	else if (is_file($validation_file = "validation/{$name}.php"))
	{
		include $validation_file;
	}
}*/

/* ========================================================================== */

// This class contains helper methods that will automate some of the mundane
// tasks associated with all other form objects.
include "FormGenerator.php";

// Let's kick things off.
$fg = new FormGenerator();

// Suppress errors?
//$fg->show_errors = false;

// A 'row' is an HTML element to wrap labels and inputs inside (default: <div>).
$fg	->row()

// A 'control' is a combination of label and input (semantic forms are
// incredibly sexy to developers).
		->control(new FormInput(array("name" => "first_name", "required"), new FormValidationRequired("We need at least one name to determine who you are")), "* First name")
	->row()
		->control(new FormInput(array("name" => "last_name"), new FormValidationRegex('/^[a-z]/i', "Last name must begin with a letter")), "Last name")
	->row()
		->control(new FormEmail(array("name" => "email"), array(new FormValidationRequired(), new FormValidationEmail())), "* Email", "A password will be sent to this address")
		//->control(new FormEmail(array("name" => "email", "required")), "* Email", "A password will be sent to this address") //<* This line will do the exact same thing as the one above.

// A 'fieldset' should be used for more than one 'control' belonging to one
// question or one field name (in this case: gender).
	->fieldset("Gender")
		->row()
			->control(new FormRadio(array("name" => "gender", "value" => "M")), "Male")
		->row()
			->control(new FormRadio(array("name" => "gender", "value" => "F")), "Female")

// No need to end the 'fieldset' above - starting a new 'fieldset' will
// automatically close the one prior.
	->fieldset("Interests")
		->row()
			->control(new FormCheckbox(array("name" => "interests[]", "value" => "Free Candy", "multiple")), "Free Candy")
		->row()
			->control(new FormCheckbox(array("name" => "interests[]", "value" => "Shady Vans", "multiple")), "Shady Vans")
		->row()
			->control(new FormCheckbox(array("name" => "interests[]", "value" => "Elderly People", "multiple")), "Elderly People")

// When you wish to call a new 'row' outside of an opened 'fieldset', be sure to
// end the 'fieldset' first.
	->end_fieldset()

// HTML elements can be stuffed into a 'row' at any point in the form.
	->row("div", array("class" => "row"), new HTMLElement("h1", array("class" => "wicked", "style" => "text-align: center"), "This is an awesome row right here"))
	->row()
		->control(new FormSelect(array("0-9" => "0-9", "10-19" => "10-19", "20-29", "30-39", "Old enough to have crow's feet", "L1" => array("L2" => "Level 2", array("L3" => "Level 3"))), null, array("name" => "age")), "Age Group")
	->row()
		->control(new FormFile(array("name" => "photo")), "Avatar Photo")
	->row()
		->control(new FormCheckbox(array("name" => "subscribe")), "Subscribe")

// You can specify 'row' attributes for custom styling (e.g. the submit row).
	->row("div", array("class" => "form-group submit"))
		->control(new FormSubmit(array("value" => "Go!")))
;

// Use the 'is_valid()' method to display a success message or prevent the form
// from being displayed. Calling 'is_valid()' will also add any validation
// errors into the form output.
$form_completed = false;
if ($fg->generate($tabs = 1)->is_valid() === true)
{
	// Place any form success logic here (email, redirect, etc).
	$form_completed = true;
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	
	<title>Form Generator Example</title>
	
	<link rel="stylesheet" href="form.css">
	
	<style type="text/css">
	<!--
	body {
		width: 640px;
		margin: 0 auto;
	}
	-->
	</style>
</head>
<body>
<?php

// Use 'echo $fg' to output form HTML. If an exception occurs, an error message
// will be shown and the script allowed to complete.
echo $fg;

?>
	<hr />
<?php if ($form_completed) : ?>
	<pre>
<?php

// All form data can be retrieved via an associative array using 'get_data()'.
print_r($fg->get_data());

?>
	</pre>
<?php endif ?>
</body>
</html>
