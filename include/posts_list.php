<h1><?php echo "$page_name - ID: $fbid"; ?></h1>

<iframe src="https://www.facebook.com/video/embed?video_id=536410896564566" width="224" height="400" frameborder="0"></iframe>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/pl_PL/sdk.js#xfbml=1&version=v2.7";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<table border="1" style="max-width:600px;">
    <tr>
      <th>załącznik</th>
      <th>treść</th>
      <th>opcje</th>
    </tr>
    <?php 
    //print_r($posts);
    foreach ($posts as $key => $post) {
        $attachment = '';
        if($post['type'] == 'video') {
            $attachment = '<div class="fb-video" data-href="'.$post['link'].'" data-width="350" data-show-text="false">
    <div class="fb-xfbml-parse-ignore">
      <blockquote cite="'.$post['link'].'">
        <a href="'.$post['link'].'">How to Share With Just Friends</a>
        <p>How to share with just friends.</p>
        Posted by <a href="https://www.facebook.com/facebook/">Facebook</a> on Friday, December 5, 2014
      </blockquote>
    </div>
  </div>';    
        }
    ?>
    <tr>
      <td><?php echo $attachment; ?></td>
      <td>
          <strong><?php echo wp_trim_words($post['message'], $num_words = 8, $more = null); ?></strong><br />
          <?php echo $post['message']; ?>
      </td>
      <td></td>
    </tr>
    <?php  
    } 
    ?>
</table>
