<?php

namespace tjpx\field;

/**
 * @link https://github.com/2rajpx/php-field/
 * @license https://github.com/2rajpx/php-field/blob/master/LICENSE
 */
/**
 * Hidden Class
 * Create hidden field
 *
 * @author Tooraj Khatibi <2rajpx@gmail.com>
 * @link https://github.com/2rajpx/
 */
class Hidden extends Input {

    /**
	 * @inheritdoc
     */
    public function render(){
        return sprintf("<input %s/>", $this->attributes);
    }

}