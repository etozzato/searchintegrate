<?php
/*
Plugin Name: SearchIntegrate
Plugin URI: http://searchintegrate.com/
Description: The easy integration for your WP monetization. Be sure to <a href="options-general.php?page=searchintegrate.php">configure</a> your plug-in.
Version: 2.6
Author: Tozzato-Johnson-Rabinowitz
Author URI: http://searchintegrate.com/
*/

define('SEARCHINTEGRATE_VERSION', '2.6');

// define('WPSI', 'http://localhost:3030');
// define('MYSI', 'http://localhost:3000');
define('WPSI', 'http://wp.searchintegrate.com');
define('MYSI', 'http://my.searchintegrate.com');

function searchintegrate_css(){
  echo "<link type=\"text/css\" rel=\"stylesheet\" href=\"./wp-content/plugins/searchintegrate/searchintegrate.css\">";
}

add_action('wp_head', 'searchintegrate_css');

function searchintegrate(){
  $search = get_query_var('s');
  if ($search){
    $siwp_placement = get_option('siwp_placement');
    $siwp_numresult = get_option('siwp_numresult');
    $wp = md5(get_option('home'));
    echo "<script type=\"text/javascript\" charset=\"utf-8\" src=\"".WPSI."/search.js?q={$search}&limit={$siwp_numresult}\"></script>";
    echo "
      <script type=\"text/javascript\" charset=\"utf-8\">
        var content = document.getElementById('{$siwp_placement}');
        if (typeof(search_integrate_content)!='undefined'){
          content.innerHTML = '<div id=\"siwp_content\"><div id=\"siwp_header\"><span id=\"results\">Sponsored search results for: <strong>{$search}</strong></span><span id=\"powered\"><a href=\"http://www.searchintegrate.com\"><img src=\"wp-content/plugins/searchintegrate/search_integrate_logo.png\" alt=\"Search Integrate\" title=\"Search Integrate\"></a></span></div>' 
          + search_integrate_content + '</div>' +  content.innerHTML
        }
      </script>
    ";
  }
}

function searchintegrate_admin(){
  add_options_page('Search Integrate', 'Search Integrate', 10, basename(__FILE__), 'searchintegrate_admin_panel');
}

function searchintegrate_admin_panel(){
if ($_POST['siwp_placement']){
  update_option('siwp_placement', $_POST['siwp_placement']);
  update_option('siwp_numresult', $_POST['siwp_numresult']);
  echo '<div id="setting-error-settings_updated" class="updated settings-error"> 
  <p><strong>Settings saved.</strong></p></div>';
}
if (!get_option('siwp_placement')){ add_option('siwp_placement', 'content'); }
if (!get_option('siwp_numresult')){ add_option('siwp_numresult', 5); }
?>
<form method="post" action="">

<div class="wrap">

<div style="border: 1px dotted #000; background: #ffffeb; padding: 10px 30px; margin: 20px 0;">
  <h2>Search Integrate Configuration</h2>
	<br />
    	<strong>Name of CSS element where your search results are displayed</strong><br />
        <input type="text" name="siwp_placement" value="<?php echo get_option('siwp_placement'); ?>" /> -- This is usually "content" - no need to change for most themes
		
        
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

  <p>
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </form>
  </p>  
</div>
<script>
  siwp_numresult = document.getElementById('siwp_numresult')
  siwp_numresult.value = <? echo get_option('siwp_numresult'); ?>
</script>

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
      </td>
    </tr>

    <tr>
      <td>Top Queries:</td>
      <td id='top_queries' style='width:80%'>
      </td>
    </tr>
    <tr>
      <td>Last Queries:</td>
      <td id='last_queries'>
      </td>
    </tr>
    <tr>
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
    <tr><td colspan='2' style="border-bottom: 1px dotted #000;"></td></tr>
    <tr>
      <td colspan='2'>
      <br />
      The Search Integrate WordPress plugin (SIWP) displays relevant sponsored ads along side the default WordPress results. By default, these results are displayed in the <em>content</em> tag.
      <p>Please note that for the security of your account, our search engine will only respond to requests for this blog from this location: <strong><?php form_option('home'); ?></strong>.</p>
      </td>
    </tr>
  </table>
  
  <? 
  $wp = md5(get_option('home'));
  echo "<script type=\"text/javascript\" charset=\"utf-8\">siwp_installed_version = '".SEARCHINTEGRATE_VERSION."';</script>";
  echo "<script type=\"text/javascript\" charset=\"utf-8\" src=\"".WPSI."/ping.js\"></script>";
  ?>
   <script type="text/javascript" charset="utf-8">
     var integration_status = document.getElementById('integration_status');
     if(typeof(wp_is_active) != 'undefined' && wp_is_active == true){
       integration_status.innerHTML = "<img src='../wp-content/plugins/searchintegrate/ok.gif'> Active";
       var top_queries = document.getElementById('top_queries');
       top_queries.innerHTML = wp_top_queries;
       var last_queries = document.getElementById('last_queries');
       last_queries.innerHTML = wp_last_queries;
       var conversion = document.getElementById('conversion');
       conversion.innerHTML = wp_conversion;
     }
     else{
       integration_status.innerHTML = "<img src='../wp-content/plugins/searchintegrate/no.gif'> Not  Active <a href='<? echo MYSI ?>/dashboard/wp?integration_id=<?php echo md5(get_option('home')); ?>&wp_blogname=<?php echo get_option('blogname'); ?>&wp_home=<?php echo get_option('home'); ?>&wp_tagline=<? echo get_option('blogdescription'); ?>' target='_new'>Activate Now!</a> -- Account activation is required for publisher payment!";
     }
     var version = document.getElementById('version');
     if (siwp_version == siwp_installed_version){
       version.innerHTML = '<strong>up to date</strong>: v' + siwp_version + ' is installed.';
     } else {
       version.innerHTML = '<strong>update to v' + siwp_version + ' required </strong>: v' + siwp_installed_version + ' is installed.';
     }
   </script>
</div>

<?}

add_action('wp_footer', 'searchintegrate');
add_action('admin_menu', 'searchintegrate_admin');

?>