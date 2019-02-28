<?php
/**
* Template to render users' html list.
*/
?>
<!-- Start of the user listing table -->
<?php
if ( !empty( $user_query ) ) {
?>
    <table class="wp-list-table widefat fixed striped users wcufd-users-list">
        <thead>
            <tr>
                <th><?php echo __( 'Display Name', 'wcufd' ); ?></th>
                <th><?php echo __( 'Username', 'wcufd' ); ?></th>
                <th><?php echo __( 'Email', 'wcufd' ); ?></th>
            </tr>
        </thead>

        <tbody id="wcufd-the-list">
            <?php
                foreach ( $user_query as $user_value ) {
                    $user_login         = $user_value->user_login;
                    $user_email         = $user_value->user_email;
                    ?>
                    <tr>
                        <td><?php echo $user_value->display_name; ?></td>
                        <td><?php echo $user_login; ?></td>
                        <td><?php echo $user_email; ?></td>
                    </tr>
                    <?php
                }
            ?>
        </tbody>
    </table>
<?php
 } else {
        // display empty message
    ?>
    <h2><?php echo __( 'No user found!', 'wcufd' ); ?></h2>
    <?php
}
?>
<!-- End of the user listing table -->

<?php
include(plugin_dir_path( __FILE__ ). 'pagination.php'); //pagination template included.
