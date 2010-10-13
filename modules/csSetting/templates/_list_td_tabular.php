<td class="sf_admin_text sf_admin_list_td_name">
  <?php echo link_to($cs_setting->getName(), 'cs_setting_edit', $cs_setting) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_value">
  <?php if ($form[$cs_setting['slug']]->hasError()): ?>
    <?php echo $form[$cs_setting['slug']]->renderError() ?>
  <?php endif ?>
  <?php echo $form[$cs_setting['slug']] ?>
</td>