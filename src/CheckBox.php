<?php

namespace tjpx\field;

/**
 * @link https://github.com/2rajpx/php-field/
 * @license https://github.com/2rajpx/php-field/blob/master/LICENSE
 */
/**
 * Checkbox Class
 * Creates checkbox field
 *
 * @author Tooraj Khatibi <2rajpx@gmail.com>
 * @link https://github.com/2rajpx/
 */
class CheckBox extends Input {

	const TYPE = 'checkbox';

	/**
	 * use wp checked and return string instead of print it
	 * @param integer|string $checkedValue first arg in checked()
	 * @param integer|string $trueValue second arg in checked()
	 * @return string saved result in buffer
	 */
	public static function checked($checkedValue, $trueValue = 'on'){
		// start buffer
		ob_start();
		// wp checked print
		checked( $checkedValue, $trueValue );
		// return buffer
		return ob_get_clean();
	}

	/**
	 * @inheritdoc
	 */
	public function render(){
		$template =
			"<p>".
				"<input %s/>".
				"<label for='%s'>".
					"%s".
				"</label>".
			"</p>";
		return sprintf(
			$template,
			$this->attributes,
			$this->name,
			$this->label
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
		// set checked attribute
		$this->attributes[] = static::checked($value);
		// type must be visible
		if($this->showTypeAttr){
			// set type attribute
			$this->attributes['type'] = static::TYPE;
		}
	}
	
}