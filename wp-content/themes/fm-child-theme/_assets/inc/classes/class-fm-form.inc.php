<?php
/**
*
*/
class Fm_form
{

// == Constructor ==========================
	public function __construct()
	{

	} // __construct()

// == Output form tag ======================
	public function form_open($action = '?', $method = 'post', $args = array(), $echo = true)
	{
		extract($args);

		$form = "<form action=\"{$action}\" method=\"{$method}\" accept-charset=\"utf-8\"";
		if ( !empty( $id ) ) $form .= " id=\"{$id}\"";
		if ( !empty( $class ) ) $form .= " class=\"{$class}\"";
		if ( !empty( $style ) ) $form .= " style=\"{$style}\"";
		$form .= ">";

		if ( $echo ) {
			echo $form;
		} else {
			return $form;
		} // if($echo)
	} // form_open()

// == Output upload form tag ===============
	public function form_open_upload($action = '?', $method = 'post', $args = array(), $echo = true)
	{
		extract($args);

		$form = "<form action=\"{$action}\" method=\"{$method}\" accept-charset=\"utf-8\"";
		if ( !empty( $id ) ) $form .= " id=\"{$id}\"";
		if ( !empty( $class ) ) $form .= " class=\"{$class}\"";
		if ( !empty( $style ) ) $form .= " style=\"{$style}\"";
		$form .= ' enctype="multipart/form-data">';

		if ( $echo ) {
			echo $form;
		} else {
			return $form;
		} // if($echo)
	} // form_open_upload()

// == Create a label =======================
	public function label( $for, $label = '', $echo = true)
	{
		// Construct the label
		$label = "<label for=\"{$for}\">{$label}</label>";

		if ( $echo ) {
			echo $label;
		} else {
			return $label;
		} // if($echo)
	} // label($for, $label)

// == Create an input field ================
	public function input( $name, $args = array(), $echo = true )
	{
		// Extract the arguments passed in
		extract($args);

		// Make sure at minimum that name exists
		if (!isset($name)) return false;

		// Input Field
		if ( !isset($type) ) $type = 'text'; // Set text field as default type if nothing is passed in
		$input = '<input '; // Setup the input field
		$input .= "type=\"{$type}\" "; // Set the input type attribute
		$input .= "name=\"{$name}\" "; // Set the input name attribute
		$input .= ( isset($value) ) ? "value=\"{$value}\" " : 'value="" '; // Set the input value attribute
		$input .= (isset($id)) ? "id=\"{$id}\" " : "id=\"{$name}\" "; // Set the input id attribute
		if ( isset($class) ) $input .= "class=\"{$class}\" " ;// Set the input class attribute if supplied
		if ( isset($autocomplete) ) $input .= "autocomplete=\"{$autocomplete}\" " ;// Set the input autocomplete attribute if supplied
		if ( $disabled ) $input .= "disabled=\"disabled\" " ;// Set the input disabled attribute if supplied
		if ( isset($maxlength) ) $input .= "maxlength=\"{$maxlength}\" " ;// Set the input maxlength attribute if supplied
		if ( isset($readonly) ) $input .= "readonly=\"{$readonly}\" " ;// Set the input readonly attribute if supplied
		if ( isset($required) ) $input .= "required=\"{$required}\" " ;// Set the input required attribute if supplied
		if ( isset($size) ) $input .= "size=\"{$size}\" " ;// Set the input size attribute if supplied
		if ( isset($placeholder) ) $input .= "placeholder=\"{$placeholder}\" "; // Set the placeholder value attribute if supplied
		if ( isset($style) ) $input .= "style=\"{$style}\" "; // Set the style value if supplied
		$input .= '>';

		if ( $echo ) {
			echo $input;
		} else {
			return $input;
		} // if($echo)
	} // input($name, $args, $echo)

// == Create a checkbox/radio ==============
	public function radio_checkbox ( $type, $name, $args = array(), $echo = true )
	{
		extract($args);
		if ( !isset($value) ) $value = 1;
		$checkbox = '';
		$checkbox .= "<input type=\"{$type}\" ";
		$checkbox .= "name=\"{$name}\" ";
		$checkbox .= "value=\"{$value}\" ";
		if ( isset($id) )$checkbox .= "id=\"{$id}\" ";
		if ( isset($class) ) $checkbox .= "class=\"{$class}\" ";
		if( $checked ) $checkbox .= 'checked="checked" ';
		if( $disabled )$checkbox .= 'disabled="disabled" ';
		$checkbox .= '>';

		if ( $echo ) {
			echo $checkbox;
		} else {
			return $checkbox;
		}
	} // checkbox($name,$args,$echo)

// == Create upload input ==================
	public function file_upload ( $name, $args = array(), $echo = true )
	{
		extract($args);

		$upload = '';
		$upload .= '<input type="file" ';
		$upload .= "name=\"{$name}\" ";
		if ( $multiple ) $upload .= 'multiple="multiple" ';
		if ( isset($id) ) $upload .= "id=\"{$id}\" ";
		if ( isset($class) ) $upload .= "class=\"{$class}\" ";
		if ( isset($disabled) ) $upload .= "disabled=\"{$disabled}\" ";
		$upload .= '>';

		if ( $echo ) {
			echo $upload;
		} else {
			return $upload;
		}

	} // file_upload($name,$args,$echo)

// == Create a textarea ====================
	public function textarea ( $name, $args = array(), $echo = true )
	{
		extract($args);

		$textarea = '';
		$textarea .= '<textarea ';
		$textarea .= "name=\"{$name}\" ";
		if ( $disabled ) $textarea .= 'disabled="disabled" ';
		if ( $readonly ) $textarea .= 'readonly="readonly" ';
		if ( isset($maxlength) ) $textarea .= "maxlength=\"{$maxlength}\" ";
		if( isset($placeholder) ) $textarea .= "placeholder=\"{$placeholder}\" ";
		if ( isset($rows) ) $textarea .= "rows=\"{$rows}\" ";
		if ( isset($cols) ) $textarea .= "cols=\"{$cols}\" ";
		if ( isset($style) ) $textarea .= "style=\"{$style}\" ";
		if ( !isset($value) ) $textarea .= '></textarea>';
		if ( isset($value) ) $textarea .= ">{$value}</textarea>";

		if ( $echo ) {
			echo $textarea;
		} else {
			return $textarea;
		}

	} // textarea($name,$args,$echo)

// == Create a submit button ===============
	public function submit ( $name = 'form_submit', $value = 'Submit', $echo = true )
	{
		$submit = "<input type=\"submit\" name=\"{$name}\" value=\"{$value}\">";

		if ( $echo ) {
			echo $submit;
		} else {
			return $submit;
		} // if/else($echo)
	} // submit ($name,$value,$echo)

// == Output form close tag ================
	public function form_close( $echo = true )
	{
		if ( $echo ) {
			echo '</form>';
		} else {
			return '</form>';
		} // if($echo)
	} // form_close($echo)


} // class