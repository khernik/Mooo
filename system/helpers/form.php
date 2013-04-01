<?php

namespace Mooo\System\Form;

use \Mooo\System\Html\Html as HTML;

/**
 * Class responsible for displaying FORM tags.
 * 
 * This is very simple helper. More advanced can be found
 * in modules folder.
 * 
 * @see MODPATH / kform
 * @author khernik
 */
class Form {
    
    /**
     * Opens <form> tag
     * 
     * 		<form name="foo" action="some_file.php" method="get">
     * 
     * @param string $action
     * @param string $id
     * @param array $attributes
     * @return string
     */
    public static function open($action, $id = '', $attributes = [], $enctype = NULL)
    {
    	HTML::$attributes['action'] = \Mooo\System\Url\Url::site($action);
    	HTML::$attributes['id'] = $id;
    	HTML::$attributes['method'] = (isset($attributes['method'])) ? $attributes['method'] : 'post';
    	
    	// Setting additional HTML attributes
    	foreach($attributes as $key => $value)
    	{
    		HTML::$attributes[$key] = $value;
    	}
    	
    	// Set enctype attribute
    	if($enctype !== NULL)
    	{
    		HTML::$attributes['enctype'] = 'multipart/form-data';
    	}
    	
    	return '<form ' . HTML::compile() . '>'.PHP_EOL;
    }
    
    /**
     * Opens <input type="text"> tag
     * 
     * 		<input type="text" name="foo" value="bar">
     * 
     * @param string $name
     * @param string $value
     * @param array $attributes
     * @return string
     */
    public static function input($name, $value = '', $attributes = [])
    {
    	HTML::$attributes['name'] = $name;
    	HTML::$attributes['value'] = $value;
    	
    	// Setting additional HTML attributes
    	foreach($attributes as $key => $value)
    	{
    		HTML::$attributes[$key] = $value;
    	}
    	 
    	return '<input type="text" ' . HTML::compile() . '>'.PHP_EOL;
    }
    
    /**
     * Opens <input type="password"> tag
     *
     *		<input type="password" name="foo" value="bar">
     *
     * @param string $name
     * @param string $value
     * @param array $attributes
     * @return string
     */
    public static function password($name, $value = '', $attributes = [])
    {
    	HTML::$attributes['name'] = $name;
    	HTML::$attributes['value'] = $value;
    	 
    	// Setting additional HTML attributes
    	foreach($attributes as $key => $value)
    	{
    		HTML::$attributes[$key] = $value;
    	}
    
    	return '<input type="password" ' . HTML::compile() . '>'.PHP_EOL;
    }
    
    /**
	 * Opens <select> options' list
	 * 
	 * 		<select name="foo">
	 * 			<option value="aaa">bbb</option>
	 * 			<option value="ccc">ddd</option>
	 * 		</select>
	 * 
	 * @param string $name
	 * @param array $options
	 * @param array $attributes
	 * @return string
     */
    public static function select($name, $options = [], $attributes = [])
    {
    	HTML::$attributes['name'] = $name;
    	
    	// Setting option tags
    	$options_compiled = '';    	
    	foreach($options as $key => $value)
    	{
    		$options_compiled .= '<option id="' . $key . '">' . $value . '</option>\n'.EOL;
    	}
    		
    	// Setting additional HTML attributes
    	foreach($attributes as $key => $value)
    	{
    		HTML::$attributes[$key] = $value;
    	}
    	
    	return '<select ' . HTML::compile() . '>' . $options_compiled . '</select>'.PHP_EOL;
    }
    
    /**
	 * Opens <textarea></textarea> tags
	 * 
	 * 		<textarea name="foo">bar</textarea>
	 * 
	 * @param string $name
	 * @param string $value
	 * @param array $attributes
	 * @return string
     */
    public static function textarea($name, $value = '', $attributes = [])
    {
    	HTML::$attributes['name'] = $name;
    	HTML::$attributes['value'] = $value;
    	
    	// Setting additional HTML attributes
    	foreach($attributes as $key => $value)
    	{
    		HTML::$attributes[$key] = $value;
    	}
    	
    	return '<textarea ' . HTML::compile() . '>' . $value . '</textarea>'.PHP_EOL;
    }
    
    /**
	 * Opens <checkbox> tag
	 * 
	 * 		<input type="checkbox" name="foo" value="bar">
	 * 
	 * @param string $name
	 * @param string $value
	 * @param array $attributes
	 * @return string
     */
    public static function checkbox($name, $value, $attributes = [])
    {
    	HTML::$attributes['name'] = $name;
    	HTML::$attributes['value'] = $value;
    	
    	// Setting additional HTML attributes
    	foreach($attributes as $key => $value)
    	{
    		HTML::$attributes[$key] = $value;
    	}
    	
    	return '<input type="checkbox" ' . HTML::compile() . '>'.PHP_EOL;
    }
    
    /**
     * Opens <radio> tag
     *
     *		<input type="radio" name="foo" value="bar">
     *
     * @param string $name
     * @param string $value
     * @param array $attributes
     * @return string
     */
    public static function radio($name, $value, $attributes = [])
    {
    	HTML::$attributes['name'] = $name;
    	HTML::$attributes['value'] = $value;
    	 
    	// Setting additional HTML attributes
    	foreach($attributes as $key => $value)
    	{
    		HTML::$attributes[$key] = $value;
    	}
    	 
    	return '<input type="radio" ' . HTML::compile() . '>'.PHP_EOL;
    }
    
    /**
     * Opens <button> tag
     * 
     * 		<button name="foo" value="bar">
     * 
     * @param string $name
     * @param array $attributes
     * @return string
     */
    public static function button($name, $value = 'button', $attributes = [])
    {
    	HTML::$attributes['name'] = $name;
    	HTML::$attributes['value'] = $value;
    	
    	// Setting additional HTML attributes
    	foreach($attributes as $key => $value)
    	{
    		HTML::$attributes[$key] = $value;
    	}
    	 
    	return '<input type="button" ' . HTML::compile() . '>'.PHP_EOL;
    }
    
    /**
     * Opens <submit> tag
     *
     *		<input type="submit" name="submit" value="submit">
     *
     * @param string $name
     * @param string $value
     * @param array $attributes
     * @return string
     */
    public static function submit($name, $value = 'submit', $attributes = [])
    {
    	HTML::$attributes['name'] = $name;
    	HTML::$attributes['value'] = $value;
    	 
    	// Setting additional HTML attributes
    	foreach($attributes as $key => $value)
    	{
    		HTML::$attributes[$key] = $value;
    	}
    
    	return '<input type="submit" ' . HTML::compile() . '>'.PHP_EOL;
    }
    
    /**
	 * Opens <hidden> tag
	 * 
	 * 		<input type="hidden" name="foo" value="bar">
	 * 
	 * @param string $name
	 * @param string $value
	 * @return string
     */
    public static function hidden($name, $value)
    {
    	HTML::$attributes['name'] = $name;
    	HTML::$attributes['value'] = $value;
    	
    	return '<input type="hidden" ' . HTML::compile() . '>'.PHP_EOL;
    }
    
    /**
	 * Opens <label></label> tag
	 * 
	 * 		<label for="foo">bar</label>
	 * 
	 * @param string $for
	 * @param string $test
	 * @return string
     */
    public static function label($for, $text)
    {
    	HTML::$attributes['for'] = $for;
    	    	 
    	return '<label '. HTML::compile() . '>' . $text . '</label>'.PHP_EOL;
    }
    
    /**
     * Closes <form> tag
     * 
     * @return string
     */
    public static function close()
    {
    	return '</form>';
    }
	
} // End \Mooo\System\Form\Form
