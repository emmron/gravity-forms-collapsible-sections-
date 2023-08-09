<?php
/**
 * Plugin Name: Gravity Forms Collapsible Sections
 * Description: A plugin to add collapsible sections in Gravity Forms with additional features like images and scroll control.
 * Version: 1.0
 * Author: Your Name
 * Author URI: http://yourwebsite.com
 */

// Hook for adding admin menus, scripts, styles, and other initialization code goes here

GFForms::include_addon_framework();

class GF_Collapsible_Section_Start extends GF_Field {
    public $type = 'section_start';

    public function get_form_editor_field_title() {
        return esc_attr__('Section Start', 'gravityforms');
    }

    public function get_form_editor_field_settings() {
        return array('image_setting');
    }

    
public function get_field_input($form, $value = '', $entry = null) {
    $image_url = $this->image_setting ? esc_url($this->image_setting) : '';
    $image_html = $image_url ? '<img src="' . $image_url . '" alt="Section Image" />' : '';
    $section_title = $this->label ? esc_html($this->label) : 'Section Start';
    return '<div class="gf-section-start">' . $image_html . '<h3>' . $section_title . '</h3></div>';
}

}
GF_Fields::register(new GF_Collapsible_Section_Start());

class GF_Collapsible_Section_End extends GF_Field {
    public $type = 'section_end';

    public function get_form_editor_field_title() {
        return esc_attr__('Section End', 'gravityforms');
    }

    
public function get_field_input($form, $value = '', $entry = null) {
    $image_url = $this->image_setting ? esc_url($this->image_setting) : '';
    $image_html = $image_url ? '<img src="' . $image_url . '" alt="Section Image" />' : '';
    $section_title = $this->label ? esc_html($this->label) : 'Section Start';
    return '<div class="gf-section-start">' . $image_html . '<h3>' . $section_title . '</h3></div>';
}

}
GF_Fields::register(new GF_Collapsible_Section_End());

// Registering image setting
add_action('gform_field_standard_settings', 'add_image_setting', 10, 2);
function add_image_setting($position, $form_id) {
    if ($position == 25) {
        ?>
        <li class="image_setting field_setting">
            <label for="field_image">
                <?php esc_html_e('Section Image', 'gravityforms'); ?>
                <input type="text" id="field_image" class="fieldwidth-3" size="35" />
            </label>
        </li>
        <?php
    }
}

// Hook for adding admin menus
add_action('admin_menu', 'gf_collapsible_sections_menu');

// Action for admin menu
function gf_collapsible_sections_menu() {
    add_menu_page('Gravity Forms Collapsible Sections', 'Collapsible Sections', 'manage_options', 'gf_collapsible_sections', 'gf_collapsible_sections_page');
}

// Function to display the plugin admin page
function gf_collapsible_sections_page() {
    ?>
    <div class="wrap">
        <h2>Gravity Forms Collapsible Sections</h2>
        <form method="post" action="options.php">
            <?php settings_fields('gf_collapsible_sections_settings'); ?>
            <?php do_settings_sections('gf_collapsible_sections'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}


add_settings_section('gf_collapsible_sections_scroll_section', 'Scroll Settings', 'gf_collapsible_sections_scroll_section_callback', 'gf_collapsible_sections');

// Callback function for scroll settings section description
function gf_collapsible_sections_scroll_section_callback() {
    echo '<p>Control how your sections should perform after it\'s opened or closed, including scrolling to settings with offsets.</p>';
}

add_action('admin_init', 'gf_collapsible_sections_register_settings');
function gf_collapsible_sections_register_settings() {
    register_setting('gf_collapsible_sections_settings', 'gf_collapsible_sections_scroll_offset');
    add_settings_section('gf_collapsible_sections_scroll_section', 'Scroll Settings', null, 'gf_collapsible_sections');
    add_settings_field('gf_collapsible_sections_scroll_offset', 'Scroll Offset', 'gf_collapsible_sections_scroll_offset_callback', 'gf_collapsible_sections', 'gf_collapsible_sections_scroll_section');
}

// Callback function for scroll offset setting
function gf_collapsible_sections_scroll_offset_callback() {
    $scroll_offset = get_option('gf_collapsible_sections_scroll_offset');
    echo '<input type="text" name="gf_collapsible_sections_scroll_offset" value="' . esc_attr($scroll_offset) . '" />';
}


add_settings_section('gf_collapsible_sections_default_open_section', 'Default Open Sections', 'gf_collapsible_sections_default_open_section_callback', 'gf_collapsible_sections');

// Callback function for default open sections settings section description
function gf_collapsible_sections_default_open_section_callback() {
    echo '<p>Decide which sections should be open by default when your form loads.</p>';
}

// Updating the default open sections field to use the new section
add_settings_field('gf_collapsible_sections_default_open', 'Default Open Sections', 'gf_collapsible_sections_default_open_callback', 'gf_collapsible_sections', 'gf_collapsible_sections_default_open_section');

add_action('admin_init', 'gf_collapsible_sections_register_default_open_settings');
function gf_collapsible_sections_register_default_open_settings() {
    register_setting('gf_collapsible_sections_settings', 'gf_collapsible_sections_default_open');
    add_settings_field('gf_collapsible_sections_default_open', 'Default Open Sections', 'gf_collapsible_sections_default_open_callback', 'gf_collapsible_sections', 'gf_collapsible_sections_scroll_section');
}

// Callback function for default open sections setting
function gf_collapsible_sections_default_open_callback() {

// Enqueue scripts and styles
    wp_enqueue_script('gf-collapsible-sections-js', plugin_dir_url(__FILE__) . 'collapsible-sections.js', array('jquery'), '1.0.0', true);
    wp_enqueue_style('gf-collapsible-sections-css', plugin_dir_url(__FILE__) . 'collapsible-sections.css', array(), '1.0.0');


// Add action hook for enqueuing scripts and styles
add_action('wp_enqueue_scripts', 'gf_collapsible_sections_enqueue_scripts');
