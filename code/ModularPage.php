<?php

/**
 * @package modularpage
 */

/**
 * Base page class for ModularPage
 */

class ModularPage extends Page
{
    
    private static $icon = "modularpage/images/modularpage-file.png";
    private static $description = "A page made up of content modules";
    
    private static $db = array();
    
    private static $defaults = array();
    
    private static $has_one = array();
    
    private static $has_many = array(
        'ContentModules' => 'ContentModule',
    );
    
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        
        $fields->removeByName('Content');
        //$fields->addFieldToTab('Root.Main', HTMLEditorField::create('Content')->performReadonlyTransformation() , 'Metadata');

        $conf = GridFieldConfig_RecordEditor::create();
        $conf->addComponent(new GridFieldSortableRows('SortOrder'));
        $fields->addFieldToTab('Root.Main', new GridField('ContentModules', 'Content', $this->ContentModules()->sort('SortOrder'), $conf), 'Metadata');
        return $fields;
    }
    
    public function PublishedContentModules()
    {
        return ContentModule::get()->filter(array(
            'ModularPageID' => $this->ID,
            'Published' => 1
        ))->sort('SortOrder');
    }
    
    private function generateContent()
    {
        $this->Content = '';
        $contentModules = $this->PublishedContentModules();
        foreach ($contentModules as $module) {
            $this->Content.= $module->Show();
        }
        return $this->Content;
    }
    
    public function onBeforeWrite()
    {
        $this->generateContent();
        parent::onBeforeWrite();
    }
}

class ModularPage_Controller extends Page_Controller
{
}
