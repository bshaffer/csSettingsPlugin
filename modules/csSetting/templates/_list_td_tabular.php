<td class="sf_admin_text sf_admin_list_td_name">
  <?php if (csSettings::isAuthenticated($sf_user)): ?>
    <?php echo link_to($cs_setting->getName(), 'cs_setting_edit', $cs_setting) ?>
  <?php else: ?>
    <?php echo $cs_setting->getName() ?>
  <?php endif ?>
</td>
<td class="sf_admin_text sf_admin_list_td_value">
  <?php if ($form[$cs_setting['slug']]->hasError()): ?>
    <?php echo $form[$cs_setting['slug']]->renderError() ?>
  <?php endif ?>
  <?php echo $form[$cs_setting['slug']] ?>
</td>