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

if (strrpos(get_bloginfo('wpurl'), 'localhost')){
  define('WPSI', 'http://localhost:3030');
  define('MYSI', 'http://localhost:3000');
} else {
  define('WPSI', 'http://wp.searchintegrate.com');
  define('MYSI', 'http://my.searchintegrate.com');
}

// ADMIN START //

add_action('admin_menu', 'searchintegrate_admin');
add_action('admin_menu', 'searchintegrate_css');

function searchintegrate_admin(){
  add_options_page('Search Integrate', 'Search Integrate', 10, basename(__FILE__), 'searchintegrate_admin_panel');
}

function searchintegrate_admin_panel(){
  if ($_POST['siwp_placement']){
    update_option('siwp_config', "{$_POST['siwp_placement']}|{$_POST['siwp_numresult']}|{$_POST['siwp_branding']}");
    echo '<div class="updated settings-error"><p><strong>Settings saved.</strong></p></div>';
  }
  if (!get_option('siwp_config')){ add_option('siwp_config', 'content|5|1'); }
  list($siwp_placement, $siwp_numresult, $siwp_branding) = explode("|", get_option('siwp_config'));
  $plugin_dir = get_bloginfo('wpurl').'/wp-content/plugins/'.dirname(plugin_basename(__FILE__));
  ?>

  <div id="siwp">
    <div id="siwp-header">
      <div id="siwp-logo">
        <img src="<?php echo $plugin_dir; ?>/images/siwp-header-logo.png" width="168" height="32" alt="Search Integrate" /></div>
        <div id="siwp-tagline">Add sponsored results to your wordpress search</div>
      </div><!-- siwp-header -->

      <div id="siwp-overview">
        <div id="status">
          <div id="version">
            <div class="indicate">
              <div class="light"><img src="<?php echo $plugin_dir; ?>/images/no.png" width="13" height="13" id="installed_version_img" /></div>
              <span id="installed_version"> -- </span>
            </div>
            <!-- indicate -->
          </div><!-- version -->

          <div class="siwp-clear"><br /></div>
          <div id="integration_status">
            <div class="indicate">
              <div class="light"><img src="<?php echo $plugin_dir; ?>/images/no.png" width="13" height="13" /></div>
              <strong>Account Status: not complete.</strong> Please complete
              the signup process below (required for payment).
            </div><!-- indicate -->
          </div><!-- activation -->
        </div><!-- status -->
        <div id="details">
          <span class="siwp-subhead">your unique Integration ID</span>
          <?php echo md5(get_bloginfo('wpurl')); ?>
          <div class="siwp-clear"><br /></div>
          <span class="siwp-subhead">Blog installation location:</span>
          <?php form_option('home'); ?>
        </div><!-- details -->
      </div><!-- siwp-overview -->

      <div class="siwp-clear"><br /></div>
      <form method="post" action="">
        <div class="siwp-section" id="options">
          <div class="sect-header"><div class="text">display options</div></div>
          <div class="container">
            <form id="options-form">
              <label>Where are your search results displayed?</label>
              <input type="text" name="siwp_placement" value="<?php echo $siwp_placement; ?>" />
              <div class="supporting">This is the name of CSS element where your search results are displayed.<br />
                <strong>For most themes, this is 'content' by default,</strong> so no change is usually needed.</div>
                <div class="siwp-divider"></div>
                <label>Number of sponsored results?</label>
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
                </select>
                <div class="supporting">These are the results added to your regular search results. <strong>We recommend 3 sponsored results.</strong></div>
                <div class="siwp-divider"></div>
                <label>Display Search Integrate logo on the search results page?</label>
                <input type="checkbox" name="siwp_branding" value="1" <?php if ($siwp_branding) echo 'checked'; ?>>
                <div class="supporting">This option puts a tiny Search Integrate logo on the search results page. No, it's not required.<br />But yes, we'd really appreciate it if you would leave this checked so others can learn about us.</div>

                <br />
                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
              </form>
            </div>
          </div> <!-- section - options -->

          <div class="siwp-section" id="analytics">
            <div class="sect-header"><div class="text">Search Analytics</div></div>
            <div class="container">
              <span class="cont-head">Top Searches:</span>
              <span id="top_queries"></span>
              <br /><br />
              <span class="cont-head">Recent Searches:</span>
              <span id="last_queries"></span>
              <br /><br />
              <span class="cont-head">Clickthrough Rate:
                (<a href="http://en.wikipedia.org/wiki/Clickthrough_rate" target="_new">Learn more</a>)</span>
              <span id="conversion"></span>
            </div>
          </div> <!-- section - analytics -->

          <div class="siwp-section" id="signup">
            <div class="sect-header"><div class="text">Create search integrate account</div></div>
            <div class="container" id="signup_content">
rrrr
            </div>

          </div> 
          <p>
          <!-- section - options -->

          <span class="legal">
            Search Integrate is a product of <a href="http://www.trueinteractive.net/">True Interactive</a>
            (www.trueinteractive.net). All right reserved.<br />
          </span>
          
          <span class="legal">Continued installation of this product indicates acceptance of our 
          <a href="#">terms of service</a>.</span></p>
        </div>
        <!-- siwp -->

      <?
      $signup = file_get_contents(dirname(__FILE__)."/signup.html");

      echo "<script type=\"text/javascript\" src=\"".WPSI."/ping.js\"></script>";

      echo "<script type=\"text/javascript\">
        var siwp_installed_version = '".SEARCHINTEGRATE_VERSION."';
      document.getElementById('siwp_numresult').value = {$siwp_numresult}
      </script>";

      echo "<script type=\"text/javascript\">

      if(typeof(wp_is_active) != 'undefined'){

        jQuery('#top_queries').html(wp_top_queries);
        jQuery('#last_queries').html(wp_last_queries);
        jQuery('#conversion').html(wp_conversion);
        jQuery('.optional').fadeIn();

        if (siwp_version == siwp_installed_version){
          jQuery('#installed_version_img').attr('src', '{$plugin_dir}/images/yes.png');
          jQuery('#installed_version').html('Plugin version: ' + siwp_version + ' - <strong>Up-to-date</strong>');
        } else {
          jQuery('#version').html('<img src=\"{$plugin_dir}/images/no.png\"> please update to V' + siwp_version);
        }

        if(wp_is_active == true){

          jQuery('#integration_status').html('<img src=\"{$plugin_dir}/images/yes.png\"> Active');

          if(wp_is_payable == true){
            jQuery('#integration_status').append('&nbsp;<img src=\"{$plugin_dir}/images/yes.png\"> Payable');
          } else {
            jQuery('#integration_status').append('&nbsp;<img src=\"{$plugin_dir}/images/no.png\"> Not Payable (EXPLAIN)');
          }

        } else {

          jQuery('#signup_content').html('{$signup}');
          jQuery('#integration_status').prepend('<img src=\"{$plugin_dir}/images/no.png\"> Not Created');
          jQuery('#account_form').attr('action', '".MYSI."/wp_account');
          jQuery('#siwp_email').attr('value', '".get_option('admin_email')."');
          jQuery('#siwp_title').attr('value', '".get_option('blogname')."');
          jQuery('#siwp_home').attr('value', '".get_bloginfo('wpurl')."');
          jQuery('#siwp_id').attr('value', '".md5(get_bloginfo('wpurl'))."');
          jQuery('#account_form_container').fadeIn();

        }
      } else {
        jQuery('#integration_status').html('Oops! Search Integrate is over-capacity. Thanks for noticing - we\'ll have everything back to normal soon.')
      }
      </script>";
    }

    // ADMIN END //

    add_action('wp_head', 'searchintegrate_css');
    add_action('wp_footer', 'searchintegrate');

    function searchintegrate_css(){
      $plugin_dir = get_bloginfo('wpurl').'/wp-content/plugins/'.dirname(plugin_basename(__FILE__));
      echo "<link type=\"text/css\" rel=\"stylesheet\" href=\"{$plugin_dir}/searchintegrate.css\">";
    }

    function searchintegrate(){
      $plugin_dir = get_bloginfo('wpurl').'/wp-content/plugins/'.dirname(plugin_basename(__FILE__));
      $search = get_query_var('s');
      if ($search){
        list($siwp_placement, $siwp_numresult, $siwp_branding) = explode("|", get_option('siwp_config'));
        echo "<script type=\"text/javascript\" src=\"".WPSI."/search.js?q={$search}&limit={$siwp_numresult}\"></script>";
        echo "<script type=\"text/javascript\">
        if (typeof(search_integrate_content) != 'undefined'){
          var content = document.getElementById('{$siwp_placement}');
          content.innerHTML = search_integrate_content + content.innerHTML;}
          </script>";
        }
        if ($siwp_branding!=1)
          echo "<script type=\"text/javascript\">document.getElementById('powered').style.display='none';</script>";
      }
      ?>