<?php

namespace tjpx\field;

use tjpx\helper\Object;

/**
 * @link https://github.com/2rajpx/php-field/
 * @license https://github.com/2rajpx/php-field/blob/master/LICENSE
 */
/**
 * Field Class
 * Used to create form field
 * Field provides concrete implementation for Form Elements
 *
 * @author Tooraj Khatibi <2rajpx@gmail.com>
 * @link https://github.com/2rajpx/
 */
abstract class Field extends Object
{

    /**
     * Field name
     * It is used for field name, id, and label invoking
     * 
     * @var string $name Holds the name of the field
     */
    public $name;

    /**
     * Field label
     * 
     * @var string $label Holds the label of the field
     */
    public $label;

    /**
     * The hint of the field
     * 
     * @var string $hint Holds the hint of the field
     */
    public $hint;

    /**
     * The value of the element
     * 
     * @var string $value Holds the value of the element
     */
    public $value;

    /**
     * Field rendering closure
     * 
     * @var Closure $render Holds the callback to render field
     */
    public $render;

    /**
     * Error rendering closure
     * 
     * @var Closure $renderError Holds the callback to render the error
     */
    public $renderError;

    /**
     * The attributes of the field
     * 
     * @var array|string $attributes Holds the attributes of the field
     */
    public $attributes = [];

    /**
     * Html tags that are valid to save in database
     * 
     * @var array $tags Holds the valid html tags
     */
    public $tags = [];

    /**
     * The rules that reun order by priority
     * 
     * @var array $rules Holds the rules of the field
     */
    public $rules = [];

    /**
     * The errors
     * Made by validator callbacks
     * 
     * @var string $errors Holds the errors pushed by validators
     */
    public $errors;

    /**
     * Prepares the properties to render the field
     *
     * @abstract Use object to prepare properties
     *
     * @category Rendering
     */
    abstract protected function prepare();

    /**
     * Returns rendered string based on self template
     *
     * @abstract Use object properties to make output string code for the field
     *
     * @category Rendering
     *
     * @return Rendered string of the field
     */
    abstract protected function render();

    /**
     * Get array and make attributes string
     *
     * @example
     * ['class'=>'testClass', 'name'=>'foo' 'value'=>'bar', "id='exampleId'"]
     * class='testClass' name='foo' value='bar' id='exampleId'
     *
     * @category Rendering
     * 
     * @param array|object html attributes
     *
     * @return string html attributes
     */
    public static function array2attrs($assoc){
        $attrs = [];
        foreach ((array) $assoc as $key => $value) {
            $attrs[] = is_string($key)
                ? "$key='$value'"
                : trim($value);
        }
        return implode(' ', $attrs);
    }

    /**
     * Prepare some config and render element
     * 
     * @uses self::prepareAttributes() to make html code related to the attributes
     * @uses self::array2attrs() to make html code related to the attributes
     * 
     * @category Rendering
     * 
     * @return string Rendered element
     */
    public function __tostring() {
        // Prepare field label
        if (!$this->label)
            $this->label = $this->name;
        // Prepare errors
        $errors = [];
        foreach ((array) $this->errors as $error) {
            // Get Html code by $error
            $errors[] = $this->renderError($error);
        }
        // Implode errors
        $this->errors = implode('', $errors);
        // Prepare the object for rendering
        $this->prepare();
        // Make html code related to the attributes
        $this->attributes = static::array2attrs($this->attributes);
        // Return rendered field
        return $this->renderField();
    }

    /**
     * Error rendering
     *
     * @see $renderError You can set a callback for this property to customize error rendering
     * 
     * @category Rendering
     *
     * @param $error The error
     *
     * @return Returns rendered error
     * 
     * @throws Exception if renderError is set but it's not a callback
     * @throws Exception if renderError callback doesn't return a string
     */
    protected function renderError($error){
        // Get renderError callback
        $cb = $this->renderError;
        // If it's not set
        if (!$cb)
            // Run default render and return it
            return "<p style='color:red'>$error</p>";
        // If renderError is not a callback
        if (!$cb instanceof \Closure)
            // Throw exception
            throw new \Exception("The renderError field must be a callback", 1);
        // Run callback
        $rendered = $cb($error);
        // If result is valid
        if (is_string($rendered))
            // Return rendered error
            return $rendered;
        // Throw exception if result is invalid
        throw new \Exception("You must return a string in your callbcak", 1);
    }

    /**
     * Error rendering
     *
     * @uses subClass::render() if self::$render is not set
     *
     * @see self::$render You can set a callback for this property to customize
     * field rendering if you dont't want use subClass::render()
     * 
     * @category Rendering
     *
     * @return Returns rendered field
     *
     * @throws Exception if $render property is set but it's not a callback
     * @throws Exception if $render property callback doesn't return a string
     */
    protected function renderField(){
        // Get render callback
        $cb = $this->render;
        // If it's not set
        if (!$cb)
            // Run default render and return it
            return $this->render();
        // If render is not a callback
        if (!$cb instanceof \Closure)
            // Throw exception
            throw new \Exception("The ".get_class($this)."::$render must be a callback", 1);
        // Run callback
        $rendered = $cb($this);
        // If result is valid
        if (is_string($rendered))
            // Return rendered field
            return $rendered;
        // Throw exception if result is invalid
        throw new \Exception("You must return a string in ".get_class($this)."::$render callbcak", 1);
    }

    /**
     * Run the rule of the rules
     * Each rule can push error to the field or change the value of the field
     * 
     * @category Validation
     *
     * @return boolean The result of validation
     */
    public function validate() {
        // Deny if rules array is empty
        if (empty($this->rules))
            return true;
        // If rules are invalid
        if (!is_array($this->rules))
            throw new \Exception("The rules must be an array of the validator|sanitize rules", 1);
        // Loop the rules
        foreach ($this->rules as $rule) {
            // Run rule
            $rule instanceof \Closure
                ? $rule($this)
                : call_user_func($rule, $this);
        }
        // Check the field is valid
        return !$this->hasError();
    }

    /**
     * Check the field has error
     * 
     * @category Validation
     *
     * @return boolean Does the field have any errors
     */
    public function hasError() {
        return !empty($this->errors);
    }

    /**
     * Add error to the field
     * 
     * @category Validation
     * 
     * @param string $error The message
     */
    public function addError($error) {
        $this->errors[] = $error;
    }

}
