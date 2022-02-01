<?php
/*
* Template Name: Events
*/

get_header('second');
the_post();

function displayEvents($myQuery) {
     // The Loop
     if ($the_query->have_posts()) {
        echo '<div class="row">';
        while ($the_query->have_posts()) : $the_query->the_post();

            foreach ($posts as $event) { 
                
                $title = get_the_title();
                $agency = get_post_meta($post->ID, 'event_location', true);
                $date = get_post_meta($post->ID, 'event-date', true);
                $start_time = get_post_meta($post->ID, 'event_start_time', true);
                $end_time = get_post_meta($post->ID, 'event_end_time', true);
                $location = get_post_meta($post->ID, 'event_location', true);
                $url = get_post_meta($post->ID, 'event_url', true);
                $url_text = get_post_meta($post->ID, 'event_url_text', true);
                
                ?>
                <div class="event-group col-sm-6 col-12 mb-4">

                    <img src="<?php echo  get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" class="img-fluid" width='1024' height='553' />

                    <div class="event-content">
                        <h5 class="event-title"><?php $title ?></h5>
                        <h6 class="event-agency"><?php $agency ?></h6>
                        <ul class="unstyled">
                            <div class="mb-1">
                                <li class="event-date">
                                    <i class="fa fa-clock-o mr-2" aria-hidden="true">
                                    <?php
                                    if (!empty($date)) {
                                        echo date('m/d', strtotime($event_date));

                                        if (!empty($start_time)) {
                                            echo  "at &nbsp;" . date('g:ia', strtotime($start_time));

                                            if (!empty($end_time)) {
                                                echo "-" . date('g:ia', strtotime($end_time));
                                            }
                                        }                          
                                    } ?>
                                </li>
                                <li class="event-location">
                                    <i class="fa fa-map-marker mr-2" aria-hidden="true">
                                        <?= $location ?>
                                </li>
                                <li class="event-desc">
                                    <?= the_content(); ?>
                                </li>
                            </div>
					        <li class="event-link">
                                <i class="fa fa-link mr-2" aria-hidden="true">
                                    <a href="<? $url?>"><?= $url_text ?></a>
                                </li>
                        </ul>
                </div>
        <?php }

            endwhile;
            wp_reset_postdata();
            echo "</div>";
        } else {

            echo "<p class="mb-5">No events today. Check <a href='https://knightconnect.campuslabs.com/engage/'>KnightConnect</a> and follow our Instagram <a href='https://www.instagram.com/ucf_osi/'>@ucf_osi</a> for more!</p>";
        } ?>
    </div>
<?php } ?>

?>

<div class="container site-content">
    <h1>
        <?= (!empty($url)) ? '<a href="' . $url . '">' . get_the_title() . '</a>' : get_the_title(); ?>
    </h1>
    <div class="container-fluid">
        <h2 class="events-header">This Week:</h2>
        <div class="row">
            <div class="col-sm-4 col-xs-12">
                    <?php
                        $prefix = SDES_Static::get_post_type( get_the_ID() ).'_';

                        echo do_shortcode(wpautop(get_post_meta(get_the_ID(),  $prefix.'sidecolumn', true )  )); 

                    ?>
                </div>
            <div class="col-sm-8 col-xs-12">

            <?php $args = array(
                'post_type' => 'event',
                'tag' => 'thisweek',
                'posts_per_page' => '-1',

                'meta_query' => array(
                    array(
                        'relation' => 'AND',
                        'date_query' => array(
                        'key' => 'event_date',
                        ),
                        'time_query' => array(
                        'key' => 'event_start_time',
                        'type' => 'NUMERIC',
                        ),
                    )
                ),

                'orderby' => array(
                'date_query' => 'ASC',
                'time_query' => 'ASC',
                ),
            );

            $the_query = new WP_Query($args);

            displayEvents($the_query);

            // The Query Weekly
            $args = array(
                'post_type' => 'event',
                'tag' => 'weekly',
                'posts_per_page' => '-1',
            );

            $the_query = new WP_Query($args);

            displayEvents($the_query);

                                    
            // The Query Monthly
            $args = array(
                'post_type' => 'event',
                'tag' => 'monthly',
            );

            $the_query = new WP_Query($args);
            displayEvents($the_query);
            
            ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>
