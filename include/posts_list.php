<h1><?php echo "$page_name - ID: $fbid"; ?></h1>

<div style="font-size: 20px; font-weight: bold; background-color: lightgreen; margin-bottom: 10px;"><?php echo $msg; ?></div>

<table border="1">
    <tr>
      <th>zdjęcie</th>
      <th>treść</th>
      <th>opcje</th>
    </tr>
    <?php 
    //print_r($posts);
    foreach ($posts as $key => $post) {
        switch ($post['type']) {
            case 'video': 
                $typ_txt = 'Wideo';
                break;
            case 'photo': 
                $typ_txt = 'Zdjęcie';
                break;
            case 'link': 
                $typ_txt = 'Link';
                break;
            default:
                $typ_txt = 'Zdjęcie';
                break;
        }
    ?>
    <tr>
        <td>
            <a target="_blank" href="http://facebook.com/<?php echo $post['id']; ?>">
                <img src="<?php echo $post['full_picture']; ?>" style="max-width: 475px" alt="" />
            </a>            
        <td style="font-size: 22px; max-width: 500px;">
            <p>Typ posta: <strong><?php echo $typ_txt; ?></strong></p>
            <p><?php echo $post['message']; ?></p>
        </td>
        <td>
            <p><a target="_blank" href="http://facebook.com/<?php echo $post['id']; ?>">
                Zobacz post
                </a></p>
            
            <form method="post">
                <input type="hidden" value="<?php echo $post['id']; ?>" name="fb_post_id">
                <input type="hidden" value="<?php echo $post['message']; ?>" name="fb_post_message">
                <input type="hidden" value="<?php echo $post['created_time']; ?>" name="fb_post_date">
                <input type="hidden" value="<?php echo $post['attachments']['data'][0]['target']['id']; ?>" name="fb_attachment_id">
                <input type="hidden" value="<?php echo $post['attachments']['data'][0]['target']['url']; ?>" name="fb_attachment_url">
                <input type="hidden" value="<?php echo $post['full_picture']; ?>" name="fb_photo_url">
                <input type="hidden" value="<?php echo $post['type']; ?>" name="fb_attachment_type">
                <input type="hidden" value="http://facebook.com/<?php echo $post['id']; ?>" name="fb_post_url">
                <?php if($category !== false) { ?>
                    <input type="submit" value="Dodaj post" name="dodaj_post" class="button button-primary button-large">
                <?php } ?>    
            </form>
                <p>Pamiętaj! W celu dodawania wpisów musi istnieć kategoria o nazwie "Facebook".</p>
            
        </td>
    </tr>
    <?php  
    } 
    ?>
</table>
