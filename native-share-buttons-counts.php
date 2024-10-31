<?php

function really_simple_share_counts () {
  $how_many_posts = 60;

	//must check that the user has the required capability 
	if (!current_user_can('manage_options')) {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

  // INITIALIZE ALL SCRIPTS
	global $really_simple_share_option;
  really_simple_share_init(true);
  really_simple_share_scripts();
  if (($really_simple_share_option['active_buttons']['facebook_like'] and $really_simple_share_option['facebook_like_html5'])
  || $really_simple_share_option['active_buttons']['facebook_share_new']) {
  	really_simple_share_facebook_like_html5_bottom_scripts();
  }
  really_simple_share_style();
  
  $out = '
	<style>
    #poststuff          { padding-top:10px; position:relative; }
    #poststuff .postbox { min-width: 200px; }
    #poststuff_left, #poststuff_right { float:none; width: 100%; min-width:550px; }
    
    @media all and (min-width: 970px) {
      #poststuff_left  { float:left;  width:74%; }
      #poststuff_right { float:right; width:25%; min-width:200px; }
    }
  
		#really_simple_share_form h3 { cursor: default; }
		#really_simple_share_form td { vertical-align:top; padding-bottom:15px; }
    
    .really_simple_share_counts { width: 55%; }
	</style>
  <div class="wrap">
	<h2>'.__( 'Native Share Buttons:', 'really-simple-share').'
    '.__( 'Post/Page Counts', 'really-simple-share').'
  </h2>
	<div id="poststuff">

	<div id="poststuff_left">
    <p>'.sprintf(__('Here they are, your <strong>%u most recent posts/pages</strong>, along with their share button counts.', 'really-simple-share'), $how_many_posts).'</p>
    <p>'.__('Please wait a moment, while the counters load.', 'really-simple-share').'</p>';

  $the_query = new WP_Query( 'post_type=any&post_status=publish&posts_per_page='.$how_many_posts );

  if ( $the_query->have_posts() ) {
 		$out .= '<table class="wp-list-table widefat">
      <tr><th>Post/Page</th><th>Share buttons</th></tr>';
  	while ( $the_query->have_posts() ) {
  		$the_query->the_post();
  		$out .= '<tr class="alternate">
        <td> <a href="'.admin_url('post.php?action=edit&post='.get_the_ID()).'">' . get_the_title() . '</a> | ' . get_the_date() . '</td>
        <td class="really_simple_share_counts">' . really_simple_share_publish(get_permalink(), get_the_title()) . '</td>
        </tr>';
  	}
 		$out .= '</table>';
  } else {
    $out .= __('No post or pages found.', 'really-simple-share');
  }
  /* Restore original Post Data */
  wp_reset_postdata();

  $out .= '
	</div>
	
	<div id="poststuff_right">'
			.'</div>

	</div>
	</div>';
  
  echo $out;
}
