<?php
/**
 * Plugin Name: WP User Themes
 * Plugin URI:  http://github.com/ohmybrew/wp-user-themes
 * Description: Allows users pick a theme in their profile section
 * Version:     1.0
 * Author:      Tyler King
 * Author URI:  http://github.com/ohmybrew
 * License:     MIT
 */
 # https://codex.wordpress.org/Plugin_API/Filter_Reference#Blog_Information_and_Option_Filters

add_filter('option_stylesheet', 'wp_user_themes');
add_filter('option_template', 'wp_user_themes');
add_action('show_user_profile', 'wp_user_themes_profile');
add_action('edit_user_profile', 'wp_user_themes_profile');
add_action('personal_options_update', 'wp_user_themes_profile_update');
add_action('edit_user_profile_update', 'wp_user_themes_profile_update');

function wp_user_themes($value) {
  $user   = get_current_user_id();
  $option = get_user_meta($user, 'user_theme', true);

  if ($user > 0 && ! empty($option)) {
    return $option;
  }
  
  return $value;
}

function wp_user_themes_profile($user) {
  $themes = wp_get_themes();
  $option = get_user_meta($user, 'user_theme', true);
  $user_theme = ! empty($option) ? $option : get_option('template');
  ?>
  <h3>Theme</h3>

  <table class="form-table">
    <tr>
      <th><label for="user_theme">Choices</label></th>
      <td>
        <select name="user_theme">
          <?php foreach($themes as $theme) { ?>
            <option value="<?php print $theme->get_template(); ?>" <?php if ($theme->get_template() == $user_theme) { ?>selected<?php } ?>><?php print $theme->get('Name'); ?> (<?php print $theme->get_template(); ?>)</option>
          <?php } ?>
        </select>
        <span class="description">Choose a theme you wish to use.</span>
      </td>
    </tr>
  </table>
  <?php
}

function wp_user_themes_profile_update($user) {
  if (! current_user_can('edit_user', $user)) {
    return false;
  }

  if (array_key_exists('user_theme', $_POST)) {
    update_user_meta($user, 'user_theme', $_POST['user_theme']);
  }
}
