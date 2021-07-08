<?php
if ( isset( $_GET['inserted'] ) ) {
?>
    <div class="notice notice-success is-dismissible">
        <p>Address Inserted Done !!</p>
    </div>
<?php
}
if ( isset( $_GET['updated'] ) ) {
?>
<div class="notice notice-success is-dismissible">
    <p>Address Updated Done !!</p>
</div>
<?php
}
if ( isset( $_GET['deleted'] ) && isset( $_GET['deleted'] ) === true  ) {
    ?>
    <div class="notice notice-success is-dismissible">
        <p>Address Deleted Done !!</p>
    </div>
<?php
}
?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e( "Address Book", 'training' );?></h1>
    <a class="page-title-action" href="<?php echo admin_url('admin.php?page=training&action=new'); ?>"> New Address</a>
    <hr>
    <form method="post">
        <?php
            $table = new \Training\Admin\Address_List();
            $table->prepare_items();
            $table->search_box('search','search_id');
            $table->display();
        ?>
    </form>
</div>