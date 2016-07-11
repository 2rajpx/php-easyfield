<?php

namespace tjpx\field;

/**
 * @link https://github.com/2rajpx/php-field/
 * @license https://github.com/2rajpx/php-field/blob/master/LICENSE
 */
/**
 * TextArea Class
 * Creates text area field
 *
 * @author Tooraj Khatibi <2rajpx@gmail.com>
 * @link https://github.com/2rajpx/
 */
class TextArea extends Field{

	/**
	 * @inheritdoc
	 */
	public function render(){
		$template =
			"<p>".
				"<label for='%s'>".
					"%s".
				"</label>".
				"<textarea %s>".
					"%s".
				"</textarea>".
				"%s"
			"</p>"
		;
		return sprintf(
			$template,
			$this->name,
			$this->label,
			$this->attributes,
			$this->value,
			$this->errors
		);
	}

	/**
	 * @inheritdoc
	 */
	protected function prepare(){
		// set name
		$this->attributes['name'] = $this->name;
		// set id attribute
		$this->attributes['id'] = $this->name;
	}

}