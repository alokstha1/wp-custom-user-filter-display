<?php
/**
* Template to render the pagination for users' list.
*/

if ( $total_users > $total_query ) {
    echo '<div id="wcufd-pagination" class="clearfix">';

    $current_page = max(1, $paged); //returns the highest value

    if ( isset( $_POST['permalink'] ) && !empty( $_POST['permalink'] ) ) {
        $base   = esc_url( $_POST['permalink'] ) . '%_%' ;
    } else {
        $base   = get_pagenum_link(1) . '%_%';
    }
    echo paginate_links(
        array(
           'base'       => $base,
           'format'     => 'page/%#%',
           'current'    => $current_page,
           'total'      => $total_pages,
           'prev_next'  => false,
           'type'       => 'plain',
        )
    );
    echo '</div>';
}