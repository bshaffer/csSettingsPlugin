<?php use_helper('I18N', 'Date') ?>
<?php include_partial('csSetting/assets') ?>

<?php $sf_response->addStylesheet('/csSettingsPlugin/css/cs_settings.css') ?>

<div id="sf_admin_container">
  <h1><?php echo __('Settings', array(), 'messages') ?></h1>

  <?php include_partial('csSetting/flashes') ?>

  <div id="sf_admin_header">
    <?php include_partial('csSetting/list_header', array('pager' => $pager)) ?>
  </div>


  <div id="sf_admin_content">
    <?php //use_helper('Form') ?>
    <?php echo form_tag('@cs_setting_save_all', array('multipart' => true)); ?>
    <?php if ($form->isCSRFProtected()) : ?>
      <?php echo $form['_csrf_token']->render(); ?>
    <?php endif; ?>
    <?php include_partial('csSetting/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper, 'form' => $form)) ?>
    <ul class="sf_admin_actions">
      <?php include_partial('csSetting/list_batch_actions', array('helper' => $helper)) ?>
      <?php include_partial('csSetting/list_actions', array('helper' => $helper)) ?>
    </ul>
    </form>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('csSetting/list_footer', array('pager' => $pager)) ?>
  </div>
</div>
