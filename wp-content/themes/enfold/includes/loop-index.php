<?php
global $avia_config, $post_loop_count;


if(empty($post_loop_count)) $post_loop_count = 1;
$blog_style = !empty($avia_config['blog_style']) ? $avia_config['blog_style'] : avia_get_option('blog_style','multi-big');
$blog_content = !empty($avia_config['blog_content']) ? $avia_config['blog_content'] : "content";

// check if we got posts to display:
if (have_posts()) :

	while (have_posts()) : the_post();

	/*
     * get the current post id, the current post class and current post format
 	 */

	$the_id 		= get_the_ID();
	$parity			= $post_loop_count % 2 ? 'odd' : 'even';
	$last           = count($wp_query->posts) == $post_loop_count ? " post-entry-last " : "";
	$post_class 	= "post-entry-".$the_id." post-loop-".$post_loop_count." post-parity-".$parity.$last." ".$blog_style;
	$post_format 	= get_post_format() ? get_post_format() : 'standard';

	/*
     * retrieve slider, title and content for this post,...
     */
    $size = strpos($blog_style, 'big') ? strpos(avia_layout_class( 'main' , false), 'sidebar') ? 'entry_with_sidebar' : 'entry_without_sidebar' : 'square';
    if(!empty($avia_config['preview_mode']) && !empty($avia_config['image_size']) && $avia_config['preview_mode'] == 'custom') $size = $avia_config['image_size'];
	$current_post['slider']  	= get_the_post_thumbnail($the_id, $size);
	$current_post['title']   	= get_the_title();
	$current_post['content'] 	= $blog_content == "content" ? get_the_content(__('Read more','avia_framework').'<span class="more-link-arrow">  &rarr;</span>') : get_the_excerpt();
	$current_post['content'] 	= $blog_content == "excerpt_read_more" ? $current_post['content'].'<div class="read-more-link"><a href="'.get_permalink().'" class="more-link">'.__('Read more','avia_framework').'<span class="more-link-arrow">  &rarr;</span></a></div>' : $current_post['content'];
	$current_post['before_content'] = "";

	/*
     * ...now apply a filter, based on the post type... (filter function is located in includes/helper-post-format.php)
     */
	$current_post	= apply_filters( 'post-format-'.$post_format, $current_post );
	$with_slider    = empty($current_post['slider']) ? "" : "with-slider";
	/*
     * ... last apply the default wordpress filters to the content
     */
	$current_post['content'] = str_replace(']]>', ']]&gt;', apply_filters('the_content', $current_post['content'] ));

	/*
	 * Now extract the variables so that $current_post['slider'] becomes $slider, $current_post['title'] becomes $title, etc
	 */
	extract($current_post);








	/*
	 * render the html:
	 */

	echo "<article class='".implode(" ", get_post_class('post-entry post-entry-type-'.$post_format . " " . $post_class . " ".$with_slider))."' ".avia_markup_helper(array('context' => 'entry','echo'=>false)).">";

        //default link for preview images
        $link = get_permalink();

        //on single page replace the link with a fullscreen image
        if(is_singular())
        {
            $link = avia_image_by_id(get_post_thumbnail_id(), 'large', 'url');
        }


        //echo preview image
        if(strpos($blog_style, 'big') !== false)
        {
            if($slider) $slider = '<a href="'.$link.'">'.$slider.'</a>';
            if($slider) echo '<div class="big-preview '.$blog_style.'">'.$slider.'</div>';
        }

        if(!empty($before_content))
            echo '<div class="big-preview '.$blog_style.'">'.$before_content.'</div>';

        echo "<div class='blog-meta'>";

        $blog_meta_output = "";
        $icon =  '<span class="iconfont" '.av_icon_string($post_format).'></span>';

            if(strpos($blog_style, 'multi') !== false)
            {
                $gravatar = "";
                $link = get_post_format_link($post_format);
                if($post_format == 'standard')
                {
                    $gravatar = get_avatar( get_the_author_meta('email'), '75', "blank" );
                    $link = get_author_posts_url($post->post_author);
                }

                $blog_meta_output = "<a href='{$link}' class='post-author-format-type'><span class='rounded-container'>".$gravatar.$icon."</span></a>";
            }
            else if(strpos($blog_style, 'small')  !== false)
            {
                $blog_meta_output = "<a href='{$link}' class='small-preview'>".$slider.$icon."</a>";
            }

        echo apply_filters('avf_loop_index_blog_meta', $blog_meta_output);

        echo "</div>";

        echo "<div class='entry-content-wrapper clearfix {$post_format}-content'>";
            echo '<header class="entry-content-header">';
                echo $title;

                echo "<span class='post-meta-infos'>";
                $markup = avia_markup_helper(array('context' => 'entry_time','echo'=>false));
                echo "<time class='date-container minor-meta updated' $markup>".get_the_time(get_option('date_format'))."</time>";
                echo "<span class='text-sep'>/</span>";



                    if ( get_comments_number() != "0" || comments_open() ){

                    echo "<span class='comment-container minor-meta'>";
                    comments_popup_link(  "0 ".__('Comments','avia_framework'),
                                          "1 ".__('Comment' ,'avia_framework'),
                                          "% ".__('Comments','avia_framework'),'comments-link',
                                          "".__('Comments Disabled','avia_framework'));
                    echo "</span>";
                    echo "<span class='text-sep'>/</span>";
                    }


                    $taxonomies  = get_object_taxonomies(get_post_type($the_id));
                    $cats = '';
                    $excluded_taxonomies =  apply_filters('avf_exclude_taxonomies', array('post_tag','post_format'), get_post_type($the_id), $the_id);

                    if(!empty($taxonomies))
                    {
                        foreach($taxonomies as $taxonomy)
                        {
                            if(!in_array($taxonomy, $excluded_taxonomies))
                            {
                                $cats .= get_the_term_list($the_id, $taxonomy, '', ', ','').' ';
                            }
                        }
                    }

                    if(!empty($cats))
                    {
                        echo '<span class="blog-categories minor-meta">'.__('in','avia_framework')." ";
                        echo $cats;
                        echo '</span><span class="text-sep">/</span>';
                    }


                    echo '<span class="blog-author minor-meta">'.__('by','avia_framework')." ";
                    echo '<span class="entry-author-link" '.avia_markup_helper(array('context' => 'author_name','echo'=>false)).'>';
                    echo '<span class="vcard author"><span class="fn">';
                    the_author_posts_link();
                    echo '</span></span>';
                    echo '</span>';
                    echo '</span>';
                echo '</span>';
            echo '</header>';


            // echo the post content
            echo '<div class="entry-content" '.avia_markup_helper(array('context' => 'entry_content','echo'=>false)).'>';
            echo $content;
            echo '</div>';

            echo '<footer class="entry-footer">';

            wp_link_pages(array('before' =>'<div class="pagination_split_post">',
                                    'after'  =>'</div>',
                                    'pagelink' => '<span>%</span>'
                                    ));

            if(has_tag() && is_single())
            {
                echo '<span class="blog-tags minor-meta">';
                the_tags('<strong>'.__('Tags:','avia_framework').'</strong><span> ');
                echo '</span></span>';
            }

            echo '</footer>';

        echo "<div class='post_delimiter'></div>";
        echo "</div>";
        echo "<div class='post_author_timeline'></div>";
	echo "</article>";

	$post_loop_count++;
	endwhile;
	else:

?>

    <article class="entry">
        <header class="entry-content-header">
            <h1 class='post-title entry-title'><?php _e('Nothing Found', 'avia_framework'); ?></h1>
        </header>

        <p class="entry-content" <?php avia_markup_helper(array('context' => 'entry_content')); ?>><?php _e('Sorry, no posts matched your criteria', 'avia_framework'); ?></p>

        <footer class="entry-footer"></footer>
    </article>

<?php

	endif;

	if(empty($avia_config['remove_pagination'] ))
	{
		echo "<div class='{$blog_style}'>".avia_pagination('', 'nav')."</div>";
	}
?>
