<?php
require_once(__DIR__.'/HTMLGenerator.php');

class HTMLInput extends HTMLSimpleTag
{
	public function __construct($type=null, $attributes=array())
	{
		parent::__construct('input', $attributes);
				
		if(null!==$type)
		{
			$this->setType($type);
		}
	}
	
	public function getType()
	{
		if(isset($this->attributes['type']))
		{
			return $this->attributes['type'];
		}
		return null;
	}
	public function setType($type)
	{
		$this->attributes['type'] = $type;
	}
}

/* SubClasses */
class HTMLInputText extends HTMLInput
{
	public function __construct($attributes=array())
	{
		parent::__construct('text', $attributes);
	}
}

class HTMLInputReset extends HTMLInput
{
	public function __construct($attributes=array())
	{
		parent::__construct('reset', $attributes);
	}
}

abstract class HTMLInputCheckable extends HTMLInput
{
	public function isChecked()
	{
		return array_key_exists('checked', $this->attributes);
	}
	public function check()
	{
		if(!$this->isChecked())
		{
			$this->attributes['checked'] = null;
		}
	}
	public function uncheck()
	{
		unset($this->attributes['checked']);
	}
	public function toggleCheck()
	{
		if($this->isChecked())
		{
			$this->uncheck();
		}
		else
		{
			$this->check();
		}
	}
}

class HTMLInputRadio extends HTMLInputCheckable
{
	public function __construct($attributes=array())
	{
		parent::__construct('radio', $attributes);
	}
}

class HTMLInputCheckbox extends HTMLInputCheckable
{
	public function __construct($attributes=array())
	{
		parent::__construct('checkbox', $attributes);
	}
}
/* SubClasses */

/* Select */
class HTMLOption extends HTMLContainer
{
	public function __construct($value, $text, $selected=false, $attributes=array())
	{
		parent::__construct('option', $attributes);
		$this->attributes['value'] = $value;
		
		if($selected)
		{
			$this->attributes['selected'] = null;
		}
		
		$HTMLText = new HTMLRawText($text);
		$this->addChild($HTMLText);
	}
}

class HTMLSelect extends HTMLContainer
{
	public function __construct($attributes=array())
	{
		parent::__construct('select', $attributes);
	}
	
	public function addOption($value, $text, $selected=false, $attributes=array())
	{
		$option = new HTMLOption($value, $text, $selected, $attributes);
		$this->addChild($option);
	}
}
/* Select */

/* Label */
class HTMLLabel extends HTMLContainer
{
	public function __construct($for=null, $attributes=array())
	{
		parent::__construct('label', $attributes);
		if(!is_null($for))
		{
			$this->attributes['for'] = $for;
		}
	}
}
/* Label */
?>
