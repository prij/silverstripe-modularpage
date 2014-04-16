<?php
/**
 * A content module
 * 
 * @package modularpage
 */
class ContentModule extends DataObject {

	private static $db = array(
		'Title' => 'Text',
		'Content' => 'HTMLText',
		'Published' => 'Boolean',
		'SortOrder'=>'Int',
	);
	
	private static $default_parent = 'ModularPage';	

	private static $display_class = 'content-module';
		
	private static $has_one = array(
		'ModularPage' => 'ModularPage',
	);
	
	private static $defaults = array(
		'Published' => true
	);

	private static $summary_fields = array(
    	'Title' => 'Title',
    	'ShowPublished' => 'Published',
  	);

  	public function RenderContent(){
  		return $this->Content;
  	}

    public function forTemplate(){
      return $this->renderWith($this->ClassName);
    }

  	public function ShowPublished(){
  		if($this->Published)
  			return "Yes";
  		else 
  			return "No";
  	}

	public function getCMSFields() {
    	$fields = parent::getCMSFields();
    	$fields->removeByName('Title');
    	$fields->removeByName('SortOrder');
    	$fields->addFieldToTab('Root.Main', new TextField('Title'), 'Published');
    	$fields->addFieldToTab('Root.Main', new HTMLEditorField('Content'));
    	return $fields;
  	}
}