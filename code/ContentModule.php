<?php

/**
 * @package modularpage
 */

/**
 * A Content Module
 */

class ContentModule extends DataObject
{
    private static $singular_name = 'Content module';
    private static $description = "Standard content block using the text editor, supports images and tables.";
    
    private static $db = array(
        'Title' => 'Text',
        'Content' => 'HTMLText',
        'Published' => 'Boolean',
        'SortOrder' => 'Int',
        'ModuleType' => 'Varchar',
    );
    
    private static $has_one = array(
        'ModularPage' => 'ModularPage',
    );
    
    private static $defaults = array(
        'Published' => true,
    );
    
    private static $summary_fields = array(
        'Title' => 'Title',
        'showPublished' => 'Published',
    );
    
    public function showPublished() {
        if ($this->Published) return "Yes";
        else return "No";
    }
    
    public function Show() {
        return $this->renderWith($this->ClassName);
    }
    
    public function DisplayClass() {
        return 'modular-content ' . strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $this->ClassName));
    }
    
    public function getDescription() {
        return self::$description;
    }
    
    private function getModuleTypes() {
        $moduleTypes = array();
        $classes = ClassInfo::getValidSubClasses('ContentModule');
        foreach ($classes as $type) {
            $instance = singleton($type);
            $html = sprintf('<span class="page-icon class-%s"></span><strong class="title">%s</strong><span class="description">%s</span>', $instance->ClassName, $instance->stat('singular_name') , $instance->stat('description'));
            $moduleTypes[$instance->ClassName] = $html;
        }
        return $moduleTypes;
    }
    
    public function onBeforeWrite() {
        parent::onBeforeWrite();
        if (!$this->Title) {
            $this->Title = "Untitled";
        }
    }
    
    public function getAdminFields($fieldList) {
        $fieldList->addFieldToTab('Root.Main', new HTMLEditorField('Content'));
        return $fieldList;
    }
    
    public function getCMSFields() {
        $fields = new FieldList();
        $fields->push(new TabSet("Root", $mainTab = new Tab("Main")));
        
        $typeField = new OptionsetField("ModuleType", "Module type", $this->getModuleTypes() , $this->ClassName);
        
        $fields->addFieldToTab('Root.Main', new TextField('Title'));
        $fields->addFieldToTab('Root.Main', $typeField);
        
        if ($this->ModuleType) {
            $fields->addFieldToTab('Root.Main', new CheckboxField('Published'));
            $instance = singleton($this->ModuleType);
            $instance->getAdminFields($fields);
        }
        
        return $fields;
    }
}
