<?php
/**
 *	@description: This class renders the HTML tag elements
 *
 **/
 
 class HtmlTagElement 
 {
	 public $tagName;
	 public $tagParams;
	 public $src;
	 public $txt;
	 
	 /**
	  *	Class Constructor
	  **/
	 public function __construct()
	 {
		 
		 
	 }	 
	 	 
	 /**
	  *	This function Opens the html tag for element
	  **/
	 public function openTag($tagName, $tagParams)
	 {
		 $this->htmlTagOpen	= '';
		 return $this->htmlTagOpen	.= '<'.$tagName.' '.$tagParams. '>'.PHP_EOL;	

	 }
	 
	 /**
	  *	This function Closes the html tag of an element.
	  **/
	 public function closeTag($tagName)
	 {
		 $this->htmlCloseTag = '';
		 return $this->htmlCloseTag .='</'.$tagName.'>'.PHP_EOL;
		 
	 }
 
 	public function anchorTag($url, $txtLinks, $tagParams)
	{
		//error_log('URL: '.$url . "$text: ".$txtLinks);
		$this->htmlATag = '';
		return $this->htmlATag	.= "<a href=\"".$url."\" ".$tagParams.">".$txtLinks."</a>";
	}
	
	public function imgTag($src, $tagParams)
	{
		$this->htmlImgTag = '';
		return $this->htmlImgTag .= '<img src=\''.$src.'\' '.$tagParams.'/>';
	}
	
	public function hTag($tagName, $tagParam)
	{
		$this->hTag = '<'.$tagName.'>';
		$this->hTag .= $tagParam;
		$this->hTag .= '<'.$tagName.'/>';
		return $this->hTag;
	}
	
	public function plainText($txt)
	{
		return	$this->text = $txt;
	}
	
	public function textAreatag($tagParams, $text)
	{
		$this->htmlTag = '';
		$this->htmlTag .= '<textarea '.$tagParams.'>';
		$this->htmlTag .= $text;
		$this->htmlTag .='</textarea>';
		return $this->htmlTag;
		
	}
 
 }
?>