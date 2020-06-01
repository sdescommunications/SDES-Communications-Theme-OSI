<?php

  $date = get_post_meta( $post->ID, 'record_date', true);
  $description = get_post_meta( $post->ID, 'record_description', true);
  $link = get_post_meta( $post->ID, 'record_file', true)['url'];

  get_header( 'second' );
  the_post();

?>

<div class="container">
  <br>
  <h1><?= get_the_title(); ?></h1>
  <hr />
  <section>
    <article class="full-width">
      <table class="table">
        <tbody>

          <?php if ( !empty( $date ) ) : ?>
            <tr>
              <th scope="row" style="width: 50px;">
                <i class="fa fa-lg fa-fw fa-clock-o"><span class="sr-only">Date</span></i>
              </th>
              <td>
                <?= date( 'F j, Y', $date ) ?>
              </td>
            </tr>
          <?php endif; ?>
          
          <?php if ( !empty( $description ) ) : ?>
            <tr>
              <th scope="row" style="width: 50px;">
                <i class="fa fa-lg fa-fw fa-info-circle"><span class="sr-only">Description</span></i>
              </th>
              <td>
                <?= $description ?>
              </td>
            </tr>
          <?php endif; ?>

          <?php if ( !empty( $link ) ) : ?>
            <tr>
              <th scope="row" style="width: 50px;">
                <i class="fa fa-lg fa-fw fa-download"><span class="sr-only">Download Link</span></i>
              </th>
              <td>
                <a href="<?= $link ?>" style="background: none; padding: 0;">Download</a>
              </td>
            </tr>
          <?php endif; ?>

        </tbody>
      </table>
      
      <a class="btn btn-callout float-right mt-3" href="<?= wp_get_referer() ?>">&lt; Back</a>
    </article>
  </section>
</div>

<?php
  get_footer();
?>