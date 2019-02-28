<?php
/**
* Template to display filter form
*/

global $wp_roles; // global WP_Roles $wp_roles
?>
<!-- Beginning of the Form section -->
<form class="wcufd-filter" id="wcufd-custom-filter">
    <!-- Nonce initialized -->
    <?php if ( function_exists('wp_nonce_field') ) wp_nonce_field('wcufd_nonce_users', 'validate_submit' ); ?>

    <div class="tablenav top wcufd-tablenav-top">
        <!-- Wp Roles dropdown -->
        <label for="wcufd-role-selector" class="screen-reader-text">
            <?php echo __( 'Role', 'wcufd' ); ?>
        </label>
        <select name="users_role" id="wcufd-role-selector">
            <option value=""><?php echo __( 'Select', 'wcufd' ); ?></option>
            <?php foreach ( $wp_roles->role_names as $role_slug => $role_name ) : ?>
                <option value="<?php echo $role_slug; ?>"><?php echo $role_name; ?></option>
            <?php endforeach; ?>
        </select>
        <!-- End of a Wp Roles dropdown section -->

        <!-- Order By Dropdown -->
        <label for="wcufd-order-by" class="screen-reader-text">
            <?php echo __( 'Order By', 'wcufd' ); ?>
        </label>
        <select name="users_orderby" id="wcufd-order-by">
            <option value=""><?php echo __( 'Order By', 'wcufd' ); ?></option>
            <option value="display_name"><?php echo __( 'Name', 'wcufd' ); ?></option>
            <option value="login"><?php echo __( 'Username', 'wcufd' ); ?></option>
        </select>
        <!-- End of the Order By Dropdown section -->

        <!-- Order dropdown section -->
        <label for="wcufd-order" class="screen-reader-text">
            <?php echo __( 'Order', 'wcufd' ); ?>
        </label>
        <select name="users_order" id="wcufd-order">
            <option value=""><?php echo __( 'Order', 'wcufd' ); ?></option>
            <option value="ASC"><?php echo __( 'ASC', 'wcufd' ); ?></option>
            <option value="DESC"><?php echo __( 'DESC', 'wcufd' ); ?></option>
        </select>
        <!-- End of Order dropdown section -->
        <!-- Hidden permalink passed for using in pagination -->
        <input type="hidden" name="permalink" value="<?php echo get_permalink(); ?>">

        <input type="submit" name="filterit" id="filterit" class="button" value="Filter">
    </div>
</form>
<!-- End of the Form section -->
