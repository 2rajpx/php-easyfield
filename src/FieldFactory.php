<?php

namespace tjpx\field;

use tjpx\helper\Factory;

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
        return parent::getInstance($config);
	}

}
