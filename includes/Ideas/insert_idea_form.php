<?php
add_shortcode('shortcode_idea_form', 'form_template');

function form_template()
{ 
    if(!check_user_is_logged_IDS()) return false;
    recive_form();
    
    
    ?>
    <form method="POST" enctype="multipart/form-data">
        <label for="idea_category"><?php _e('Choose Idea Category', 'IMTPDOMAIN') ?></label>
        <select id="idea_category" name="idea_category" style="width:100%" required>
            <?php foreach (get_terms('ideas_categories') as $category) : ?>
                <option value='<?php echo $category->term_id; ?>' > <?php echo $category->name; ?></option>
                <?php endforeach; ?>
        </select>

        <br>
        <label for="idea_title"><?php _e('Title', 'IMTPDOMAIN') ?></label>
        <input type="text" name="idea_title" id="idea_title" required><br>
        <label for="idea_content"><?php _e('Description', 'IMTPDOMAIN') ?></label>
        <textarea type="text" name="idea_content" id="idea_content" required></textarea><br>
        <label for="idea_file"><?php _e('uploud file', 'IMTPDOMAIN') ?></label>
        <input type="file" name="idea_file" id="idea_file" style="height:60px" accept=".doc,.docx, .jpeg, .pdf">
        <p>Supported formats: JPEG,PDF,DOC</p>
        <input type="submit" value="submit" name="submit_idea"> 
    </form>
<?php
}


function recive_form()
{
    if (isset(
        $_POST['submit_idea'],
        $_POST['idea_category'],
        $_POST['idea_title'],
        $_POST['idea_content'],
    )) {
        $file_url = '';
        if (!empty($_FILES['idea_file']['name'])) {
            $uploaded_file = upload_file($_FILES['idea_file']);
            if ($uploaded_file['status']) {
                $file_url = $uploaded_file['content'];
            } else {
                print_status($uploaded_file['content']);
            }
        }

        $new_idea = array(
            'post_title'    => sanitize_text_field($_POST['idea_title']),
            'post_content'  => sanitize_textarea_field($_POST['idea_content']),
            'post_status'   => 'Pending',
            'post_type'     => 'ideas',
            'post_author'   => get_current_user_id(),
            'meta_input'    => array(
                'idea_attachment'   => esc_url_raw($file_url),
            ),
            'tax_input' => array(
                'ideas_categories'  => array($_POST['idea_category']),
            )
        );

        // Insert the post into the database
        $insert_post_status = wp_insert_post($new_idea);
        if ($insert_post_status) {
            print_status('Your idea was successfully submitted. Will be published after review');
        } else {
            print_status('Your idea has not been submitted, please try again');
        }
    }
}


function upload_file($file_s)
{
    if (!function_exists('wp_handle_upload')) require_once(ABSPATH . 'wp-admin/includes/file.php');
    $uploadedfile = $file_s;
    $upload_overrides = array('test_form' => false);
    if (in_array($file_s['type'], array(
        'application/pdf', 'application/msword',
        'application/vnd', 'openxmlformats-officedocument',
        'wordprocessingml.document', 'application/octet-stream', 'image/jpeg'
    ))) {
        $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
        if ($movefile) {
            return array('status' => true, 'content' => $movefile['url']);
        } else {
            return array('status' => false, 'content' => "upload file have problem, Try again!");
        }
    } else return array('status' => false, 'content' => "uplouded file format is not supported.");
}

function check_user_is_logged_IDS(){
    if(!is_user_logged_in()){
        print_status('Only logged in users can post ideas. <a href="'.wp_login_url( get_permalink()).'">Log in</a>' );
        return false;
    }else{
        return true;
    }
}


function print_status($message){
    echo "<pre class='xdebug-var-dump'>$message</pre>";
}