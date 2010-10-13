<?php

/**
 * BasecsSettingsActions 
 * 
 * @uses autocsSettingsActions
 * @package 
 * @version $id$
 * @copyright 2006-2007 Brent Shaffer
 * @author Brent Shaffer <bshaffer@centresource.com>
 * @license See LICENSE that came packaged with this software
 */
class BasecsSettingActions extends AutocsSettingActions
{ 
  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new SettingsListForm();
    return parent::executeIndex($request);
  }
  
  public function executeListSaveSettings(sfWebRequest $request)
  {
    self::executeIndex($request);
    if($settings = $request->getParameter('cs_setting'))
    {
      $this->form = new SettingsListForm();
      $this->form->bind($settings, $request->getfiles('cs_setting'));
      if ($this->form->isValid()) 
      {
        foreach($this->form->getValues() as $slug => $value)
        {
          $setting = Doctrine::getTable('csSetting')->findOneBySlug($slug);
          if ($setting) 
          {
            $setting->setValue($value);
            $setting->save();
          }
        }
        
        if($files = $request->getFiles('cs_setting'))
        {
          $this->processUpload($settings, $files);
        }
        
        // Update form with new values
        $this->form = new SettingsListForm();

        $this->getUser()->setFlash('notice', 'Your settings have been saved.');
      }
      else
      {
        $this->getUser()->setFlash('error', 'Your form contains some errors');
      }
    }
    $this->setTemplate('index');
  }
  
  public function executeListRestoreDefault(sfWebRequest $request)
  {
    Doctrine::getTable('csSetting')->restoreDefault($request->getParameter('id'));
    
    $this->redirect($request->getReferer());
  }
  
  public function executeRestoreAllDefaults(sfWebRequest $request)
  {
    Doctrine::getTable('csSetting')->restoreAllDefaults();
    
    $this->redirect($request->getReferer());
  }
  
  public function processUpload($settings, $files)
  {
    $default_path = csSettings::getDefaultUploadPath();
    
    foreach ($files as $slug => $file) 
    {
      if ($file['name']) 
      {
        $setting = Doctrine::getTable('csSetting')->findOneBySlug($slug);
        
        $target_path = $setting->getOption('upload_path');
        
        $target_path = $target_path ? $target_path : $default_path;
        
        //If target path does not exist, attempt to create it
        if(!file_exists($target_path))
        {
          $target_path = mkdir($target_path) ? $target_path : 'uploads';
        }
        
        $target_path = $target_path . DIRECTORY_SEPARATOR . basename( $file['name']); 
        
        if(!move_uploaded_file($file['tmp_name'], $target_path)) 
        {
          $this->getUser()->setFlash('error', 'There was a problem uploading your file!');
        }
        else
        {  
          $setting->setValue(basename($file['name']));
          $setting->save();
        }
      }
      elseif (isset($settings[$slug.'_delete'])) 
      {
        $setting = Doctrine::getTable('csSetting')->findOneBySlug($slug);
        unlink($setting->getUploadPath().'/'.$setting->getValue());
        $setting->setValue('');
        $setting->save();
      }
    }
  }
}
