<?php

namespace 2jpx\field;

/**
 * @link https://github.com/2rajpx/php-field/
 * @license https://github.com/2rajpx/php-field/blob/master/LICENSE
 */
/**
 * Select Class
 * Creates select field
 *
 * @author Tooraj Khatibi <2rajpx@gmail.com>
 * @link https://github.com/2rajpx/
 */
class Select extends Field
{

    /**
     * The options of the list
     * 
     * @var array $options Holds the options of the list
     */
    public $options = [];

    /**
     * use wp selected and return string instead of print it
     * @param integer|string $selectedValue first arg in selected()
     * @param integer|string $optionValue second arg in selected()
     * @return string saved result in buffer
     */
    public static function selected($selectedValue, $optionValue){
        // start buffer
        ob_start();
        // wp selected print
        selected($selectedValue, $optionValue);
        // return buffer
        return ob_get_clean();
    }

    /**
     * generate html code by options array
     * @param array $options list of options
     * @param integer|string $selectedValue for add selected='' to option attribute
     * @return string generated html
     */
    public static function options2html(array $options = [], $selectedValue = null){
        // buffer prepared html codes
        $buffer = [];
        // loop options
        foreach ($options as $key => $value) {
            // make optgroup
            if(is_array($value)){
                // turn optgroup options to html
                $optgroupOptions = static::options2html($value, $selectedValue);
                // append prepared group to buffer
                $buffer[] = "<optgroup label='$key'>$optgroupOptions</options>";
            } else {
            // make option
                // print selected='' if option value and selected value are equivalent
                $selected = static::selected($selectedValue, $value);
                // append prepared option to buffer
                $buffer[] = "<option value='$value' $selected>$key</option>";
            }
        }
        // make html
        $html = implode('', $buffer);
        // return result
        return $html;
    }

    /**
     * @inheritdoc
     */
    protected function prepare(){
        // Set name attribute
        $this->attributes['name'] = $this->name;
        // Set id attribute
        $this->attributes['id'] = $this->name;
        // prepare options
        $this->options = static::options2html($this->options, $this->value);
    }

    /**
     * @inheritdoc
     */
    public function render(){
        // Set default template
        $template =
            "<label title='%s' for='%s'>".
                "%s".
            "</label>".
            "<select %s>".
                "%s".
            "</select>".
            "%s";
        return sprintf(
            $template,
            $this->hint,
            $this->name,
            $this->label,
            $this->attributes,
            $this->options,
            $this->errors
        );
    }

}