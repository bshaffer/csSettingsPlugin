<?php

/**
 * PlugincsSetting form.
 *
 * @package    form
 * @subpackage csSetting
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
abstract class PlugincsSettingForm extends BasecsSettingForm
{
  public function getSettingWidget()
  {
    $type = $this->getObject()->getType();
    $name = $this->getObject()->getName();
    
    // See if there is a widget specific to this setting
    $method = 'get'.sfInflector::camelize($name).'SettingWidget';
    if (method_exists($this, $method))
    {
      return $this->$method();
    }
    // Else, see if there is a widget specific to this setting's type
    $method = 'get'.sfInflector::camelize($type).'SettingWidget';
    if (method_exists($this, $method))
    {
      return $this->$method();
    }
    // Return a generic Widget
    return new sfWidgetFormInput(array(), $this->getObject()->getOptionsArray());
  }

  public function getSettingValidator()
  {
    $type = $this->getObject()->getType();
    $name = $this->getObject()->getName();
    // See if there is a validator specific to this setting
    $method = 'get'.sfInflector::camelize($name).'SettingValidator';
    if (method_exists($this, $method))
    {
      return $this->$method();
    }
    // Else, see if there is a validator specific to this setting's type
    $method = 'get'.sfInflector::camelize($type).'SettingValidator';
    if (method_exists($this, $method))
    {
      return $this->$method();
    }
    // Return a generic Validator    
    return new sfValidatorString(array('required' => false));
  }

  public function getRichTextSettingWidget()
  {
    if(class_exists('sfWidgetFormCKEditor'))
     return new sfWidgetFormCKEditor(array(), $this->getObject()->getOptionsArray());
    else
      return new sfWidgetFormTextarea(array(), $this->getObject()->getOptionsArray());
  }
  
  //Type Textarea
  public function getTextareaSettingWidget()
  {
    return new sfWidgetFormTextarea(array(), $this->getObject()->getOptionsArray());
  }
  
  // Type Checkbox
  public function getCheckboxSettingWidget()
  {
    return new sfWidgetFormInputCheckbox(array(), $this->getObject()->getOptionsArray());
  }

  // Type Date
  public function getDateTimeSettingWidget()
  {
    return new sfWidgetFormDateTime($this->getObject()->getOptionsArray());
  }
  public function getDateTimeSettingValidator()
  {
    return new sfValidatorDateTime(array('required' => false));
  }
  
  // Type Yesno
  public function getYesnoSettingWidget()
  {
    return new sfWidgetFormSelectRadio(array('choices' => array('yes' => 'Yes', 'no' => 'No')), $this->getObject()->getOptionsArray());
  }
  public function getYesnoSettingValidator()
  {
    return new sfValidatorChoice(array('choices' => array('yes', 'no'), 'required' => false));
  }
  
  //Type Select List
  public function getSelectSettingWidget()
  {
    return new sfWidgetFormSelect(array('choices' => $this->getObject()->getOptionsArray(), 'required' => false));
  }
  public function getSelectSettingValidator()
  {
    return new sfValidatorChoice(array('choices' => $this->getObject()->getOptionsArray(), 'required' => false));
  }
  
  //Type Model
  public function getModelSettingWidget()
  {
    return new sfWidgetFormDoctrineChoice($this->getObject()->getOptionsArray());
  }
  public function getModelSettingValidator()
  {
    return new sfValidatorDoctrineChoice($this->getObject()->getOptionsArray());
  }
  
  //Type Upload
  public function getUploadSettingWidget()
  {
    $path = $this->getObject()->getUploadPath() . '/' . $this->getObject()->getValue();
    $options = array(
          'file_src' => $this->getObject()->getValue(),
          'template' => "<a href='/$path'>%file%</a><br />%input%<br />%delete% %delete_label%",
      );
    
    // If you want to pass the widget custom settings, you can override in your setting's options  
    $options = array_merge($options, $this->getObject()->getOptionsArray());
    
    return new sfWidgetFormInputFileEditable($options);
  }
  
  // Overriding Bind in this case allows us to have the form field "setting_group_new" for usability
  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    $taintedValues['setting_group'] = (isset($taintedValues['setting_group_new']) && $taintedValues['setting_group_new']) ?  $taintedValues['setting_group_new'] : $taintedValues['setting_group'];
    unset($taintedValues['setting_group_new']);
    $ret = parent::bind($taintedValues, $taintedFiles);
    return $ret;
  }
}