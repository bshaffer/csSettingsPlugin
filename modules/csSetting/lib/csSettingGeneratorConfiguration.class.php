<?php

/**
 * news module helper.
 *
 * @package    ./
 * @subpackage news
 * @author     Your name here
 * @version    SVN: $Id: helper.php 12474 2008-10-31 10:41:27Z fabien $
 */
class csSettingGeneratorConfiguration extends BaseCsSettingGeneratorConfiguration
{
  public function getTableMethod()
  {
    return 'findSettingsByGroup';
  }
}
