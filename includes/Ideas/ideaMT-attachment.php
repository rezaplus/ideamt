<?php
/**
 * 
 *
 * 
 */
class ideaMT_attachment_class{
	/**
	 *
	 *
	 */
    function run_ideas_attachment(){
        add_action('add_meta_boxes', array($this,'add_idea_meta_boxes'));
        add_action('save_post', array($this,'save_idea_meta_data'));
        add_action('post_edit_form_tag', array($this,'update_edit_form'));
    }

    function add_idea_meta_boxes() {  
        add_meta_box('idea_attachment', 'Attachment', array($this,'idea_attachment_callback'), 'ideas', 'normal', 'high');  
    }
      
    
    function idea_attachment_callback() {  
        wp_nonce_field(plugin_basename(__FILE__), 'idea_attachment_nonce');
        $html = '<p class="description">';
        $html .= __('Upload your File here.','IMTPDOMAIN');
        $html .= '</p>';
        $html .= '<input type="file" id="idea_attachment" name="idea_attachment" value="" size="25" accept=".doc,.docx, .jpeg, .pdf">';
        $attachment_file = get_post_meta( get_the_ID(), 'idea_attachment', true );
        if(!empty($attachment_file)){
            $html .= '<hr><a class="button button-large" href="';
            $html .= $attachment_file;
            $html .= '">'.__('Download','IMTPDOMAIN').'</a>';
        }
        echo $html;
    }
    
    function save_idea_meta_data($id) {
        if(!empty($_FILES['idea_attachment']['name'])) {
            $supported_types = array('application/pdf','application/doc','image/jpeg');
            $arr_file_type = wp_check_filetype(basename($_FILES['idea_attachment']['name']));
            $uploaded_type = $arr_file_type['type'];
    
            if(in_array($uploaded_type, $supported_types)) {
                $upload = wp_upload_bits($_FILES['idea_attachment']['name'], null, file_get_contents($_FILES['idea_attachment']['tmp_name']));
                if(isset($upload['error']) && $upload['error'] != 0) {
                    wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
                } else {
                    update_post_meta($id, 'idea_attachment', $upload['url']);
                }
            }
            else {
                wp_die("The file type that you've uploaded is not supported format.");
            }
        }
    }
    
    function update_edit_form() {
        echo ' enctype="multipart/form-data"';
    }
}
function run_ideaMT_attachment_class() {

    $plugin = new ideaMT_attachment_class();
    $plugin->run_ideas_attachment();

}
run_ideaMT_attachment_class();
