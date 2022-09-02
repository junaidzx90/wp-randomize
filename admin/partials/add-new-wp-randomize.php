<?php
if(isset($_POST['save_randomize_fields'])){
    $title = ((isset($_POST['title']))?$_POST['title']:'');
    $title = sanitize_text_field( $title );
    $title = stripslashes( $title );
    
    $rand_category = ((isset($_POST['rand_category']))?$_POST['rand_category']:'');
    $rand_category = intval($rand_category);
    
    $fields = [];
    $shortcodes = ((isset($_POST['shortcodes']))?$_POST['shortcodes']: []);
    foreach($shortcodes as $shortcode){
        $sh = str_replace("'", '"', $shortcode);
        $sh = stripslashes($sh);
        $fields[] = $sh;
    }
    
    global $wpdb;
    $wpdb->insert($wpdb->prefix.'wp_randomize', array(
        'title' => $title,
        'category' => $rand_category,
        'fields' => serialize($fields),
        'date' => date("Y-m-d h:i:s a")
    ));

    if($wpdb->insert_id){
        wp_safe_redirect( admin_url('admin.php?page=wp-randomize&action=edit&id='.$wpdb->insert_id) );
        exit;
    }
}
if(isset($_POST['update_randomize_fields'])){
    $reand_ID = intval($_POST['reand_ID']);

    $title = ((isset($_POST['title']))?$_POST['title']:'');
    $title = sanitize_text_field( $title );
    $title = stripslashes( $title );
    
    $rand_category = ((isset($_POST['rand_category']))?$_POST['rand_category']:'');
    $rand_category = intval($rand_category);
    
    $fields = [];
    $shortcodes = ((isset($_POST['shortcodes']))?$_POST['shortcodes']: []);
    foreach($shortcodes as $shortcode){
        $sh = str_replace("'", '"', $shortcode);
        $sh = stripslashes($sh);
        $fields[] = $sh;
    }
    
    global $wpdb;
    $wpdb->update($wpdb->prefix.'wp_randomize', array(
        'title' => $title,
        'category' => $rand_category,
        'fields' => serialize($fields),
    ), array('ID' => $reand_ID), array("%s", "%s", "%s"), array("%d"));
}
?>
<div id="randomize">
    <form action="" method="post">
        <?php
        $title = null;
        $categoryId = null;
        $shortcodes = [];

        $is_ID = null;

        if(isset($_GET['id'])){
            $randID = intval($_GET['id']);
            $is_ID = $randID;
            global $wpdb;
            $results = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wp_randomize WHERE ID = $randID");
            if($results){
                $title = $results->title;
                $categoryId = $results->category;
                $shortcodes = unserialize($results->fields);
            }
        }
        ?>
        <div class="randomize_field">
            <div class="random_th">
                <label for="title">Title</label>
            </div>
            <div class="random_td">
                <input type="text" class="widefat" name="title" id="title" value="<?php echo $title ?>">
            </div>
        </div>
        <div class="randomize_field">
            <div class="random_th">
                <label for="rand_category">Category</label>
            </div>
            <div class="random_td">
                <select name="rand_category" id="rand_category">
                    <?php
                    $terms = get_terms( array(
                        'taxonomy' => 'category',
                        'hide_empty' => false,
                    ) );
                    ?>
                    <option value="">Select Category</option>
                    <?php
                    if($terms){
                        foreach($terms as $term){
                            echo '<option '.((intval($categoryId) === $term->term_id)?'selected': '').' value="'.$term->term_id.'">'.$term->name.'</option>';
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        
        <div class="randomize_field">
            <div class="random_th">
                <label>Contents</label>
            </div>
            <div class="random_td">
                <div class="random_contents">
                    <div class="random_dynamic_fields">
                        <?php
                        if(is_array($shortcodes) && sizeof($shortcodes)>0){
                            foreach($shortcodes as $shortcode){
                                ?>
                                <div class="rands_inner_field">
                                    <span class="rand_field_count">1</span>
                                    <input type="text" placeholder="Shortcode" name="shortcodes[]" class="shortcode" value='<?php echo $shortcode ?>'>
                                    <span class="remove_rand_field">+</span>
                                </div>
                                <?php
                            }
                        }else{
                            echo '<p style="margin: 0; color: red">No entry added!</p>';
                        }
                        ?>
                    </div>
                    <button class="button-secondary add-new-shortcode">Add shortcode</button>
                </div>
            </div>
        </div>
        <?php
        if($is_ID !== null){
            echo '<input type="hidden" name="reand_ID" value="'.$is_ID.'">';
        }
        
        ?>
        <input type="submit" class="button-primary save_randomize_fields" value="Save changes" name="<?php echo (($is_ID !== null)? 'update_randomize_fields':'save_randomize_fields') ?>">
    </form>
</div>