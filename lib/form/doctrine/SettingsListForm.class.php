<?php

class SettingsListForm extends sfForm
{
  public function configure()
  {
    foreach (Doctrine::getTable('csSetting')->findAllForList() as $setting) 
    {
      $form = new csSettingAdminForm($setting);
      $this->widgetSchema[$setting['slug']] = $form->getSettingWidget();
      $this->widgetSchema[$setting['slug']]->setDefault($setting->getValue());
      $this->validatorSchema[$setting['slug']] = $form->getSettingValidator();      
    }
    
    $this->widgetSchema->setNameFormat('cs_setting[%s]');
  }
}