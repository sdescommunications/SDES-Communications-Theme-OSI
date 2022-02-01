<?php
/*
* Template Name: Events
*/

get_header('second');
the_post();

?>

<div class="container site-content">
    <h1>
        <?= (!empty($url)) ? '<a href="' . $url . '">' . get_the_title() . '</a>' : get_the_title(); ?>
    </h1>
    <hr />
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-12">
                    <div class="container-fluid">
                        <h2 class="events-header">This Week:</h2>
                        <?php

                        $args = array(
                            'post_type' => 'event',
                            'tag' => 'thisweek',
                            'posts_per_page' => '-1',

                            'meta_query' => array(
                                array(
                                    'relation' => 'AND',
                                    'date_query' => array(
                                        'key' => 'event_date',
                                    ),
                                    'am_pm_query' => array(
                                        'key' => 'event_start_am_pm',
                                    ),
                                    'time_query' => array(
                                        'key' => 'event_start_time',
                                        'type' => 'NUMERIC',
                                    ),
                                )
                            ),

                            'orderby' => array(
                                'date_query' => 'ASC',
                                'am_pm_query' => 'ASC',
                                'time_query' => 'ASC',
                            ),
                        );

                        $the_query = new WP_Query($args);

                        // The Loop
                        if ($the_query->have_posts()) {
                            echo '<div class="row">';
                            while ($the_query->have_posts()) : $the_query->the_post();

                                foreach ($posts as $event) { ?>

                                    <div class="event-group col-sm-4 col-12">

                                        <img src="
                                        <?php
                                        echo  get_the_post_thumbnail_url(get_the_ID(), 'event-img-size');
                                        ?>
                                        " class="img-fluid" width='300' height='300' />

                                        <div class="event-content">
                                            <h4 class="event-title">
                                                <?php the_title();
                                                ?>
                                            </h4>
                                            <h5 class="event-agency">
                                                <?php
                                                echo get_post_meta(get_the_ID(), 'event_agency', true);
                                                ?>
                                            </h5>
                                            <div class="event-date">
                                                <?php
                                                $date = get_post_meta(get_the_ID(), 'event_date', true);

                                                $start_time = get_post_meta(get_the_ID(), 'event_start_time', true);

                                                $start_am_pm = get_post_meta(get_the_ID(), 'event_start_am_pm', true);

                                                $end_time = get_post_meta(get_the_ID(), 'event_end_time', true);

                                                $end_am_pm = get_post_meta(get_the_ID(), 'event_end_am_pm', true);

                                                if (!empty($date)) {
                                                    echo "<p>" . date('l, M j', strtotime($date));

                                                    if (!empty($start_time)) {
                                                        echo " at&nbsp" . $start_time . $start_am_pm;

                                                        if (!empty($end_time)) {
                                                            echo " - " . $end_time . $end_am_pm . "</p>";
                                                        } else {
                                                            echo "</p>";
                                                        }
                                                    } else {
                                                        echo "</p>";
                                                    }
                                                }
                                                ?>
                                            </div>
                                            <p class="event-type-location">
                                                <?php echo get_post_meta(get_the_ID(), 'event_type', true); ?>
                                                : <?php echo get_post_meta(get_the_ID(), 'event_location', true); ?></p>
                                            <p>
                                                <?php
                                                echo the_content();
                                                ?>
                                            </p>
                                            <a class="event-link" href="<?php echo get_post_meta(get_the_ID(), 'event_url', true); ?>"><?php echo get_post_meta(get_the_ID(), 'event_url_text', true); ?></a>
                                        </div>
                                    </div>
                        <?php }

                            endwhile;
                            wp_reset_postdata();
                            echo "</div>";
                        } else {

                            echo "<div class='no-events-message'><em>No events this week.</em><em>Check back later for the latest updates from OSI!</em></div>";
                        } ?>

                        <?php
                        // The Query
                        $args = array(
                            'post_type' => 'event',
                            'tag' => 'weekly',
                            'posts_per_page' => '-1',
                        );

                        $the_query = new WP_Query($args);

                        // The Loop
                        if ($the_query->have_posts()) {
                            echo "<h2 class='events-header'>Weekly:</h2>";
                            echo '<div class="row">';
                            while ($the_query->have_posts()) : $the_query->the_post();

                                foreach ($posts as $event) { ?>

                                    <div class="event-group col-sm-4 col-12">

                                        <img src="
                                        <?php
                                        echo  get_the_post_thumbnail_url(get_the_ID(), 'event-img-size');
                                        ?>
                                        " class="img-fluid" width='300' height='300' />

                                        <div class="event-content">
                                            <h4 class="event-title">
                                                <?php the_title();
                                                ?>
                                            </h4>
                                            <h5 class="event-agency">
                                                <?php
                                                echo get_post_meta(get_the_ID(), 'event_agency', true);
                                                ?>
                                            </h5>
                                            <p class="event-date">
                                                <?php
                                                $date = get_post_meta(get_the_ID(), 'event_date', true);

                                                $time = get_post_meta(get_the_ID(), 'event_time', true);

                                                if (!empty($date)) {
                                                    echo date('l, M j', strtotime($date)); ?>
                                                    at <?php
                                                    }

                                                    if (!empty($time)) {
                                                        echo $time;
                                                    } ?>
                                            </p>
                                            <p class="event-type-location">
                                                <?php echo get_post_meta(get_the_ID(), 'event_type', true); ?>
                                                : <?php echo get_post_meta(get_the_ID(), 'event_location', true); ?></p>
                                            <p>
                                                <?php
                                                echo the_content();
                                                ?>
                                            </p>
                                            <a class="event-link" href="<?php echo get_post_meta(get_the_ID(), 'event_url', true); ?>"><?php echo get_post_meta(get_the_ID(), 'event_url_text', true); ?></a>
                                        </div>
                                    </div>
                        <?php }

                            endwhile;
                            wp_reset_postdata();

                            echo "</div>";
                        } ?>

                        <?php
                        // The Query
                        $args = array(
                            'post_type' => 'event',
                            'tag' => 'monthly',
                        );

                        $the_query = new WP_Query($args);

                        // The Loop
                        if ($the_query->have_posts()) {
                            echo " <h2 class='events-header'>Monthly:</h2>";
                            echo '<div class="row">';
                            while ($the_query->have_posts()) : $the_query->the_post();

                                foreach ($posts as $event) { ?>

                                    <div class="event-group col-sm-4 col-12">

                                        <img src="
                                        <?php
                                        echo  get_the_post_thumbnail_url(get_the_ID(), 'event-img-size');
                                        ?>
                                        " class="img-fluid" width='300' height='300' />

                                        <div class="event-content">
                                            <h4 class="event-title">
                                                <?php the_title();
                                                ?>
                                            </h4>
                                            <h5 class="event-agency">
                                                <?php
                                                echo get_post_meta(get_the_ID(), 'event_agency', true);
                                                ?>
                                            </h5>
                                            <p class="event-date">
                                                <?php
                                                $date = get_post_meta(get_the_ID(), 'event_date', true);

                                                $time = get_post_meta(get_the_ID(), 'event_time', true);

                                                if (!empty($date)) {
                                                    echo date('l, M j', strtotime($date)); ?>
                                                    at <?php
                                                    }

                                                    if (!empty($time)) {
                                                        echo $time;
                                                    } ?>
                                            </p>
                                            <p class="event-type-location">
                                                <?php echo get_post_meta(get_the_ID(), 'event_type', true); ?>
                                                : <?php echo get_post_meta(get_the_ID(), 'event_location', true); ?></p>
                                            <p>
                                                <?php
                                                echo the_content();
                                                ?>
                                            </p>
                                            <a class="event-link" href="<?php echo get_post_meta(get_the_ID(), 'event_url', true); ?>"><?php echo get_post_meta(get_the_ID(), 'event_url_text', true); ?></a>
                                        </div>
                                    </div>
                        <?php }

                            endwhile;
                            wp_reset_postdata();

                            echo "</div>";
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
