<?php
/*
* Template Name: Events
*/

get_header('second');
the_post();

?>

<div class="container">
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
                        // The Query
                        $args = array(
                            'post_type' => 'event',
                            'tag' => 'thisweek',
                            'meta_key' => 'event_date',
                            'orderby' => 'meta_value',
                            'order' => 'ASC',
                        );

                        $the_query = new WP_Query($args);

                        // The Loop
                        if ($the_query->have_posts()) {
                            echo '<div class="row">';
                            while ($the_query->have_posts()) : $the_query->the_post();

                                foreach ($posts as $event) { ?>

                                    <div class="event-group col-sm-4 col-12 mb-4">

                                        <img src="
                                        <?php
                                        echo  get_the_post_thumbnail_url(get_the_ID(), 'event-img-size');
                                        ?>
                                        " class="img-fluid" />

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

                    <h2 class="events-header">Weekly:</h2>
                    <?php
                    // The Query
                    $args = array(
                        'post_type' => 'event',
                        'tag' => 'weekly',
                    );

                    $the_query = new WP_Query($args);

                    // The Loop
                    if ($the_query->have_posts()) {
                        echo '<div class="row">';
                        while ($the_query->have_posts()) : $the_query->the_post();

                            foreach ($posts as $event) { ?>

                                <div class="event-group col-sm-4 col-12 mb-4">

                                    <img src="
                                        <?php
                                        echo  get_the_post_thumbnail_url(get_the_ID(), 'event-img-size');
                                        ?>
                                        " class="img-fluid" />

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

                    <h2 class="events-header">Monthly:</h2>
                    <?php
                    // The Query
                    $args = array(
                        'post_type' => 'event',
                        'tag' => 'monthly',
                    );

                    $the_query = new WP_Query($args);

                    // The Loop
                    if ($the_query->have_posts()) {
                        echo '<div class="row">';
                        while ($the_query->have_posts()) : $the_query->the_post();

                            foreach ($posts as $event) { ?>

                                <div class="event-group col-sm-4 col-12 mb-4">

                                    <img src="
                                        <?php
                                        echo  get_the_post_thumbnail_url(get_the_ID(), 'event-img-size');
                                        ?>
                                        " class="img-fluid" />

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

<?php get_footer(); ?>