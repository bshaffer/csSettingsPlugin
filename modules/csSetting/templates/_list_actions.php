<li class='sf_admin_action_save_settings'>
  <input type="submit" name="submit" value="Save Settings">
</li>
<?php if (csSettings::isAuthenticated($sf_user)): ?>
  <?php echo $helper->linkToNew(array(  'params' =>   array(  ),  'class_suffix' => 'new',  'label' => 'New',)) ?>
<?php endif ?>
<li class='sf_admin_action_restore_all_defaults'>
  <?php echo link_to('Restore All Defaults', '@cs_setting_restore_all_defaults', array('confirm' => 'Are you sure?')) ?>
</li>

