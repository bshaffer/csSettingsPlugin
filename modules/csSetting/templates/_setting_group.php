<div class="sf_admin_form_row sf_admin_text sf_admin_form_field_options">
  <div>
    <?php echo $form['setting_group']->renderLabel() ?>
    <?php echo $form['setting_group'] ?>
    <?php if (strpos($form['setting_group'], '</select>') !== false): ?>
      <br />
      <br />
      <label>New Group: </label>
      <input type="text" name="cs_setting[setting_group_new]" id="cs_setting_setting_group_new">
    <?php endif ?>      
    <div class='help'>
      <?php echo $form['setting_group']->renderHelp() ?>
    </div>
  </div>
</div>