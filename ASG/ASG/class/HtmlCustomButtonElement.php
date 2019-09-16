<?php
/**
 *
 **/
 class HtmlCustomButtonElement
 {
	 public $type;
	 public $value;
	 public $name;
	 public $id;
	 public $Class;
	 public $onsubmit;
	 public $onclick;
	 public $style;
	 public $displayContent;
	 public $disabled;
	 
	 public function __construct($type = 'button')
	 {
		 $this->type = $type;
	 }
	 
	 public function renderHtml()
	 {
		 $htmlButton = '';
		 $htmlButton .= "<div style=\"".$this->style."\">";
		 if (isset($this->type))
		 {
			 $htmlButton .= "<input type=\"".$this->type."\" ";
		 }
		 
		 if (isset($this->Class))
		 {
			 $htmlButton .= " class=\"".$this->Class."\" ";
		 }
		 
		 if (isset($this->name))
		 {
			 $htmlButton .= " name=\"".$this->name."\" ";
		 }
		 
		 
		 if (isset($this->value))
		 {
			 $htmlButton .= " value=\"".$this->value."\" ";
		 }
		 
		 
		 if (isset($this->id))
		 {
			 $htmlButton .= " id = \"".$this->id."\" ";
		 }
		 
		 if (isset($this->onsubmit))
		 {
			 $htmlButton .= " onSubmit=\"".$this->onsubmit."\"";
		 }
		 
		 if (isset($this->onclick))
		 {
			 $htmlButton .= " onClick=\"".$this->onclick."\"";
		 }
		 
		 if($this->disabled==true)
		{
				$htmlButton .=' disabled="disabled" ';	
		}
			
		 
		 if(isset($this->displayContent) && !isset($this->type))
		 {
				$htmlButton .= $this->displayContent; 
		 }
		 else
		 {
		 	$htmlButton .= ' />';
		 }
		 $htmlButton .= '</div>';

		 return $htmlButton;
		 
	 }
	 public function resetProperties()
	 {
			foreach($this as $key=>$value)
			{
				unset($this->$key);		
			}
	}
	
 }
?>