<?php
/**
 *@description : This class generates the html text box element.
 **/
 
class HtmlTextElement 
{
	public $alt = NULL;
	public $autofocus;
	public $onClick = '';
	public $maxLength;
	public $name;
	public $Class;
	public $id;
	public $type;
	public $value;
	public $width;
	public $height;
	public $label;
	public $onfocus;
	public $onblur;
	public $txt;
	public $size;
	public $onkeypress;
	public $readonly;
	public $style;
	public $colspan;
	public $rowspan;
	public $onchange;
	public $displayContent;
	public $onkeyup;
	public $accesskey; // This is only for date controls
	public $minStartDate;
	public $maxStartDate;
	public $tdID = array(); //setting tdID .
	public $disabled;
	public $onkeydown;
	public $tdStyle;
	public $thStyle;
	public $rnetv4TdID;
	public $rnetv4ThID;
	public $src;
	
	/**
	 *by default constructor accepts $type = text, we can modify it password element by sending $type = password
	 */
	public function __construct( $type = 'text')
	{				
		$this->type		= $type;
	
	}
	
	/**
	 *@description: This method renders the html input box element
	 **/
	
	public function renderHtml()
	{
		//error_log('$this '.$this->type);		
		
		$htmlTag	 =	'';
		$value = '';
		if(!isset($this->displayContent))
		{
			if( isset($this->type))
			{
				$htmlTag 	.= '<input type=\''.$this->type.'\' ';
			}
			else
			{
				$htmlTag 	.= '<input type=\'text\' ';
			}
			
			if( isset($this->alt) )
			{
				$htmlTag .= 'alt=\''.$this->alt.'\' ';
			}
			if( isset($this->name))
			{
				//error_log("inside if: " . $this->name);
				$htmlTag .= 'name=\''.$this->name.'\' ';
			}
			if( isset($this->maxLength) )
			{
				$htmlTag .= 'maxlength=\''.$this->maxLength.'\' ';
			}
			if( isset($this->value))
			{
				$htmlTag .= 'value=\''.$this->value.'\' ';
			}
			if( isset($this->id))
			{
				$htmlTag .= 'id=\''.$this->id.'\' ';
			}
			if( isset ($this->onfocus) )
			{
				$htmlTag .=' onfocus=\''.$this->onfocus.'\'';
			}
			if( isset ($this->onblur) )
			{
				$htmlTag .=' onblur=\''.$this->onblur.'\'';
			}
			if( isset ($this->onClick) )
			{
				$htmlTag .=' onclick=\''.$this->onClick.'\'';
			}
			if(isset($this->size))
			{
				$htmlTag .= ' size=\''.$this->size.'\'';
			}		
			
			
			if( isset ($this->onkeypress) )
			{
				$htmlTag .=' onkeypress=\''.$this->onkeypress.'\'';
			}
			
			if( isset ($this->onkeyup) )
			{
				$htmlTag .=' onkeyup=\''.$this->onkeyup.'\'';
			}
			
			if( isset ($this->src) )
			{
				$htmlTag .=' src=\''.$this->src.'\'';
			}

				
			if($this->readonly==true)
			{
				$htmlTag .=' readonly="readonly" ';	
			}
			if($this->disabled==true)
			{
				$htmlTag .=' disabled="disabled" ';	
			}
			
			
			if( isset ($this->style) )
			{
				$htmlTag .=' style=\''.$this->style.'\'';
			}
			
			if( isset ($this->onchange) )
			{
				$htmlTag .=' onchange=\''.$this->onchange.'\'';
			}
			
			if( isset ($this->accesskey) )
			{
				$htmlTag .=' accesskey=\''.$this->accesskey.'\'';
			}
			
			if( isset($this->minStartDate) )
			{
				$htmlTag .= ' minStartDate=\'' . $this->minStartDate . '\'';
			}
			
			if( isset($this->maxStartDate) )
			{
				$htmlTag .= ' maxStartDate=\'' . $this->maxStartDate . '\'';
			}
			
			if( isset ($this->Class) )
			{
				$htmlTag .=' class=\''.$this->Class.'\'';
			}
			
			if( isset ($this->onkeydown) )
			{
				$htmlTag .=' onkeydown=\''.$this->onkeydown.'\'';
			}
			
			
			if($this->checked == 'checked')
			{
				$htmlTag .= 'checked = \'checked\'';
			}
			else if($this->isDefaultChkd == '1')
			{
				$htmlTag .= 'checked = \'checked\'';
			}
			else 
			{
				$htmlTag .= '';
			}
			
			$htmlTag .= '/>'.' '.$this->txt.PHP_EOL;
		}
		else
		{
			$htmlTag = 	$this->displayContent;
		}
		//error_log("Html Tag ". $htmlTag);
		return $htmlTag;
	}
	/**
	 *@description: This method adds label and generates the <th> <td> block of table
	 **/
	public function addLabel($htmlElement, $label, $style, $required = FALSE)
	{
		//error_log(__METHOD__ . $htmlElement);
		if(!isset($this->colspan))
		{
			$this->colspan = 1;
		}
				
		if(!isset($this->rowspan))
		{
			$this->rowspan = 1;
		}
		
		
		$labelName = str_replace(" ", "_", $label);	
		if(empty($this->rnetv4TdID))
		{
			$tdID = $this->split_nth($labelName, '_', 2);
			$this->rnetv4TdID = 'rnetv4_'.$tdID[0];
		}
		
		unset($randomDivID);
		$randomDivID = $this->generateRandomString();
		
		$htmlLabel = '';
		if(!empty($label))
		{
			$htmlLabel .= '<th style="'.$this->thStyle.'"  rowspan="'.$this->rowspan.'" id="'.$this->rnetv4ThID.'">'. $label;
		if ($required) 
		{
			$htmlLabel .= '<span style=color:'.$style.' class="required">* </span>'.PHP_EOL;
		}
		$htmlLabel .= '</th>'.PHP_EOL;
		$htmlLabel .= '<td colspan="'.$this->colspan.'" rowspan="'.$this->rowspan.'" id="'.$this->rnetv4TdID.'" style="'.$this->tdStyle.'"><div id="'.$randomDivID.'" style="margin:0px; padding:0px;">'.$htmlElement.'</div></td>'.PHP_EOL;
		}
		else 
		{
			/*if($this->colspan==1 && $this->rowspan==1)
			{
				$htmlLabel .= '<td id="'.$this->rnetv4ThID.'">&nbsp; &nbsp;</td>';
			}*/
			$htmlLabel .= '<td colspan="'.$this->colspan.'" rowspan="'.$this->rowspan.'" id="'.$this->rnetv4TdID.'" style="'.$this->tdStyle.'"><div id="'.$randomDivID.'" style="margin:0px; padding:0px;">'.$htmlElement.'</div></td>'.PHP_EOL;
		}
		
		$this->resetProperties();
		return $htmlLabel;
	}
	
	public function generateRandomString($length = 10) 
	{
		return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
   	}
	
	
	public function resetProperties()
	{
	
			foreach($this as $key=>$value)
			{
					unset($this->$key);		
			}
	}
	
	/**
	 *@description:This function used to set the td id by converting the label name with underscores and take the first part
	 *@author:Bharath Kumar
	 ****/
	function split_nth($str, $delim, $n)
	{
  		return array_map(function($p) use ($delim) 
		{
      	return implode($delim, $p);
  		}, 
 		array_chunk(explode($delim, $str), $n));
	}
}

?>