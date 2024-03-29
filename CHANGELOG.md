# Changelog

All notable changes to this project will be documented in this file.

## Changelog

### v4.0.9 - 2023-05-25

- Changed: Improve release plugin workflow.

### v4.0.8 - 2022-10-17

- Fixed: Fix setting json path is not working.

### v4.0.7 - 2022-09-07

- Fixed: Fix conflict class names.
- Fixed: Remove PHP warning notices.

### v4.0.6 - 2022-08-25

- Fixed: Fix issue return wrong SVG path.

### v4.0.5 - 2022-08-24

- Fixed: Check icon multicolor before output SVG.

### v4.0.4 - 2022-08-23

- Fixed: Fix issue Uncaught TypeError: Cannot access offset of type string on string in
  acf-icomoon/includes/fields/acf-field-icomoon.php:146.

### v4.0.3 - 2022-08-23

- Return SVG: Support multiColor.
- Return multiple types: icon class, SVG, Array, HTML.

### v4.0.2 - 2022-05-10

- Fixed: compatible with Gutenberg blocks

### v4.0.1 - 2022-04-22

- Fixed: wrong output on ACF field.

### v4.0.0 - 2022-04-21

- New: Mass upgrade with VueJS and a brand-new layout.
- Changed: Refactor HTML, CSS, JS
- New: Search icons inside popup

### v3.3 - 2021-09-11

- Fixed: Error when activate plugin without WPBakery Page Builder installed
- Added: Notice when Advanced Custom Fields is not activated

### v3.2 - 2021-09-03

- Revise and cleanup code to publish plugin
- Fixed: Custom selection.json path is not working
- New: Add WPBakery Page Builder Icomoon Param Type for developers

### v3.1 - 2021-08-24

- Add selected icon using PHP instead of JS.

### v3.0 - 2021-08-03

- Fix bug: not re-init script on dynamic elements (after duplicating or in ACF tabs)
- Refactor js

### v2.2 - 2021-06-08

- Update CSS for VC Param
- Exit function when element is not visible

### v2.0.1 - 2021-01-28

- Fix popup not show when add more item in ACF repeater (due to ACF always has a pre-appended clone item)

### v2.0.0 - 2021-01-11

- Re-build the whole plugin to use the same structure with VC Param Icomoon
- Check popup position left/right
- Update UI

### v1.0.5 - 2020-12-31

- Update UI

### v1.0.4 - 2020-12-25

- Check array key exists to prevent PHP Warning

### v1.0.3 - 2020-08-12

- Replace get_stylesheet_directory_uri with get_stylesheet_directory to get the file path
- Add conditional logic file_exists in $path variable

### v1.0.2 - 2020-08-05

- Check file exists before get file content.
- Replace get_template_directory_uri with get_stylesheet_directory_uri.

### v1.0.1 - 2020-06-04

- Auto get selection.json path if value is empty

### v1.0.0 - 2020-05-20

- Remove all fields that have not worked yet.
- Add option to return icon class.
- Auto get selection.json path.
- Fix error when using with ACF repeater.