<?php use_helper('Form') ?>
<?php use_helper('Object') ?>
<?php 
$type = $cs_setting->getType();
$name = isset($name) ? $name:'cs_setting['.$cs_setting['id'].']';

switch($type)
{
  case 'checkbox':
    echo input_hidden_tag($name, 0);
    echo checkbox_tag($name, 1, $cs_setting->getValue());
  break;

  case 'input':
    echo input_tag($name, $cs_setting->getValue(), 'size=55');
  break;

  case 'textarea':
    echo textarea_tag($name, $cs_setting->getValue());
  break;

  case 'yesno':
    echo 'Yes: '.radiobutton_tag($name, 1, $cs_setting->getValue());
    echo 'No: '.radiobutton_tag($name, 0, $cs_setting->getValue() ? false:true);
  break;

  case 'select':
    $options = _parse_attributes($cs_setting->getOptions());
    echo select_tag($name, options_for_select($options, $cs_setting->getValue(), 'include_blank=true'));
  break;

  case 'model':
    $config = _parse_attributes($cs_setting->getOptions());
    $method = $cs_setting->getOption('table_method');
    $method = $method ? $method : 'findAll';
    $options = Doctrine::getTable($cs_setting->getOption('model', true))->$method(); 
    echo select_tag($name, objects_for_select($options, 'getId', '__toString', $cs_setting->getValue()), 'include_blank=true');
  break;

  case 'wysiwyg':
    echo textarea_tag($name, $cs_setting->getvalue(), 'rich=true '.$cs_setting->getOptions());
  break;

  case 'upload':
    echo $cs_setting->getValue() ? link_to($cs_setting->getValue(), public_path('uploads/setting/'.$cs_setting->getValue())) .'<br />' : '';
    echo input_file_tag($name, $cs_setting->getValue(), $cs_setting->getOptions());
  break;

  default:
    echo input_tag($name, $cs_setting->getValue(), 'size=55');
  break;
}
