<?php
/**
 * 
 *
 * 
 */
class ideaMT_like_toggle_class
{
    public $likes;

    /**
     *
     */
    function run_ideas()
    {
        add_filter('the_content', array($this, 'display_like_button'), 1);
        add_action('wp_body_open',array($this , 'like_unlike_idea'));
    }

    function display_like_button($content)
    {        
        if (is_singular('ideas')) {
            $like_status = $this->user_is_liked_this_idea() ? 'Unlike' : 'Like';
            $html = '<a class="button button-large" href="';
            $html .= get_permalink().'?idea_action='.$like_status.'&idea_id='.get_the_ID().'">';
            $html .= $like_status." (".count($this->likes).")";
            $html .= '</a><br>';
        }
        return $html . $content;
    }


    public function like_unlike_idea()
    {
        $this->likes = $this->get_idea_likes();
        if ($_GET['idea_action']) {
            if ($_GET['idea_action'] == 'Like') {
                array_push($this->likes, get_current_user_id());
                update_post_meta(get_the_ID(), 'idea_likes', $this->likes);
            }
            if ($_GET['idea_action'] == 'Unlike') {
                // if (($key = array_search(get_current_user_id(), $this->likes)) != false) {
                //    unset($this->likes[$key]);
                update_post_meta(get_the_ID(), 'idea_likes', array());
                //  }

            }
        }
    }

    public function user_is_liked_this_idea()
    {
        $resukt = in_array(get_current_user_id(), $this->likes );
        return $resukt;
    }

    public function get_idea_likes()
    {
        $likes = get_post_meta(get_the_ID(),'idea_likes');
        return  $likes;
    }
}
/**
 *
 */
function run_ideaMT_like_toggle_class()
{

    $plugin = new ideaMT_like_toggle_class();
    $plugin->run_ideas();
}
run_ideaMT_like_toggle_class();









