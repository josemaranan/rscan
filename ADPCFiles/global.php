<?php

class GlobalVariable
{
	protected $htmlTagObj;
	protected $htmlTextElement;
	protected $headerObj;
	protected $htmlForm;
	protected $tableObj;
	protected $jsFilesAjax;
	protected $commonListBox;    
	
	public function __construct()
	{
		global $htmlTagObj;
		$this->htmlTagObj = $htmlTagObj;
		global $htmlTextElement;
		$this->htmlTextElement = $htmlTextElement;
		global $headerObj;
		$this->headerObj = $headerObj;
		global $htmlForm;
		$this->htmlForm = $htmlForm;
		global $tableObj;
		$this->tableObj = $tableObj;
		global $jsFilesAjax;
		$this->jsFilesAjax = $jsFilesAjax;
		global $commonListBox;
		$this->commonListBox = $commonListBox;
	}
}