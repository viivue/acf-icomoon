<div align="center">

 <img width="100px" src="https://ps.w.org/acf-icomoon/assets/icon.svg" align="center" alt="Icomoon for Advanced Custom Fields" />

 <h1 align="center" style="border:none; padding:0;">IcoMoon for Advanced Custom Fields</h1>

 <p align="center">A new ACF field that allows to select icon from <a href="https://icomoon.io" target="_blank">IcoMoon</a> json.</p>

 <p align="center">
   <a href="https://github.com/viivue/acf-icomoon/releases/latest">
   <img src="https://badgen.net/github/release/viivue/acf-icomoon/?cache=600">
   </a><a href="https://wordpress.org/plugins/acf-icomoon/">
   <img src="https://img.shields.io/badge/-WordPress-0273A9">
   </a>
    <a href="https://icomoon.io">
   <img src="https://img.shields.io/badge/-IcoMoon-29C3F9">
   </a>
    <a href="https://www.advancedcustomfields.com/">
   <img src="https://img.shields.io/badge/-Advanced Custom Fields-347C39">
   </a>
 </p>

</div>

## Installation

1. Search for "Support For Icomoon with Advanced Custom Fields" from your CMS Admin > Plugins page, or download the zip
   file
   from [WordPress Plugin Repository](https://wordpress.org/plugins/support-for-icomoon-with-advanced-custom-fields/)
   or [releases](https://github.com/viivue/acf-icomoon/releases).
2. Upload the plugin to your `wp-content/plugins` directory.
3. Activate the plugin in the CMS admin plugins list.
4. Go to Field Groups > Create new Field and choose "Icomoon" Type in Content Group.
   ![Screenshot 3](https://ps.w.org/acf-icomoon/assets/screenshot-3.png)

5. How it looks in the page
   ![Screenshot 1](https://ps.w.org/acf-icomoon/assets/screenshot-1.png)
   ![Screenshot 2](https://ps.w.org/acf-icomoon/assets/screenshot-2.png)

## Usage

### Setting Up the IcoMoon Field

1. **Create a Field Group**: In the WordPress admin, navigate to Custom Fields > Field Groups and create a new field
   group.
2. **Add IcoMoon Field**: Add a new field and select "Icomoon" as the field type.
3. **Configure Field Settings**:
    - **Selection.json**: Input the path of `selection.json` file from IcoMoon and ensure this file is uploaded to your
      theme or plugin directory. Leave empty to use the plugin's selection.json.
    - **Return value**: Choose the desired return format (`Icon element`, `Icon class`, `SVG element` or `Array`).
4. Assign the field group to the desired post type or page.

### Enqueuing IcoMoon Assets for Frontend Output

To display the selected IcoMoon icon on the frontend, you need to enqueue the IcoMoon font files and styles in your
theme. Below is an example of how to properly enqueue the IcoMoon assets and output the selected icon.

#### Step 1: Enqueue IcoMoon Styles and Fonts

Add the following code to your theme's `functions.php` file to enqueue the IcoMoon stylesheet and font files.

```php
// Enqueue IcoMoon assets (should be in functions.php)
add_action( 'wp_enqueue_scripts', 'vii_acf_icomoon_enqueue_styles' );
function vii_acf_icomoon_enqueue_styles() {
    wp_enqueue_style( 'vii-icomoon', get_template_directory_uri() . '/assets/css/style.css', array(), '1.0.0' );
}
```

**Notes**:

- Replace `/assets/css/style.css` with the actual path to your IcoMoon `style.css` file.
- Ensure the font files referenced in `style.css` are correctly placed in the specified directory (e.g.,
  `/assets/fonts/`).
- The IcoMoon `style.css` typically includes the `@font-face` rule to load the font files.

#### Step 2: Output the Selected Icon on the Frontend

Use the ACF `get_field()` function to retrieve the selected icon and display it in your template. The output depends on
the return format configured in the field settings.

**Example 1: Return value Set to `Icon class`**

If the return value is set to `Icon class`, the field returns the icon's CSS class (e.g., `icon-home`).

```php
<?php
$icon_class = get_field( 'icon' ); // Field name: 'icon'
if ( $icon_class ) {
    echo '<i class="' . esc_attr( $icon_class ) . '"></i>';
}
?>
```

**Example 2: Return value Set to `Icon element`/`SVG element`**

If the return value is set to `Icon element` or `SVG element`, the field returns the full icon HTML (e.g.,
`<i class="icon-home"></i>` for Icon element and the full SVG code for SVG element).

```php
<?php
$icon_html = get_field( 'icon' );
if ( $icon_html ) {
    echo esc_html($icon_html);
}
?>
```

**Example 3: Return value Set to `Array`**

If the return value is set to `Array`, the field returns an array containing the full data of an icon.

```php
<?php
$icon = get_field( 'icon' );
if ( $icon && is_array( $icon ) ) {
    var_dump($icon); // dump the icon array for debugging, you can use it to get the icon name, class, and SVG code
}
?>
```

**Notes**:

- Always use `esc_attr()` or `esc_html()` to sanitize output for security.
- Ensure the IcoMoon font family name (e.g., `icomoon`) matches the one defined in your `style.css`.
- The `<i>` tag is commonly used for IcoMoon icons, but you can use any HTML element as long as the correct CSS class or
  font-family is applied.

### Example Template Integration

Here’s an example of how you might integrate the IcoMoon icon in a WordPress template (e.g., `single.php`):

```php
<?php
// Enqueue IcoMoon assets (should be in functions.php)
add_action( 'wp_enqueue_scripts', 'vii_acf_icomoon_enqueue_styles' );
function vii_acf_icomoon_enqueue_styles() {
    wp_enqueue_style( 'vii-icomoon', get_template_directory_uri() . '/assets/css/style.css', array(), '1.0.0' );
}

// In your template file
$icon_class = get_field( 'icon' );
if ( $icon_class ) {
    ?>
    <div class="icon-container">
        <i class="<?php echo esc_attr( $icon_class ); ?>"></i>
    </div>
    <?php
}
?>
```

Or you can use the icomoon set from plugin by using the following code:

```php
<?php
// Enqueue IcoMoon assets (should be in functions.php)
add_action( 'wp_enqueue_scripts', 'vii_acf_icomoon_enqueue_styles' );
function vii_acf_icomoon_enqueue_styles() {
    wp_enqueue_style( 'vii-icomoon', ACFICOMOON_STYLESHEET_DIR . '/assets/css/icomoon.css', array(), '1.0.0' );
}
```

### Troubleshooting

- **Icons Not Displaying**: Ensure the `selection.json` file path is correct, font files are accessible, and the
  `style.css` is properly enqueued.
- **404 Errors for Font Files**: Verify the font file paths in `style.css` match their actual location in your theme
  directory.
- **Incorrect Font Family**: Check that the font-family name in your CSS matches the one defined in IcoMoon’s
  `style.css`.

## Compatibility

- **ACF**: Compatible with ACF 5.7+.
- **WordPress**: Tested up to WordPress 6.6.2.
- **PHP**: Requires PHP 7.2 or higher.

## Changelog

A complete listing of all notable changes to Support For Icomoon with Advanced Custom Fields is documented
in [CHANGELOG.md](CHANGELOG.md).

## Contributing

Contributions are welcome! Please submit a Pull Request or open an issue
on [GitHub](https://github.com/viivue/acf-icomoon).

## License

This project is licensed under the GPL v2 or later - see the [LICENSE](LICENSE) file for details.
