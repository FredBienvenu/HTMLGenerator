<?php
require_once(__DIR__.'/HTMLGenerator.php');

class HTMLDiv extends HTMLContainer
{
	public function __construct($attributes=array())
	{
		parent::__construct('div', $attributes);
	}
}

class HTMLSpan extends HTMLContainer
{
	public function __construct($attributes=array())
	{
		parent::__construct('span', $attributes);
	}
}

class HTMLForm extends HTMLContainer
{
	private $action;
	private $method;
	
	public function __construct($attributes=array())
	{
		parent::__construct('form', $attributes);
	}
	
	public function getAction()
	{
		return $this->action;
	}
	public function setAction($action)
	{
		$this->action = $action;
	}
	
	public function getMethod()
	{
		return $this->method;
	}
	public function setMethod($method)
	{
		$this->method = $method;
	}
}
?>
