<?php
/*
Plugin Name: SearchIntegrate
Plugin URI: http://searchintegrate.com/
Description: The easy integration for your WP monetization. Be sure to <a href="options-general.php?page=searchintegrate.php">configure</a> your plug-in.
Version: 1.0.1
Author: Tozzato-Johnson-Rabinowitz
Author URI: http://searchintegrate.com/
*/

define('SEARCHINTEGRATE_VERSION', '2.0.1');
//define('SI_SERVER', 'http://localhost:3030');
define('SI_SERVER', 'http://wp.searchintegrate.com');

function searchintegrate_css(){
  echo "<link type=\"text/css\" rel=\"stylesheet\" href=\"./wp-content/plugins/searchintegrate/searchintegrate.css\">";
}

add_action('wp_head', 'searchintegrate_css');

function searchintegrate(){
  $search = get_query_var('s');
  if ($search){
    $wp = md5(get_option('home'));
    echo "<script type=\"text/javascript\" charset=\"utf-8\" src=\"".SI_SERVER."/search.js?q={$search}\"></script>";
    echo "
      <script type=\"text/javascript\" charset=\"utf-8\">
        var content = document.getElementById('content');
        if (typeof(si_content)!='undefined'){
          content.innerHTML += '<p class=\"si_header\"><em>{$search}</em> results from searchintegrate.com</p>' 
          + si_content;
        }
      </script>
    ";
  }
}

function searchintegrate_admin(){
  add_options_page('Search Integrate', 'Search Integrate', 10, basename(__FILE__), 'searchintegrate_admin_panel');
}

function searchintegrate_admin_panel(){
?>

<div class="wrap">
  <h2>Search Integrate Configuration</h2>
  <table border="0" cellspacing="5" cellpadding="5">
    <tr>
      <td>Integration ID:</td>
      <td><?php echo md5(get_option('home')); ?></td>
    </tr>
    <tr>
      <td>WP Home:</td>
      <td><?php form_option('home'); ?></td>
    </tr>
    <tr>
      <td>Integration Status:</td>
      <td id='integration_status'>
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
    <tr>
      <td colspan='2'>
      <br />
      Search Integrate will use your <strong>integration id</strong> to identify your blog: you don't need to worry about anything.
      <br />
      Click HERE If your Integration Status is <strong>Not Active</strong>, otherwise you're good to go!
      </td>
    </tr>
    <tr>
      <td colspan='2'>
      <br />
      Our plug-in will display relevant ads appending results to the standard <em>content</em> tag: this should assure complete compatibility
      with any WP template. Please not that our search engine will expect to receive requests for this blog from this page: 
      <strong><?php form_option('home'); ?></strong> otherwise, for the security of your account, it will not respond.
      </td>
    </tr>
  </table>
  
  <? 
  $wp = md5(get_option('home'));
  echo "<script type=\"text/javascript\" charset=\"utf-8\" src=\"".SI_SERVER."/ping.js\"></script>";
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
     else
       integration_status.innerHTML = "<img src='../wp-content/plugins/searchintegrate/no.gif'> Not  Active";
   </script>
</div>

<?}

add_action('wp_footer', 'searchintegrate');
add_action('admin_menu', 'searchintegrate_admin');

?>