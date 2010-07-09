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

function searchintegrate_css(){
  echo "
    <style type='text/css'>
      #searchintegrate #subheader{
        font-size: 11px;
        font-weight:bold;
        padding: 1px 1px 5px 0;
        text-align:left;
        border-bottom: 1px dotted #000;
        margin-bottom: 10px;
      }
      #searchintegrate #result{
        padding:0px 5px 5px 15px;
        margin-bottom: 10px;  
        clear: both;
        border-bottom: 1px solid #ccc;
      }
      #searchintegrate #title{
        font-size:13px;
        color: #ff6600;
        text-decoration:underline;
      }
      #searchintegrate #advertiser{
        font-size:11px;
        color:#330000;
        padding: 0 10px 0 0;
        text-decoration:none;
      }
      #searchintegrate #description{
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
    echo "<script type=\"text/javascript\" charset=\"utf-8\" src=\"http://wp.searchintegrate.com/search.js?q=$search&wp=$wp'))\"></script>";
    echo "
      <script type=\"text/javascript\" charset=\"utf-8\">
        var content = document.getElementById('content');
        if (typeof(si_content)!='undefined'){
          content.innerHTML += '<div id=\"searchintegrate\"><h4>&quot;$search&quot; results from searchintegrate.com</h4>' 
          + si_content
          + '</div>';
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
  </table>
  
  <? 
  $wp = md5(get_option('home'));
  echo "<script type=\"text/javascript\" charset=\"utf-8\" src=\"http://wp.searchintegrate.com/ping.js?wp=$wp\"></script>";
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