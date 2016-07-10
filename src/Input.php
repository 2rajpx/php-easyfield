<?php

namespace 2jpx\field;

/**
 * @link https://github.com/2rajpx/php-field/
 * @license https://github.com/2rajpx/php-field/blob/master/LICENSE
 */
/**
 * Input Class
 * Creates input field
 *
 * @author Tooraj Khatibi <2rajpx@gmail.com>
 * @link https://github.com/2rajpx/
 */
class Input extends Field{

    /**
     * show/hide type='example' in the attributes
     * @var boolean
     */
    protected $showTypeAttr = true;

    /**
     * @inheritdoc
     */
    protected function attributes() {
        // Set name
        $this->attributes['name'] = $this->name;
        // Set id
        $this->attributes['id'] = $this->name;
        // Set value
        $this->attributes['value'] = $this->value;
        // Type must be visible
        if ($this->showTypeAttr) {
            // Set type attribute
            $this->attributes['type'] = static::TYPE;
        }
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
            "<input %s/>".
            "<div>".
                "%s".
            "</div>";
        return sprintf(
            $template,
            $this->hint,
            $this->name,
            $this->label,
            $this->attributes,
            $this->errors,
        );
    }

}
