<?php
/**
 *@description: This class generates the html drop down list box
 *
 **/
class ParentListBox
{
	public $rows;
	public $name;
	public $selectedItem;
	public $onChange;
	public $id;
	public $loader;
	public $style;
	public $className;
	public $size;
	public $multiple;
	public $loaderID;
	public $sqlQry;
	public $disabled;
	
	public function ParentListBox()
	{
		$this->rows=array();
		
	}
	/**
	 *@description: This method adds row to the select list
	 *
	 **/
	public function AddRow($Key, $Value)
	{
		 $this->rows[$Key]=$Value;
	}
	
	/**
	 *@description: This method outputs the html content for select box.
	 **/
	public function Display()
	{
		$htmlSelect = '';
		if ($this->loader)
		{
			$htmlSelect .= '<div style="display:none;" id="'.$this->loaderID.'"><img src="https://'.$_SERVER['HTTP_HOST'].'/Include/images/progress.gif">Please wait... ';
			$htmlSelect .= '</div>'.PHP_EOL;
		}
		$htmlSelect .= "<SELECT NAME=\"".$this->name."\" ";
		$htmlSelect .= "id=\"".$this->id."\" ";
		$htmlSelect .= "class=\"".$this->className."\" ";
		$htmlSelect .= "style=\"".$this->style."\" ";
		if(!empty($this->size))
		{
			$htmlSelect .= "size=\"".$this->size."\" ";		 	
		}
		
		if($this->disabled==true)
		{
			$htmlSelect .= " disabled=\"disabled\" ";	
		}
			
		if(!empty($this->multiple))
		{
			$htmlSelect .= "multiple=\"" .$this->multiple. "\" ";			
		}
		
		if(strlen($this->onChange)>0)
		{
			$htmlSelect .= "OnChange=\"".$this->onChange."\"";
		}
		$htmlSelect .= ">".PHP_EOL;
		foreach($this->rows as $Key=>$Value)
		{
			if(is_array($this->selectedItem)){
				if (in_array($Key, $this->selectedItem)) 
				{ 
					$htmlSelect .= "<OPTION VALUE=\"".$Key."\" SELECTED>".$Value."</OPTION>\r";
				}
				else
				{
					$htmlSelect .= "<OPTION VALUE=\"".$Key."\">".$Value."</OPTION>\r";
				}
			}
			elseif(strlen($this->selectedItem)>0)
			{
				if(!strcmp($this->selectedItem, $Key))
				{
					$htmlSelect .= "<OPTION VALUE=\"".$Key."\" SELECTED>".$Value."</OPTION>\r";
				}
				else
				{
					$htmlSelect .= "<OPTION VALUE=\"".$Key."\">".$Value."</OPTION>\r";
				}
			}
			else
			{
				$htmlSelect .= "<OPTION VALUE=\"".$Key."\">".$Value."</OPTION>\r";
			}
		}
		$htmlSelect .= "</SELECT>".PHP_EOL;
		return $htmlSelect;
	}
}
?>