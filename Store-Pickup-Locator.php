<?php

/**
 * Plugin Name: Store Pickup Locator
 * Plugin URI: https://www.yourwebsiteurl.com/
 * Description: This is the Store Pickup Locator I ever created.
 * Version: 1.0
 * Author: Excellence technologies
 * Author URI: http://yourwebsiteurl.com/
 **/

    wp_enqueue_style( 'bootstrap', plugins_url('css/bootstrap.min.css',__FILE__ ),true,'1.1','all');
    wp_enqueue_style( 'ownstyle',plugins_url('css/style.css',__FILE__ ));


    add_action('admin_menu', 'my_menu_pages');
    function my_menu_pages(){
        add_menu_page('My Page Title', 'Excellenec Shipping', 'manage_options', 'show-menu', 'my_menu_output','',6 );
        add_submenu_page('show-menu', 'Submenu Page Title', 'Show Store', 'manage_options', 'show-menu','my_menu_output' );
        add_submenu_page('show-menu', 'Submenu Page Title2', 'Add new store', 'manage_options', 'add-store','my_submenu_output' );
    }
        function my_menu_output(){
            global $wpdb;
            $results = $wpdb->get_results( "SELECT * FROM wp_store"); 

            echo '<a href="admin.php?page=add-store"><button type="button" id="add-new" class="btn btn-primary">Add Store</button></a>';
            if(!empty($results))   
            {    
                echo'
                <table class="table">
                <thead>
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Title</th>
                    <th scope="col">Street-address</th>
                    <th scope="col">Zip/Postal Code</th>
                    <th scope="col">Latitude*</th>
                    <th scope="col">Longitude*</th>
                    <th scope="col">Status</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>';

                foreach($results as $row){?>
                  <tr>
                    <th scope="row"><?php echo $row->id ?></th>
                    <td><?php echo $row->title ?></td>
                    <td><?php echo $row->address ?></td>
                    <td><?php echo $row->zipcode ?></td>
                    <td><?php echo $row->latitude ?></td>
                    <td><?php echo $row->longitude ?></td>
                    <td>
                        <?php if ($row->status == 1){ echo '<a href="#"><button type="button"  class="btn btn-success">Active</button></a>';}else{ echo '<a href="#"><button type="button"  class="btn btn-danger">Deactive</button></a>';} ?>
                    
                    </td>
                    <td><?php echo '<a href="admin.php?page=show-menu&id='.$row->id.'"><button type="button"  class="btn btn-info">Delete</button></a>' ?></td>
                  </tr>
                 <?php } ?>
                </tbody>
              </table>
              <?php
            } 
            
            //delete store
            $delete_id = $_GET['id'];
            if(isset($delete_id)){
                $id = $delete_id;
                $table = 'wp_store';
                $wpdb->delete( $table, array( 'id' => $id ) );
            }
        }
        function my_submenu_output(){
            global $wpdb;
        ?><form action="?page=add-store" method='post' >
        <div class="form-group">
          <label for="title">Title:</label>
          <input type="title" class="form-control" id="title" placeholder="Enter store name" name="title">
        </div>
        <div class="form-group">
          <label for="address">Street-address:</label>
          <input type="text" class="form-control" id="address" placeholder="Enter address" name="address">
        </div>
        <div class="form-group">
          <label for="zipcode">Zip/Postal Code:</label>
          <input type="text" class="form-control" id="zipcode" placeholder="Enter zip/postal code" name="zipcode">
        </div>
        <div class="form-group">
          <label for="latitude">Latitude*:</label>
          <input type="text" class="form-control" id="latitude" placeholder="Enter latitude" name="latitude">
        </div>
        <div class="form-group">
          <label for="longitude">Longitude*:</label>
          <input type="text" class="form-control" id="longitude" placeholder="Enter longitude" name="longitude">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
      </form><?php

        $default_row = $wpdb->get_row( "SELECT * FROM wp_store ORDER BY id DESC LIMIT 1" );
        
        if ( $default_row != null ) {
            $id = $default_row->id + 1;
        } else {
            $id = 1;
        }
        $default = array(
            'id' => $id,
            'title' => $_POST['title'],
            'address' => $_POST['address'],
            'latitude' => $_POST['latitude'],
            'longitude' => $_POST['longitude'],
            'zipcode' => $_POST['zipcode'],
            'status' => 1,
        );
        $item = shortcode_atts( $default, $_REQUEST );
        $wpdb->insert( wp_store, $item );
       
        }


