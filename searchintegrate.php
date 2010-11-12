<?php
/*
Plugin Name: SearchIntegrate
Plugin URI: http://searchintegrate.com/
Description: The easy integration for your WP monetization. Be sure to <a href="options-general.php?page=searchintegrate.php">CONFIGURE</a> your plug-in.
Version: 2.6
Author: Tozzato-Johnson-Rabinowitz
Author URI: http://searchintegrate.com/
*/

define('SEARCHINTEGRATE_VERSION', '2.6');

// define('WPSI', 'http://localhost:3030');
// define('MYSI', 'http://localhost:3000');
define('WPSI', 'http://wp.searchintegrate.com');
define('MYSI', 'http://my.searchintegrate.com');

// ADMIN START //

add_action('admin_menu', 'searchintegrate_admin');

function searchintegrate_admin(){
  add_options_page('Search Integrate', 'Search Integrate', 10, basename(__FILE__), 'searchintegrate_admin_panel');
}

function searchintegrate_admin_panel(){
if ($_POST['siwp_placement']){
  update_option('siwp_config', "{$_POST['siwp_placement']}|{$_POST['siwp_numresult']}");
  echo '<div class="updated settings-error"><p><strong>Settings saved.</strong></p></div>';
}
if (!get_option('siwp_config')){ add_option('siwp_config', 'content|5'); }
list($siwp_placement, $siwp_numresult) = explode("|", get_option('siwp_config'));
$plugin_dir = get_settings('home').'/wp-content/plugins/'.dirname(plugin_basename(__FILE__));
?>
<div class="wrap">
  <div style="border: 1px dotted #000; background: #ffffeb; padding: 20px; margin: 20px;">
    <form method="post" action="">
    <h2>Search Integrate Configuration</h2>
    <br />
    <strong>Name of CSS element where your search results are displayed</strong><br />
    <input type="text" name="siwp_placement" value="<?php echo $siwp_placement; ?>" />
    -- This is usually "content" - no need to change for most themes
    <br /><br />
    <strong>Number of sponsored results to display</strong><br />
    <select id='siwp_numresult' name='siwp_numresult'>
      <option value='1'>1</option>
      <option value='2'>2</option>
      <option value='3'>3</option>
      <option value='4'>4</option>
      <option value='5'>5</option>
      <option value='6'>6</option>
      <option value='7'>7</option>
      <option value='8'>8</option>
      <option value='9'>9</option>
      <option value='10'>10</option>
    </select> -- Recommend: 3 results
    <br /><br />
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
  </form>
  </div>

  <table border="0" cellspacing="5" cellpadding="5">
    <tr>
      <td>Integration ID:</td>
      <td><?php echo md5(get_option('home')); ?> (your unique SIWP id code)</td>
    </tr>
    <tr>
      <td>Where this blog is installed:</td>
      <td><?php form_option('home'); ?></td>
    </tr>
    <tr>
      <td>Plugin Version:</td>
      <td id='version'>
        -
      </td>
    </tr>
    <tr class='optional' style='display:none'>
      <td>Top Queries:</td>
      <td id='top_queries' style='width:80%'>
      </td>
    </tr>
    <tr class='optional' style='display:none'>
      <td>Last Queries:</td>
      <td id='last_queries'>
      </td>
    </tr>
    <tr class='optional' style='display:none'>
      <td>Conversion Rate:</td>
      <td id='conversion'>
      </td>
    </tr>
    <tr><td colspan='2' style="border-bottom: 1px dotted #000;"></td></tr>
    <tr>
      <td valign="top"><strong>Account Status:</strong></td>
      <td id='integration_status'>
      </td>
    </tr>
    <tr>
      <td colspan='2' style="border-bottom: 1px dotted #000;"></td></tr>
    <tr>
      <td colspan='2'>
        <br />
        The Search Integrate WordPress plugin (SIWP) displays relevant sponsored ads along side the default WordPress results.
        By default, these results are displayed in the <em>content</em> tag.
        <br />
        Please note that for the security of your account, our search engine will only respond to requests for this blog
        from this location: <strong><?php form_option('home'); ?></strong>.
      </td>
    </tr>
  </table>
</div>

  <?
  $activation_link = "<a href=\"".MYSI."/dashboard/wp?integration_id="
                      .md5(get_option('home'))."&wp_blogname="
                      .get_option('blogname')."&wp_home="
                      .get_option('home')."&wp_tagline="
                      .get_option('blogdescription')
                      ."\" target=\"_new\">Activate Now!</a> -- Account activation is required for publisher payment!";
  echo "<script type=\"text/javascript\" src=\"".WPSI."/ping.js\"></script>";
  echo "<script type=\"text/javascript\">
          var siwp_installed_version = '".SEARCHINTEGRATE_VERSION."';
          document.getElementById('siwp_numresult').value = {$siwp_numresult}
        </script>";
  echo "<script type=\"text/javascript\">
          if(typeof(wp_is_active) != 'undefined' && wp_is_active == true){
            jQuery('#integration_status').html('<img src=\"{$plugin_dir}/ok.gif\"> Active');
            jQuery('#top_queries').html(wp_top_queries);
            jQuery('#last_queries').html(wp_last_queries);
            jQuery('#conversion').html(wp_conversion);
            jQuery('.optional').fadeIn();} 
          else{
            jQuery('#integration_status').html('<img src=\"{$plugin_dir}/no.gif\"> Not Active {$activation_link}');}
          if (siwp_version == siwp_installed_version){
            jQuery('#version').html('current: v' + siwp_version + ' is installed.');}
          else {
            jQuery('#version').html('update to v' + siwp_version + ' required: v' + siwp_installed_version + ' is installed.');}
        </script>";
}

// ADMIN END //

add_action('wp_head', 'searchintegrate_css');
add_action('wp_footer', 'searchintegrate');

function searchintegrate_css(){
  $plugin_dir = get_settings('home').'/wp-content/plugins/'.dirname(plugin_basename(__FILE__));
  echo "<link type=\"text/css\" rel=\"stylesheet\" href=\"{$plugin_dir}/searchintegrate.css\">";
}

function searchintegrate(){
  $plugin_dir = get_settings('home').'/wp-content/plugins/'.dirname(plugin_basename(__FILE__));
  $search = get_query_var('s');
  if ($search){
    list($siwp_placement, $siwp_numresult) = explode("|", get_option('siwp_config'));
    echo "<script type=\"text/javascript\" src=\"".WPSI."/search.js?q={$search}&limit={$siwp_numresult}\"></script>";
    echo "<script type=\"text/javascript\">
          if (typeof(search_integrate_content) != 'undefined'){
          var content = document.getElementById('{$siwp_placement}');
          content.innerHTML = search_integrate_content + content.innerHTML;}
          </script>";
  }
}
?>