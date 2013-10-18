<?php
/**
 * Slider
 * Shortcode that allows to display a simple slideshow
 */
return;
if ( !class_exists( 'avia_sc_masonry_entries' ) ) 
{
	class avia_sc_masonry_entries extends aviaShortcodeTemplate
	{
			static $slide_count = 0;
	
			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['name']			= __('Fullwidth Masonry', 'avia_framework' );
				$this->config['tab']			= __('Content Elements', 'avia_framework' );
				$this->config['icon']			= AviaBuilder::$path['imagesURL']."sc-masonry.png";
				$this->config['order']			= 38;
				$this->config['target']			= 'avia-target-insert';
				$this->config['shortcode'] 		= 'av_masonry_entries';
				$this->config['tooltip'] 	    = __('Display a fullwidth masonry/grid with blog entries', 'avia_framework' );
				$this->config['tinyMCE'] 		= array('disable' => "true");
				$this->config['drag-level'] 	= 1;
			}

			/**
			 * Popup Elements
			 *
			 * If this function is defined in a child class the element automatically gets an edit button, that, when pressed
			 * opens a modal window that allows to edit the element properties
			 *
			 * @return void
			 */
			function popup_elements()
			{
				$this->elements = array(


                   array(
						"name" 	=> __("Which Entries?", 'avia_framework' ),
						"desc" 	=> __("Select which entries should be displayed by selecting a taxonomy", 'avia_framework' ),
						"id" 	=> "link",
						"fetchTMPL"	=> true,
						"type" 	=> "linkpicker",
						"subtype"  => array( __('Display Entries from:',  'avia_framework' )=>'taxonomy'),
						"multiple"	=> 6,
						"std" 	=> "category"
				),
				
				array(
					"name" 	=> __("Post Number", 'avia_framework' ),
					"desc" 	=> __("How many items should be displayed per page?", 'avia_framework' ),
					"id" 	=> "items",
					"type" 	=> "select",
					"std" 	=> "12",
					"subtype" => AviaHtmlHelper::number_array(1,100,1, array('All'=>'-1'))),
				
					
				array(
					"name" 	=> __("Size Settings", 'avia_framework' ),
					"desc" 	=> __("Here you can select how the masonry should behave and handle all entries and the feature images of those entries", 'avia_framework' ),
					"id" 	=> "size",
					"type" 	=> "radio",
					"std" 	=> "fixed masonry",
					"options" => array(
						'flex' => __('Flexible Grid: All entries get the same width but Images of each entry are displayed with their original height and width ratio',  'avia_framework' ),
						'fixed' => __('Perfect Grid: Display a perfect grid where each element has exactly the same size. Images get cropped/stretched if they don\'t fit',  'avia_framework' ),
						'fixed masonry' => __('Perfect Automatic Masonry: Display a grid where most elements get the same size, only elements with very wide images get twice the width and elements with very high images get twice the height. To qualify for "very wide" or "very high" the image must have a aspect ratio of 16:9 or higher',  'avia_framework' ),
						'fixed manually' => __('Perfect Manual Masonry: Manually control the height and width of entries by adding either a "landscape" or "portrait" tag when creating the entry. Elements with no such tag use a fixed default size, elements with both tags will display extra large',  'avia_framework' ),
					)),
					
					
				array(
					"name" 	=> __("Gap between elements", 'avia_framework' ),
					"desc" 	=> __("Select the gap between the elements", 'avia_framework' ),
					"id" 	=> "gap",
					"type" 	=> "select",
					"std" 	=> "1px",
					"subtype" => array(
						__('No Gap',  'avia_framework' ) =>'no',
						__('1 Pixel Gap',  'avia_framework' ) =>'1px',
						__('Large Gap',  'avia_framework' ) =>'large',
					)),
					
				
				array(
					"name" 	=> __("Element Title and Excerpt", 'avia_framework' ),
					"desc" 	=> __("You can choose if you want to display title and/or caption", 'avia_framework' ),
					"id" 	=> "caption_elements",
					"type" 	=> "select",
					"std" 	=> "title excerpt",
					"subtype" => array(
						__('Display Title and Excerpt',  'avia_framework' ) =>'title excerpt',
						__('Display Title',  'avia_framework' ) =>'title',
						__('Display Excerpt',  'avia_framework' ) =>'excerpt',
						__('Display Neither',  'avia_framework' ) =>'none',
					)),	
				
					
				array(
					"name" 	=> __("Element Title and Excerpt", 'avia_framework' ),
					"desc" 	=> __("You can choose whether to always display Title and Excerpt or only on hover", 'avia_framework' ),
					"id" 	=> "caption_display",
					"type" 	=> "select",
					"std" 	=> "always",
					"required" => array('caption_elements','not','none'),
					"subtype" => array(
						__('Always Display',  'avia_framework' ) =>'always',
						__('On Hover',  'avia_framework' ) =>'on-hover',
					)),	
					
					
					// add ID!!!!!!!!!!!!!!!
					
				);
			}
			
			/**
			 * Editor Element - this function defines the visual appearance of an element on the AviaBuilder Canvas
			 * Most common usage is to define some markup in the $params['innerHtml'] which is then inserted into the drag and drop container
			 * Less often used: $params['data'] to add data attributes, $params['class'] to modify the className
			 *
			 *
			 * @param array $params this array holds the default values for $content and $args. 
			 * @return $params the return array usually holds an innerHtml key that holds item specific markup.
			 */
			function editor_element($params)
			{	
				$params['innerHtml'] = "<img src='".$this->config['icon']."' title='".$this->config['name']."' />";
				$params['innerHtml'].= "<div class='avia-element-label'>".$this->config['name']."</div>";
				return $params;
			}
			
			/**
			 * Editor Sub Element - this function defines the visual appearance of an element that is displayed within a modal window and on click opens its own modal window
			 * Works in the same way as Editor Element
			 * @param array $params this array holds the default values for $content and $args. 
			 * @return $params the return array usually holds an innerHtml key that holds item specific markup.
			 */
			function editor_sub_element($params)
			{	
				$img_template 		= $this->update_template("img_fakeArg", "{{img_fakeArg}}");
				$template 			= $this->update_template("title", "{{title}}");
				$content 			= $this->update_template("content", "{{content}}");
				
				$thumbnail = isset($params['args']['id']) ? wp_get_attachment_image($params['args']['id']) : "";
				
		
				$params['innerHtml']  = "";
				$params['innerHtml'] .= "<div class='avia_title_container'>";
				$params['innerHtml'] .= "	<span class='avia_slideshow_image' {$img_template} >{$thumbnail}</span>";
				$params['innerHtml'] .= "	<div class='avia_slideshow_content'>";
				$params['innerHtml'] .= "		<h4 class='avia_title_container_inner' {$template} >".$params['args']['title']."</h4>";
				$params['innerHtml'] .= "		<p class='avia_content_container' {$content}>".stripslashes($params['content'])."</p>";
				$params['innerHtml'] .= "	</div>";
				$params['innerHtml'] .= "</div>";
				
				
				
				return $params;
			}
			
			
			
			/**
			 * Frontend Shortcode Handler
			 *
			 * @param array $atts array of attributes
			 * @param string $content text within enclosing form of shortcode element 
			 * @param string $shortcodename the shortcode found, when == callback name
			 * @return string $output returns the modified html string 
			 */
			
			function shortcode_handler($atts, $content = "", $shortcodename = "", $meta = "")
			{
				$output  = "";
				
				$skipSecond = false;
				
				//check if we got a layerslider
				global $wpdb;
				
				$params['class'] = "alternate_color ".$meta['el_class'];
				$params['open_structure'] = false;
				if($meta['index'] == 0) $params['close'] = false;
				if($meta['index'] != 0) $params['class'] .= " masonry-not-first";
				if($meta['index'] == 0 && get_post_meta(get_the_ID(), 'header', true) != "no") $params['class'] .= " masonry-not-first";
				
				
				$output .=  avia_new_section($params);
				$masonry  = new avia_masonry($atts);
				$masonry->extract_terms();
				$masonry->query_entries();
				$output .= $masonry->html();
				$output .= "</div>"; //close section
				
				
				//if the next tag is a section dont create a new section from this shortcode
				if(!empty($meta['siblings']['next']['tag']) && in_array($meta['siblings']['next']['tag'], AviaBuilder::$full_el ))
				{
				    $skipSecond = true;
				}

				//if there is no next element dont create a new section.
				if(empty($meta['siblings']['next']['tag']))
				{
				    $skipSecond = true;
				}
				
				if(empty($skipSecond)) {
				
				$output .= avia_new_section(array('close'=>false, 'id' => "after_masonry"));
				
				}
				
				return $output;
			}
			
	}
}





if ( !class_exists( 'avia_masonry' ) )
{
	class avia_masonry
	{
		static  $element = 0;
		protected $atts;
		protected $entries;

		function __construct($atts = array())
		{
			self::$element += 1;
			$this->atts = shortcode_atts(array(	'link' 	=> 'category',
												'items' => 24,
												'size'	=> 'fixed',
												'gap'	=> '1px',
												'caption_elements' 	=> 'title excerpt',
												'caption_display' 	=> 'always',
												'auto_ratio' 		=> 1.7 //equals a 16:9 ratio
		                                 		), $atts);
		                                 		
		  	$this->atts = apply_filters('avf_masonry_settings', $this->atts, self::$element);
		  	
		}
		
		
		function extract_terms()
		{
			if(isset($this->atts['link']))
			{
				$this->atts['link'] = explode(',', $this->atts['link'], 2 );
				$this->atts['taxonomy'] = $this->atts['link'][0];

				if(isset($this->atts['link'][1]))
				{
					$this->atts['categories'] = $this->atts['link'][1];
				}
			}
		}
		
		
		
		function html()
		{
			if(empty($this->loop)) return;
			
			$output 	= "";
			$size 		= strpos($this->atts['size'], 'fixed') !== false ? 'fixed' : "flex";
			$auto 		= strpos($this->atts['size'], 'masonry') !== false ? true : false;
			$manually	= strpos($this->atts['size'], 'manually') !== false ? true : false;
			$defaults 	= array('ID'=>'', 'thumb_ID'=>'', 'title' =>'', 'url' => '',  'class' => array(),  'date' => '', 'excerpt' => '', 'data' => '', 'attachment'=> array(), 'bg' => "");
			
			$output  = "<div class='av-masonry-parent noHover av-{$size}-size av-{$this->atts['gap']}-gap av-caption-{$this->atts['caption_display']}' >";
			$output .= 	"<div class='av-masonry-container isotope av-js-disabled ' >";
			
			foreach($this->loop as $entry)
			{
				extract(array_merge($defaults, $entry));
				$class_string = implode(' ', $class);
				if(!empty($attachment))
				{
					if($size == 'fixed')
					{
						$bg = '<div class="av-masonry-outerimage-container"><div class="av-masonry-image-container" style="background-image: url('.$attachment[0].');"><img src="'.$attachment[0].'" title="" alt="" /></div></div>';
						
						if($auto)
							$class_string .= $this->ratio_check_by_image_size($attachment);
							
						if($manually)
							$class_string .= $this->ratio_check_by_tag($entry['tags']);	
						
					}
					else
					{
						$bg = '<div class="av-masonry-outerimage-container"><div class="av-masonry-image-container"><img src="'.$attachment[0].'" title="" alt="" /></div></div>';
					}
					
					
				}
				
				$output .= 	"<a href='{$url}' class='{$class_string}'>";
				$output .= 		"<div class='av-inner-masonry-sizer'></div>"; //responsible for the size
				$output .=		"<figure class='av-inner-masonry main_color'>";
				$output .= 			$bg;
				
				//title and excerpt
				if($this->atts['caption_elements'] != 'none')
				{
					$output .=	"<figcaption class='av-inner-masonry-content site-background'>";
					
					if(strpos($this->atts['caption_elements'], 'title') !== false){
						$output .=	"<h3 class='av-masonry-entry-title entry-title'>{$the_title}</h3>";
					}
					
					if(strpos($this->atts['caption_elements'], 'excerpt') !== false && !empty($content)){
						$output .=	"<div class='av-masonry-entry-content entry-content'>{$content}</div>";
					}
					$output .=	"</figcaption>";
				}
				$output .= 		"</figure>";
				$output .= 	"</a><!--end av-masonry entry-->";					
			}
			
			$output .= 	"</div>";
			$output .= "</div>";
			return $output;
		}
		
		
		function ratio_check_by_image_size($attachment)
		{
			$img_size = ' av-grid-img';
			
			if($attachment[1] > $attachment[2]) //landscape
			{
				//only consider it landscape if its 1.7 times wider than high
				if($attachment[1] / $attachment[2] > $this->atts['auto_ratio']) $img_size = ' av-landscape-img';
			}
			else //same check with portrait
			{
				if($attachment[2] / $attachment[1] > $this->atts['auto_ratio']) $img_size = ' av-portrait-img';
			}
			
			return $img_size;
		}
		
		function ratio_check_by_tag($tags)
		{
			$img_size = '';
			
			if(is_array($tags))
			{	
				if(in_array('portrait', $tags)) { $img_size .= ' av-portrait-img'; }
				if(in_array('landscape', $tags)){ $img_size .= ' av-landscape-img'; }
			}
			
			if(empty($img_size))  $img_size = ' av-grid-img';
			
			return $img_size;
			
		}
		
		
		function prepare_loop_from_entries()
		{
			$this->loop = array();
			if(empty($this->entries) || empty($this->entries->posts)) return;
			$tagTax = "post_tag"; 
			$date_format = get_option('date_format');
			
			
			foreach($this->entries->posts as $key => $entry)
			{ 	
				$img_size	 						= 'masonry';
				$this->loop[$key]['ID'] = $id		= $entry->ID;
				$this->loop[$key]['thumb_ID'] 		= get_post_thumbnail_id($id);
				$this->loop[$key]['the_title'] 		= get_the_title($id);
				$this->loop[$key]['url']			= get_permalink($id);
				$this->loop[$key]['date'] 			= get_the_time($date_format, $id);
				$this->loop[$key]['class'] 			= get_post_class("av-masonry-entry isotope-item thumb_no".$this->loop[$key]['thumb_ID'], $id); 
				$this->loop[$key]['content']		= !empty($entry->post_excerpt) ? $entry->post_excerpt : avia_backend_truncate($entry->post_content, apply_filters( 'avf_masonry_excerpt_length' , 60) , apply_filters( 'avf_masonry_excerpt_delimiter' , " "), "…", true, '');
				
				
				//post type specific
				switch($entry->post_type)
				{
					case 'post': 
					
					$post_format 		= get_post_format($id) ? get_post_format($id) : 'standard';
					$this->loop[$key]	= apply_filters( 'post-format-'.$post_format, $this->loop[$key] );
					
					break;
					
					case 'attachment':
					
					$this->loop[$key]['thumb_ID'] = $id;
					
					break; 
					
					case 'product':
					
					$tagTax = "product_tag"; 
					
					break; 
					
				}
				
				//get post tags
				$this->loop[$key]['tags']		= wp_get_post_terms($id, $tagTax, array( 'fields' => 'slugs' ));
				
				//check if the image got landscape as well as portrait class applied. in that case use a bigger image size
				if(strlen($this->ratio_check_by_tag($this->loop[$key]['tags'])) > 20) $img_size = 'extra_large';
				
				//get attachment data
				$this->loop[$key]['attachment'] = wp_get_attachment_image_src($this->loop[$key]['thumb_ID'], $img_size);
				
				//apply filter for other post types, in case we want to use them and display additional/different information
				$this->loop[$key] = apply_filters('avf_masonry_loop_prepare', $this->loop[$key], $this->entries);
			}
		}
		
		
		//fetch new entries
		public function query_entries($params = array())
		{
			global $avia_config;

			if(empty($params)) $params = $this->atts;

			if(empty($params['custom_query']))
            {
				$query = array();

				if(!empty($params['categories']))
				{
					//get the portfolio categories
					$terms 	= explode(',', $params['categories']);
				}

				$page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : get_query_var( 'page' );
				if(!$page) $page = 1;

				//if we find no terms for the taxonomy fetch all taxonomy terms
				if(empty($terms[0]) || is_null($terms[0]) || $terms[0] === "null")
				{
					$terms = array();
					$allTax = get_terms( $params['taxonomy']);
					foreach($allTax as $tax)
					{
						$terms[] = $tax->term_id;
					}
				}
				
				
					
					$query = array(	'orderby' 	=> 'date',
									'order' 	=> 'DESC',
									'paged' 	=> $page,
									'post_type' => 'any',
									'posts_per_page' => $params['items'],
									'tax_query' => array( 	array( 	'taxonomy' 	=> $params['taxonomy'],
																	'field' 	=> 'id',
																	'terms' 	=> $terms,
																	'operator' 	=> 'IN')));
				
					
																
					
			}
			else
			{
				$query = $params['custom_query'];
			}


			$query = apply_filters('avia_masonry_entries_query', $query, $params);

			$this->entries = new WP_Query( $query );
			$this->prepare_loop_from_entries();
		}
	}
}
















