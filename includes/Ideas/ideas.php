<?php
/**
 * 
 *
 * 
 */
class ideaMT_ideas_class
{
    /**
     *
     */
    public function __construct()
    {
        $this->load_dependencies();
    }

    function load_dependencies()
    {
        /**
         *
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'Ideas/ideaMT-attachment.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'Ideas/insert_idea_form.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'Ideas/ideaMT-like-toggle.php';
    }
    /**
     *
     */
    function run_ideas()
    {
        add_action('init', array($this, 'Register_idea_category_taxonomies'));
        add_action('init', array($this, 'Register_ideas_post_type'));
        add_filter('the_content', array($this, 'display_attachment_file'), 1);
    }


    function Register_idea_category_taxonomies()
    {

        $labels = array(
            'name'                          => __('Categories', 'IMTPDOMAIN'),
            'singular_name'                 => __('category', 'IMTPDOMAIN'),
            'search_items'                  => __('Search Ideas Categories', 'IMTPDOMAIN'),
            'popular_items'                 => __('Popular Ideas Categories', 'IMTPDOMAIN'),
            'all_items'                     => __('All Ideas Categories', 'IMTPDOMAIN'),
            'parent_item'                   => __('Parent Idea Category', 'IMTPDOMAIN'),
            'edit_item'                     => __('Edit Idea Category', 'IMTPDOMAIN'),
            'update_item'                   => __('Update Idea Category', 'IMTPDOMAIN'),
            'add_new_item'                  => __('Add New Idea Category', 'IMTPDOMAIN'),
            'new_item_name'                 => __('New Idea Category', 'IMTPDOMAIN'),
            'separate_items_with_commas'    => __('Separate Ideas categories with commas', 'IMTPDOMAIN'),
            'add_or_remove_items'           => __('Add or remove Ideas categories', 'IMTPDOMAIN'),
            'choose_from_most_used'         => __('Choose from most used ideas categories', 'IMTPDOMAIN'),
        );

        $args = array(
            'label'                         => 'ideas Categories',
            'labels'                        => $labels,
            'public'                        => true,
            'hierarchical'                  => true,
            'show_ui'                       => true,
            'show_in_nav_menus'             => true,
            'args'                          => array('orderby' => 'term_order'),
            'rewrite'                       => array('slug' => 'ideas', 'with_front' => true, 'hierarchical' => true),
            'query_var'                     => true
        );

        register_taxonomy('ideas_categories', 'ideas', $args);
    }






    /**
     *
     */
    function Register_ideas_post_type()
    {
        $labels = array(
            'name'                  => _x('Ideas', 'Post type general name', 'IMTPDOMAIN'),
            'singular_name'         => _x('Idea', 'Post type singular name', 'IMTPDOMAIN'),
            'menu_name'             => _x('Ideas', 'Admin Menu text', 'IMTPDOMAIN'),
            'name_admin_bar'        => _x('Ideas', 'Add New on Toolbar', 'IMTPDOMAIN'),
            'add_new'               => __('Add New', 'IMTPDOMAIN'),
            'add_new_item'          => __('Add New Idea', 'IMTPDOMAIN'),
            'new_item'              => __('New idea', 'IMTPDOMAIN'),
            'edit_item'             => __('Edit idea', 'IMTPDOMAIN'),
            'view_item'             => __('View idea', 'IMTPDOMAIN'),
            'all_items'             => __('All ideas', 'IMTPDOMAIN'),
            'search_items'          => __('Search ideas', 'IMTPDOMAIN'),
            'parent_item_colon'     => __('Parent ideas:', 'IMTPDOMAIN'),
            'not_found'             => __('No ideas found.', 'IMTPDOMAIN'),
            'not_found_in_trash'    => __('No ideas found in Trash.', 'IMTPDOMAIN'),
        );
        $args = array(
            'labels'             => $labels,
            'description'        => 'ideas custom post type.',
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'idea', 'with_front' => true),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => true,
            'menu_position'      => 20,
            'supports'           => array('title', 'editor', 'author', 'comments'),
            'show_in_rest'       => false
        );

        return register_post_type('ideas', $args);
    }

    function display_attachment_file($content)
    {
        if (is_singular('ideas')) {
            $attachment_file = get_post_meta(get_the_ID(), 'idea_attachment', true);
            if ($attachment_file) {
                $html = '<hr><a class="button button-large" href="';
                $html .= $attachment_file;
                $html .= '">' . __('Download', 'IMTPDOMAIN') . '</a>';
                return $content . $html;
            }
        }

        return $content;
    }
}
/**
 *
 */
function run_ideaMT_ideas_class()
{

    $plugin = new ideaMT_ideas_class();
    $plugin->run_ideas();
}
run_ideaMT_ideas_class();








add_action('wp_ajax_recently_added_posts', 'recently_added_posts');
add_action('wp_ajax_nopriv_recently_added_posts', 'recently_added_posts');
if ($_POST['action'] == 'recently_added_posts') {
    function recently_added_posts($query)
    {
        if ($query->is_search() && !is_admin()) {
            $query->set('ideas', array('sfwd-courses', 'sfwd-lessons', 'sfwd-topic'));
            $query->set(
                'orderby',
                array(
                    'post_type' => 'ideas',
                    'date' => 'DESC'
                )
            );
            return $query;
            die();
        }
        add_filter('pre_get_posts', 'recently_added_posts');
    }
}
add_action('wp_ajax_last_updated_posts', 'last_updated_posts');
add_action('wp_ajax_nopriv_last_updated_posts', 'last_updated_posts');
if ($_POST['action'] == 'last_updated_posts') {
    function last_updated_posts($query)
    {
        if ($query->is_search() && !is_admin()) {
            $query->set('ideas', array('sfwd-courses', 'sfwd-lessons', 'sfwd-topic'));
            $query->set(
                'orderby',
                array(
                    'post_type' => 'ideas',
                    'modified' => 'DESC'
                )
            );
        }
        return $query;
        die();
    }
    add_filter('pre_get_posts', 'last_updated_posts');
}
function ajaxurl_filter()
{
    echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}
add_action('wp_head', 'ajaxurl_filter');



add_action('wp_body_open', function () {
?>
    <select id="filter">
        <option value='last_updated_posts' id='recently_added'>
            Last Updated
        </option>`
        <option value="recently_added_posts" id='last_updated'>
            Recently Added
        </option>
    </select>
    <script>
  jQuery("#filter").change(function(){
      var selected_option=jQuery("#filter option:selected").val();
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {'action' :selected_option },
            success: function(response){alert(response);}
        });
    });
        </script>
<?php
});
