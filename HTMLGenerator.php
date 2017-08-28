<?php

abstract class HTMLElement
{
	abstract public function toString();
	abstract public function toIndentedString($indentation);
	
	abstract public function isScalar();
}

class HTMLRawText extends HTMLElement
{
	private $text;
	private $value;
	
	public function __construct($text)
	{
		$this->text = $text;
	}
	
	public function toString()
	{
		return $this->text;
	}
	
	public function toIndentedString($indentation=0)
	{
		return str_repeat("\t", $indentation).$this->toString();
	}
	
	public function isScalar()
	{
		return true;
	}
}

abstract class HTMLTag extends HTMLElement
{
	protected $tagName;
	protected $attributes;
	
	protected $classes=array();
	
	public function __construct($tagName, $attributes=array())
	{
		$this->tagName = $tagName;
		$this->attributes = $attributes;
		if(isset($attributes['class']))
		{
			$this->setClass($attributes['class']);
		}
	}
	
	/* Attributes */
	public function hasAttribute($name)
	{
		return array_key_exists($name, $this->attributes);
	}
	
	public function setAttribute($name, $value=null)
	{
		if('class' == $name)
		{
			$this->setClass($value);
		}
		else
		{
			$this->attributes[$name] = $value;
		}
	}
	
	public function getAttribute($name)
	{
		if($this->hasAttribute($name))
		{
			return $this->attributes[$name];
		}
		return null;
	}
	
	public function removeAttribute($name)
	{
		unset($this->attributes[$name]);
		if('class' === $name)
		{
			$this->classes = array();
		}
	}
	
	public function removeAllAttributes()
	{
		$this->attributes = array();
		$this->classes = array();
	}
	/* Attributes */

	/* Id */
	public function hasId()
	{
		return isset($this->attributes['id']);
	}
	public function getId()
	{
		if($this->hasId())
		{
			return $this->attributes['id'];
		}
		return null;
	}
	public function setId($id)
	{
		$this->attributes['id'] = $id;
	}
	
	/* Id */
	
	/* Classes */
	private function saveClasses()
	{
		if(!empty($this->classes))
		{
			$this->attributes['class'] = implode(' ', $this->classes);
		}
		else
		{
			unset($this->attributes['class']);
		}
	}
	
	public function hasClass($class)
	{
		return in_array($class, $this->classes);
	}
	public function addClass($class)
	{
		$this->classes[] = $class;
		$this->saveClasses();
	}
	public function setClass($classes)
	{
		$this->classes = preg_split('/\s+/', $classes);
		$this->saveClasses();
	}
	public function setClasses($classes)
	{
		$this->classes = $classes;
		$this->saveClasses();
	}
	public function getClasses()
	{
		return $this->classes;
	}
	public function removeClass($class)
	{
		if(($position = array_search($class, $this->classes)) !== false) 
		{
			unset($this->classes[$position]);
			$this->saveClasses();
		}
	}
	public function removeAllClasses()
	{
		$this->classes = array();
		$this->saveClasses();
	}	
	/* Classes */
	
	protected function toCustomString()
	{
		$str = "{$this->tagName}";
		foreach($this->attributes as $name => $value)
		{
			$str .= " $name";
			if(!is_null($value))
			{
				$str .= "=\"{$value}\"";
			}
		}
		return $str;
	}
}

class HTMLSimpleTag extends HTMLTag
{
	public function toString()
	{
		return "<".$this->toCustomString()."/>";
	}
	public function toIndentedString($indentation=0)
	{
		return str_repeat("\t", $indentation).$this->toString();
	}
	
	public function isScalar()
	{
		return true;
	}
}

class HTMLContainer extends HTMLTag
{
	private $childs=array();
	
	
	public function addChild($child)
	{
		$this->childs[] = $child;
	}
	public function insertChildAt($index, $child)
	{
		if($index<0)
		{
			$index = 0;
		}
		
		if($index<count($this->childs))
		{
			array_splice($this->childs, $index, 0, array($child));
		}
		else
		{
			$this->childs[] = $child;
		}
	}
	public function getChildById($id)
	{
		foreach($this->childs as $child)
		{
			if
			(
				$child->hasId() 
				&& 
				$id == $child->getId()
			)
			{
				return $child;
			}
		}
		return null;
	}
	public function getChildAt($index)
	{
		if($index >= 0 && $index < count($this->childs))
		{
			
			return $this->childs[$index];
		}
		return null;
	}
	public function getFirstChild()
	{
		return $this->getChildAt(0);
	}
	public function getLastChild()
	{
		return $this->getChildAt(count($this->childs)-1);
	}
	public function removeAllChilds()
	{
		$this->childs = array();
	}
	
	public function addRawText($text)
	{
		$this->childs[] = new HTMLRawText($text);
	}
	public function setInnerHTML($text)
	{
		$this->removeAllChilds();
		$this->addRawText($text);
	}
	public function setText($text)
	{
		$this->setInnerHTML($text);
	}

	public function toString()
	{
		$str  = "<".$this->toCustomString().">";
		foreach($this->childs as $child)
		{
			$str .= $child->toString();
		}
		$str .= "</{$this->tagName}>";
		return $str;
	}
	
	public function toIndentedString($indentation=0)
	{
		$str  = str_repeat("\t", $indentation)."<".$this->toCustomString().">";
		if(!empty($this->childs))
		{
			// don't indent if there is only one scalar children
			if(1===count($this->childs) && $this->childs[0]->isScalar())
			{
				$str .= $this->childs[0]->toString();
			}
			else
			{
				$str .= "\n";
				
				foreach($this->childs as $child)
				{
					$str .= $child->toIndentedString($indentation+1)."\n";
				}
				
				$str .= str_repeat("\t", $indentation);
			}
		}
		$str .= "</{$this->tagName}>";
		return $str;
	}
	
	public function isScalar()
	{
		return false;
	}
}
?>