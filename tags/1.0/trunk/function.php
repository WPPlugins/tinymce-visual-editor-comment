<?php
function hello_dolly() {
echo '<p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="JPKK3H9LQVUSN">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</p>';
}

// Now we set that function up to execute when the admin_notices action is called
add_action( 'admin_notices', 'hello_dolly' );


add_filter( 'comment_form_field_comment', 'comment_editor' );
 
function comment_editor() {
  global $post;
 
  ob_start();
 
  wp_editor( '', 'comment', array(
    'textarea_rows' => 15,
    'teeny' => true,
    'quicktags' => false,
    'media_buttons' => false
  ) );
 
  $editor = ob_get_contents();
 
  ob_end_clean();
 
  //make sure comment media is attached to parent post
  $editor = str_replace( 'post_id=0', 'post_id='.get_the_ID(), $editor );
 
  return $editor;
}
 
// wp_editor doesn't work when clicking reply. Here is the fix.
add_action( 'wp_enqueue_scripts', '__THEME_PREFIX__scripts' );
function __THEME_PREFIX__scripts() {
    wp_enqueue_script('jquery');
}
add_filter( 'comment_reply_link', '__THEME_PREFIX__comment_reply_link' );
function __THEME_PREFIX__comment_reply_link($link) {
    return str_replace( 'onclick=', 'data-onclick=', $link );
}
add_action( 'wp_head', '__THEME_PREFIX__wp_head' );
function __THEME_PREFIX__wp_head() {
?>
<script type="text/javascript">
  jQuery(function($){
    $('.comment-reply-link').click(function(e){
      e.preventDefault();
      var args = $(this).data('onclick');
      args = args.replace(/.*\(|\)/gi, '').replace(/\"|\s+/g, '');
      args = args.split(',');
      tinymce.EditorManager.execCommand('mceRemoveEditor', true, 'comment');
      addComment.moveForm.apply( addComment, args );
      tinymce.EditorManager.execCommand('mceAddEditor', true, 'comment');
    });
  });
</script>
<?php } ?>