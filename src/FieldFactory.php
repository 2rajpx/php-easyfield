<?php

namespace tjpx\field;

use tjpx\helper\base\Factory;
use tjpx\helper\numstr\Naming;

/**
 * @link https://github.com/2rajpx/php-field/
 * @license https://github.com/2rajpx/php-field/blob/master/LICENSE
 */
/**
 * The field factory class to make instance from form fields
 *
 * @author Tooraj Khatibi <2rajpx@gmail.com>
 * @link https://github.com/2rajpx/
 */
class FieldFactory extends Factory {

	/**
	 * Builds the field and returns it
	 *
	 * @param array $name field config
	 *
	 * @return Field instance
	 *
	 * @throws Exception if the name of the field not found in config array
	 */
	public static function getInstance(array $config = []) {
        // If element has no name
        if (!isset($config['name'])){
            throw new \Exception("You have to set the name of the field", 1);
        }
        if(!isset($config['class'])){
            $config['class'] = Text::className();
        }
        // Make _pascalName by name of the element
        $this->_pascalName = Naming::camelize($config['name']);
        // Make binding name
        $this->_bindingName = null;
        if ($prefix = $this->prefix) {
            // Prepend the prefix to the binding name
            $this->_bindingName.= Naming::camelize($prefix);
        }
        // Append the pascal name to binding name
        $this->_bindingName.= $this->_pascalName;
        // Use (_) seperator insetead of camelCase
        $this->_bindingName = Naming::camel2id($this->_bindingName, '_');
        return parent::getInstance($config);
	}

}
