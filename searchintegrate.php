<?php
/*
Plugin Name: SearchIntegrate
Plugin URI: http://searchintegrate.com/
Description: The easy integration for your WP monetization. Be sure to <a href="options-general.php?page=searchintegrate.php">configure</a> your plug-in.
Version: 1.0.1
Author: Tozzato-Johnson-Rabinowitz
Author URI: http://searchintegrate.com/
*/

define('SEARCHINTEGRATE_VERSION', '1.0.1');
define('SI_SERVER', 'http://localhost:3030');
//define('SI_SERVER', 'http://wp.searchintegrate.com');

function searchintegrate_css(){
  echo "
    <style type='text/css'>
      #searchintegrate_subheader{
        font-size: 11px;
        font-weight:bold;
        padding: 1px 1px 5px 0;
        text-align:left;
        border-bottom: 1px dotted #000;
        margin-bottom: 10px;
      }
      #searchintegrate_result{
        padding:0px 5px 5px 15px;
        margin-bottom: 10px;  
        clear: both;
        border-bottom: 1px solid #ccc;
      }
      #searchintegrate_title{
        font-size:13px;
        color: #ff6600;
        text-decoration:underline;
      }
      #searchintegrate_advertiser{
        font-size:11px;
        color:#330000;
        padding: 0 10px 0 0;
        text-decoration:none;
      }
      #searchintegrate_description{
        font-size:small;
        color: #000;
        text-decoration:none;
    }
    </style>
  ";
}

add_action('wp_head', 'searchintegrate_css');

function searchintegrate(){
  $search = get_query_var('s');
  if ($search){
    $wp = md5(get_option('home'));
    echo "<script type=\"text/javascript\" charset=\"utf-8\" src=\"".SI_SERVER."/search.js?q={$search}&wp={$wp}\"></script>";
    echo "
      <script type=\"text/javascript\" charset=\"utf-8\">
        var content = document.getElementById('content');
        if (typeof(si_content)!='undefined'){
          content.innerHTML += '<div id=\"searchintegrate\"><h4>&quot;{$search}&quot; results from searchintegrate.com</h4>' 
          + si_content
          + '</div>';
        } else {
          content.innerHTML += '<!-- Searchintegrate.com Error: the server did not provide a search result. Is your plug-in correctly configured? -->'
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
  echo "<script type=\"text/javascript\" charset=\"utf-8\" src=\"".SI_SERVER."/ping.js?wp={$wp}\"></script>";
  ?>
   <script type="text/javascript" charset="utf-8">
     var integration_status = document.getElementById('integration_status');
     if(typeof(wp_is_active) != 'undefined' && wp_is_active == true)
       integration_status.innerHTML = "<img src='../wp-content/plugins/searchintegrate/ok.gif'> Active";
     else
       integration_status.innerHTML = "<img src='../wp-content/plugins/searchintegrate/no.gif'> Not  Active";
   </script>
</div>

<?}

add_action('wp_footer', 'searchintegrate');
add_action('admin_menu', 'searchintegrate_admin');

?>