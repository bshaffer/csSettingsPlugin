<?php

/**
 * BasecsSettings
 *
 * @package
 * @version $id$
 * @copyright 2006-2007 Brent Shaffer
 * @author Brent Shaffer <bshaffer@centresource.com>
 * @license See LICENSE that came packaged with this software
 */
class BasecsSettings
{
  static  $cache = false;
    
  static function getDefaultUploadPath()
  {
    return 'uploads/setting';
  }

  static function isAuthenticated($user = null)
  {
    if (!$user)
    {
      $user = sfContext::getInstance()->getUser();
    }

    $authMethod = sfConfig::get('app_csSettingsPlugin_authMethod');
    $authCredential = sfConfig::get('app_csSettingsPlugin_authCredential');

    $hasAccess = false;
    if ($authMethod)
    {
      $hasAccess = $user->$authMethod();
    }
    if (!$hasAccess && $authCredential)
    {
      $hasAccess = $user->hasCredential($authCredential);
    }

    return $hasAccess;
  }

  /**
   * get
   * Returns the string value of a particular setting.
   *
   * @param string $setting
   * @static
   * @access public
   * @return string
   */
  static function get($setting, $default = null)
  {
    // Pull from cached settings array
    $cache = self::buildCache('settings_array');
    
    if($cache->has('settings_array_'.$setting))
    {
      return unserialize($cache->get('settings_array_'.$setting));
    }

    //Look in app.ymls for setting
    return sfConfig::get('app_'.self::settingize($setting), $default);
  }
  
  static function buildCache($type)
  {
    $cache_handler = self::getCache();

    if(!$cache_handler->get($type.'_cs_cache_is_built', false))
    {
      foreach (Doctrine::getTable('csSetting')->findAll() as $setting)
      {
        if($type == 'settings_array')
        {
          $cache_handler->set($type.'_'.$setting['slug'], serialize($setting->getValue()));
          $cache_handler->set($type.'_'.$setting['name'], serialize($setting->getValue()));
        }
        elseif( $type == 'settings_object')
        {
          $cache_handler->set($type.'_'.$setting['slug'], serialize($setting->toArray()));
          $cache_handler->set($type.'_'.$setting['name'], serialize($setting->toArray()));
        }
      }
      
      $cache_handler->set($type.'_cs_cache_is_built', true);
    }
    
    return $cache_handler;
  }

  /**
   * getSetting
   * pulls the csSetting object for a given setting
   *
   * @param string $setting
   * @static
   * @access public
   * @return object csSetting
   */
  static function getSetting($setting)
  {

    if(strlen(trim($setting)) == 0)
    {
      throw new sfException('[f6Settings::getSetting] invalid name');
    }
    
    $cache = self::buildCache('settings_object');
    
    if ($cache->has('settings_object_'.$setting))
    {
      $ret = new csSetting();
      $ret->fromArray(unserialize($cache->get('settings_object_'.$setting, serialize(array()))));
      
      $value = unserialize($cache->get('settings_object_'.$setting));
      if(!is_array($value))
      {
        return null;
      }
      
      $ret->toArray($value);
      $ret->assignIdentifier($value['id']);
      
      return $ret;
    }

    return null;
  }

  static public function clearSettingsCache()
  {
    
    self::getCache()->clean(); 
  }

  static public function getCache()
  {
    if(!self::$cache)
    {
      $cache_settings = sfConfig::get('app_csSettingsPlugin_cache', array(
        'class'   => 'sfNoCache',
        'options' => array()
      ));
      
      $class    = $cache_settings['class'];
      $options  = $cache_settings['options'];
            
      self::$cache = new $class($options);
    }
    
    return self::$cache;
  }

  static public function settingize($anystring)
  {
    return str_replace('-', '_', Doctrine_Inflector::urlize(trim($anystring)));
  }
}
