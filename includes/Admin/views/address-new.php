<?php
    foreach ($this->errors as $error){
?>
        <div class="notice notice-warning is-dismissible">
            <p> <?php echo $error; ?> </p>
        </div>
<?php
    }
?>



<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e( "Add New Address", 'training' );?></h1>
    <hr/>
    <form action="" method="post">
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="name"><?php _e( "Name", 'training' );?></label>
                    </th>
                    <td>
                        <input type="text" name="name" id="name" class="regular-text" value="">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="address"><?php _e( "Address", 'training' );?></label>
                    </th>
                    <td>
                        <textarea name="address" id="address" class="regular-text" value=""></textarea>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="phone"><?php _e( "Phone", 'training' );?></label>
                    </th>
                    <td>
                        <input type="number" name="phone" id="phone" class="regular-text" value="">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="email"><?php _e( "Email", 'training' );?></label>
                    </th>
                    <td>
                        <input type="email" name="email" id="email" class="regular-text" value="">
                    </td>
                </tr>
            </tbody>
        </table>

        <?php wp_nonce_field( 'new_address' ) ?>
        <?php submit_button( __("Add Address"), 'primary', 'submit_address', true, null ) ?>
    </form>
</div>