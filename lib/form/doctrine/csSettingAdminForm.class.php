<?php

/**
 * PlugincsSetting form.
 *
 * @package    form
 * @subpackage csSetting
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class csSettingAdminForm extends csSettingForm
{
  public function configure()
  { 
    $this->widgetSchema['type'] = new sfWidgetFormSelectRadio(array(
                                       'choices' => sfConfig::get('app_csSettingsPlugin_types'),
                                       ));
    
    $choices = Doctrine::getTable('csSetting')->getExistingGroupsArray();
    
    if ($choices) 
    {
      $choices = array_merge(array('' => ''), $choices);
      $this->widgetSchema['setting_group'] = new sfWidgetFormSelect(array(
                                         'choices' => $choices,
                                         ));
    }
            
    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'csSetting', 'column' => array('name')), array('invalid' => 'Cannot use this name, a setting with this name already exists!'))
    );
    
    $help = array(
        'Text Field'      => 'HTML Attributes', 
        'Text Area'       => 'HTML Attributes', 
        'Checkbox'        => 'HTML Attributes', 
        'Checkbox'        => 'Choices (key=value)', 
        'Date Time'       => '<a href="http://www.symfony-project.org/api/1_2/sfWidgetFormDateTime" target="_blank">Widget Options</a> (with_time=false)', 
        'Yes/No Radios'   => 'HTML Attributes',
        'Database Model'  => '<a href="http://www.symfony-project.org/api/1_2/sfWidgetFormDoctrineChoice" target="_blank">Widget Options</a> (*model=MyModel method=__toString add_empty=true)',
        'Upload'          => '<a href="http://www.symfony-project.org/api/1_2/sfWidgetFormInputFileEditable" target="_blank">Widget Options</a>',
        'Rich Text'      => 'HTML Attributes',
        );
        
    $helpStr = '<b>The following options are supported for each setting type</b>:<ul>';
    
    foreach ($help as $key => $value) 
    {
      $helpStr .= "<li>$key: $value</li>";
    }
    $helpStr .= '</ul>* required';
    
    $this->widgetSchema->setLabels(array(
        'setting_group'   => 'Group',
        'slug'            => 'Handle',
        'widget_options'  => 'Options',
      ));
      
    $this->widgetSchema->setLabels(array(
        'slug'            => 'This is used in your code to pull the value for this setting.  Use csSettings::get($handle);',
        'widget_options'  => $helpStr,
        'setting_group'   => 'Organize your settings into groups',
      ));
  }
}