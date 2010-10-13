# Symfony Doctrine Settings Plugin #

Much like the `` sfConfig::get() `` method, use `` csSettings::get() `` to call dynamic, user-defined settings.  
An admin generator interface allows easy administration.

All settings are cached when first loaded, and the cache is refreshed whenever a
setting is changed or added.

After the settings have been loaded, retrieve them with `` csSettings::get() `` 
(returns a string value) or `` csSettings::getSetting() `` (returns a csSetting object)

## Usage ##

To create a setting, you can add one in using a fixture, or using the DIY admin module interface (see below)
    
    [yaml]
    # in YAML fixture:
    csSetting:
      test_setting:
        name:             My Fake Setting
        type:             yesno                 # optional: defaults to 'input'
        setting_default:  'yes'                 # optional, defaults to empty string
        widget_options:   class=my-fake-setting # optional, more on this below

You can pull your setting into your project with the class method `` get ``.  This takes
either the name of your setting or the inflected name of your setting.

    [php]
    $value = csSettings::get('My Fake Setting');  // The name of your setting
    $value = csSettings::get('my_fake_setting');  // This will also work

You can also use csSettings to call settings in your app.yml.  By default, if the value passed
to the `` get `` method is null, `` sfConfig `` is called:

    [php]
    $value = csSettings::get('this_setting_does_not_exist'); 
    // If a csSetting does not exist with this name, this returns the same as:
    $value = sfConfig::get('app_this_setting_does_not_exist');
    // This also works:
    $value = csSettings::get('This Setting Does Not Exist');     

Alternatively, the entire `` csSetting `` object can be returned by calling the class method
`` getSetting ``:

    [php]
    $setting = csSettings::getSetting('my_fake_setting');

## Admin Module ##
An admin module also exists for managing the settings in the database. you must enable the module in order to access it

    [yaml]
    # /apps/myapp/config/settings.yml
    all:
      .settings:
         enabled_modules:        [default, csSettings]

This module can be used to edit the variables as the plugin was originally built with the value for the setting being on the 
list page and editable from there, or it can behave like a normal admin-generated page.
By default, you must be authenticated to edit the setting configurations themselves.  

If you want to prevent validated users from editing the setting configurations, you can
override the authMethod setting in your app.yml:

    [yaml]
    # /config/app.yml
    all:
      csSettingsPlugin:
         authMethod:        isSuperAdmin  # or whatever method you want called on myUser.  

## Fields ##
 * id
  * Used as the primary key
 * name
  * Used as the variable name
  * Must be unique
 * type
   * checkbox
   * input
   * textbox
   * yesno
   * select
   * model
   * upload
   * richtext (needs sfCKEditorPlugin to work. If this plugin is not installed, textarea will be displayed)
 * widget_options
   * checkbox / input / textbox / yesno - sets HTML attributes of the widget
   * select
     * used as the array of options to select from
   * model
     * used to determine the model to chose from. Option "model" is required.
   * upload
     * upload_path: not required, but can be used to determine upload path.
 * group
  * organize your settings into groups for improved usability
 * setting_default
  * add a default value to your setting, allowing the user to restore the default
    for a single setting, or restore all setting defaults.
  * if no value is set for your setting initially, this value will be used
 * value
  * The value of the setting

## Cache ##

Customize the cache handler for your settings using your app.yml.  By default, __sfNoCache__ is used.

  [yaml]
  # /config/app.yml
  all:
    csSettingsPlugin:
      cache:
        class: sfFileCache
        options:
          automatic_cleaning_factor: 0
          cache_dir:                 %SF_TEMPLATE_CACHE_DIR%
          lifetime:                  86400
          prefix:                    %SF_APP_DIR%/template



Please contact bshaffer@centresource.com for any questions or suggestions.