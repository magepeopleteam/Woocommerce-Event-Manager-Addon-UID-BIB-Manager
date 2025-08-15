<?php
if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

function mep_bib_cpt_tax(){
	$labels = array(
        'name'                       => _x( 'UId/BiB Cat','mage-eventpress-uid-number' ),
        'singular_name'              => _x( 'UId/BiB Cat','mage-eventpress-uid-number' ),
        'menu_name'                  => _x( 'UId/BiB Cat','mage-eventpress-uid-number' ),
        'all_items'                  => __( 'All UId/BiB Cat', 'mage-eventpress-uid-number' ),
	);

	$args = array(
		'hierarchical'          => true,
		"public" 				=> true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'mep_uid_cat' ),
		'show_in_rest'          => true,
		'rest_base'             => 'mep_uid_cat',
	);
    register_taxonomy('mep_uid_cat', 'mep_events', $args);
}
add_action("init","mep_bib_cpt_tax",10);

add_action('mep_uid_cat_add_form', function() {
    ?>
    <style>
        /* Hide slug, description, parent fields on Add New term form */
        .form-field.term-slug-wrap,
        .form-field.term-description-wrap,
        .form-field.term-parent-wrap {
            display: none !important;
        }
    </style>
    <?php
});

add_action('mep_uid_cat_edit_form', function() {
    ?>
    <style>
        /* Hide slug, description, parent fields on Edit term form */
        .term-slug-wrap,
        .term-description-wrap,
        .term-parent-wrap {
            display: none !important;
        }
    </style>
    <?php
});


/**
 * Add custom field when creating a new mep_uid_cat term
 */
add_action( 'mep_uid_cat_add_form_fields', 'mep_uid_cat_add_form_field' );

function mep_uid_cat_add_form_field() {
    ?>
    <div class="form-field">
    <label for="mep_uid_start"><?php _e( 'UID/BIB Start Number', 'mage-eventpress-uid-number' ); ?></label>
    <input type="text" name="mep_uid_start" id="mep_uid_start" value="" placeholder="<?php esc_attr_e('e.g., 1001', 'mage-eventpress-uid-number'); ?>">
    <p class="description"><?php _e( 'Enter the UID/BIB start number', 'mage-eventpress-uid-number' ); ?></p>
    </div>

    <div class="form-field">
    <label for="mep_uid_end"><?php _e( 'UID/BIB End Number', 'mage-eventpress-uid-number' ); ?></label>
    <input type="text" name="mep_uid_end" id="mep_uid_end" value="" placeholder="<?php esc_attr_e('e.g., 1002', 'mage-eventpress-uid-number'); ?>">
    <p class="description"><?php _e( 'Enter the UID/BIB end number', 'mage-eventpress-uid-number' ); ?></p>
    </div>
    <?php
}


/**
 * Add custom field when editing an existing mep_uid_cat term
 */
add_action( 'mep_uid_cat_edit_form_fields', 'mep_uid_cat_edit_form_field' );

function mep_uid_cat_edit_form_field( $term ) {
    // Get previously saved value
    $uid_start = get_term_meta( $term->term_id, 'mep_uid_start', true );
    $uid_end = get_term_meta( $term->term_id, 'mep_uid_end', true );
    ?>
    <tr class="form-field">
        <th scope="row">
            <label for="mep_uid_start"><?php _e('UID/BIB Start Number', 'mage-eventpress-uid-number'); ?></label>
        </th>
        <td>
            <input type="text" name="mep_uid_start" id="mep_uid_start" value="<?php echo esc_attr( $uid_start ); ?>" placeholder="<?php esc_attr_e('e.g., 1001', 'mage-eventpress-uid-number'); ?>">
            <p class="description"><?php _e('Enter the UID/BIB start number', 'mage-eventpress-uid-number'); ?></p>
        </td>
    </tr>

    <tr class="form-field">
        <th scope="row">
            <label for="mep_uid_end"><?php _e('UID/BIB End Number', 'mage-eventpress-uid-number'); ?></label>
        </th>
        <td>
            <input type="text" name="mep_uid_end" id="mep_uid_end" value="<?php echo esc_attr( $uid_end ); ?>" placeholder="<?php esc_attr_e('e.g., 1002', 'mage-eventpress-uid-number'); ?>">
            <p class="description"><?php _e('Enter the UID/BIB end number', 'mage-eventpress-uid-number'); ?></p>
        </td>
    </tr>   

    <?php
}


/**
 * Save custom field when creating a new mep_uid_cat term
 */
add_action( 'created_mep_uid_cat', 'mep_uid_cat_save_term_meta' );

function mep_uid_cat_save_term_meta( $term_id ) {
    if ( isset( $_POST['mep_uid_start'] ) ) {
        update_term_meta(
            $term_id,
            'mep_uid_start',
            sanitize_text_field( $_POST['mep_uid_start'] )
        );
    }
    if ( isset( $_POST['mep_uid_end'] ) ) {
        update_term_meta(
            $term_id,
            'mep_uid_end',
            sanitize_text_field( $_POST['mep_uid_end'] )
        );
    }
}

/**
 * Save custom field when editing an existing mep_uid_cat term
 */
add_action( 'edited_mep_uid_cat', 'mep_uid_cat_update_term_meta' );

function mep_uid_cat_update_term_meta( $term_id ) {
    if ( isset( $_POST['mep_uid_start'] ) ) {
        update_term_meta(
            $term_id,
            'mep_uid_start',
            sanitize_text_field( $_POST['mep_uid_start'] )
        );
    }
    if ( isset( $_POST['mep_uid_end'] ) ) {
        update_term_meta(
            $term_id,
            'mep_uid_end',
            sanitize_text_field( $_POST['mep_uid_end'] )
        );
    }
}

add_filter('manage_edit-mep_uid_cat_columns', function($columns) {
    // Insert after the 'name' column
    $new_columns = [];
    foreach ($columns as $key => $title) {
        $new_columns[$key] = $title;
        if ($key === 'name') {
            $new_columns['mep_uid_start'] = 'UID/BIB Start number';
            $new_columns['mep_uid_end'] = 'UID/BIB End Number';
        }
    }
    return $new_columns;
});

add_filter('manage_mep_uid_cat_custom_column', function($content, $column_name, $term_id) {
    if ($column_name === 'mep_uid_start') {
        $start = get_term_meta($term_id, 'mep_uid_start', true);
        $content = $start ? esc_html($start) : '—';
    }
    elseif ($column_name === 'mep_uid_end') {
        $end = get_term_meta($term_id, 'mep_uid_end', true);
        $content = $end ? esc_html($end) : '—';
    }
    return $content;
}, 10, 3);

add_filter('manage_edit-mep_uid_cat_columns', function($columns) {
    return [
        'cb' => $columns['cb'] ?? '',       // Keep the checkbox for bulk actions (optional)
        'name' => 'Name',
        'mep_uid_start' => 'UID/BIB Start number',
        'mep_uid_end' => 'UID/BIB End Number',
    ];
});

add_filter('manage_mep_uid_cat_custom_column', function($content, $column_name, $term_id) {
    if ($column_name === 'mep_uid_start') {
        $start = get_term_meta($term_id, 'mep_uid_start', true);
        return $start ? esc_html($start) : '—';
    }
    if ($column_name === 'mep_uid_end') {
        $end = get_term_meta($term_id, 'mep_uid_end', true);
        return $end ? esc_html($end) : '—';
    }
    return $content;
}, 10, 3);

add_action('admin_menu', function() {
    add_submenu_page(
        'edit.php?post_type=mep_events', // Parent slug
        'UID/BiB Numbers',               // Page title
        'UID/BiB Numbers',               // Menu title
        'manage_options',                // Capability
        'uid_bib_numbers',               // Menu slug
        'uid_bib_numbers_page_callback',// Callback function
        21                              // Menu position (after mep_uid_cat's assumed 20)
    );
});


function uid_bib_numbers_page_callback() {
    // Get all terms from 'mep_uid_cat'
    $terms = get_terms([
        'taxonomy' => 'mep_uid_cat',
        'hide_empty' => false,
    ]);
    $paged = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $per_page = 50;
    ?>
    <div class="wrap">
    <h1><?php _e('UID/BiB Numbers', 'mage-eventpress-uid-number'); ?></h1>

        <?php
        if ( isset($_POST['generate_bib']) && !empty($_POST['mep_uid_cat_select']) ) {
            $selected_term_id = intval($_POST['mep_uid_cat_select']);

            $start_number = intval(get_term_meta($selected_term_id, 'mep_uid_start', true));
            $end_number = intval(get_term_meta($selected_term_id, 'mep_uid_end', true));

            if ($start_number > 0 && $end_number >= $start_number) {
            $created_posts = 0;
            for ($num = $start_number; $num <= $end_number; $num++) {
                $formatted_num = str_pad($num, 4, '0', STR_PAD_LEFT);
                $post_data = [
                'post_title'  => $formatted_num,
                'post_type'   => 'mep_uid_number',
                'post_status' => 'publish',
                'meta_input'  => [
                    'mep_uid_number' => $formatted_num,
                    'mep_uid_cat' => $selected_term_id,
                    'mep_uid_status' => 'Available',
                ],
                ];

                $post_id = wp_insert_post($post_data);
                if ($post_id && !is_wp_error($post_id)) {
                $created_posts++;
                }
            }

            update_term_meta($selected_term_id, 'mep_uid_generated_status','done');

            echo '<div style="margin-top:20px; color:green; font-weight:bold;">';
            printf( esc_html__( 'Successfully created %1$s posts for numbers %2$s to %3$s.', 'mage-eventpress-uid-number' ), $created_posts, str_pad($start_number, 4, '0', STR_PAD_LEFT), str_pad($end_number, 4, '0', STR_PAD_LEFT) );
            echo '</div>';
            } else {
            echo '<div style="margin-top:20px; color:red;">' . esc_html__( 'Invalid start or end number.', 'mage-eventpress-uid-number' ) . '</div>';
            }
        }
        ?>

        <form method="post" action="">
            <label for="mep_uid_cat_select"><?php _e('Select Category:', 'mage-eventpress-uid-number'); ?></label>
            <select name="mep_uid_cat_select" id="mep_uid_cat_select" style="min-width: 250px; margin-left: 10px;">
                <option value=""><?php esc_html_e('-- Select a Category --', 'mage-eventpress-uid-number'); ?></option>
                <?php foreach ($terms as $term): 
                    $status = get_term_meta($term->term_id, 'mep_uid_generated_status', true) ? get_term_meta($term->term_id, 'mep_uid_generated_status', true) : 'not_done';
                    if($status === 'not_done') {                                       
                    ?>
                    <option value="<?php echo esc_attr($term->term_id); ?>" <?php selected( isset($_POST['mep_uid_cat_select']) ? intval($_POST['mep_uid_cat_select']) : 0, $term->term_id ); ?>>
                        <?php echo esc_html($term->name); ?>
                    </option>
                <?php } endforeach; ?>
            </select>
            <input type="submit" name="generate_bib" class="button button-primary" value="<?php esc_attr_e('Generate BIB Number', 'mage-eventpress-uid-number'); ?>" style="margin-left: 15px;">
        </form>


        <hr style="margin:30px 0;">
    <h2><?php _e('Generated UID/BiB Numbers', 'mage-eventpress-uid-number'); ?></h2>
        <?php mep_get_generated_uid_number_list($per_page, $paged); ?>
    </div>
    <?php
}




function mep_get_generated_uid_number_list($per_page, $paged) {

    // Handle delete action
    if (isset($_GET['action'], $_GET['uid_id']) && $_GET['action'] === 'mep_delete_uid_number') {
        $uid_id = intval($_GET['uid_id']);
        if (isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'mep_delete_uid_number_' . $uid_id)) {

            // wp_delete_post($uid_id, true);
            $uid_post_id = $uid_id;

            $attendee_id = get_post_meta($uid_id,'mep_uid_attendee_id',true);


            update_post_meta( $attendee_id, 'ea_uid_number', '' );
            update_post_meta( $attendee_id, 'uid_post_id', '' );


            // Reset UID/BiB number post meta
            update_post_meta( $uid_post_id, 'mep_uid_status', 'Available' );
            update_post_meta( $uid_post_id, 'mep_uid_attendee_id', '' );
            update_post_meta( $uid_post_id, 'mep_uid_event_id', '' );
            update_post_meta( $uid_post_id, 'mep_uid_order_id', '' );









            $redirect_url = add_query_arg(
                ['paged' => $paged],
                remove_query_arg(['action','uid_id','_wpnonce'], wp_get_referer() ?: admin_url('edit.php?post_type=mep_uid_number&page=' . $_GET['page']))
            );
            wp_redirect($redirect_url);
            exit;
        } else {
            wp_die('Security check failed.');
        }
    }

    // Get filter values
    $filter_event_id = isset($_GET['filter_event_id']) ? sanitize_text_field($_GET['filter_event_id']) : '';
    $filter_cat_id   = isset($_GET['filter_cat_id']) ? sanitize_text_field($_GET['filter_cat_id']) : '';
    $filter_order_id = isset($_GET['filter_order_id']) ? sanitize_text_field($_GET['filter_order_id']) : '';
    $filter_status   = isset($_GET['filter_status']) ? sanitize_text_field($_GET['filter_status']) : '';

    $terms = get_terms(['taxonomy' => 'mep_uid_cat','hide_empty' => false]);

    // Build meta query
    $meta_query = [];
    if ($filter_event_id !== '') $meta_query[] = ['key'=>'mep_uid_event_id','value'=>$filter_event_id];
    if ($filter_cat_id !== '')   $meta_query[] = ['key'=>'mep_uid_cat','value'=>$filter_cat_id];
    if ($filter_order_id !== '') $meta_query[] = ['key'=>'mep_uid_order_id','value'=>$filter_order_id];
    if ($filter_status !== '')   $meta_query[] = ['key'=>'mep_uid_status','value'=>$filter_status];
    if (count($meta_query) > 1) $meta_query['relation'] = 'AND';

    $args = [
        'post_type' => 'mep_uid_number',
        'posts_per_page' => $per_page,
        'paged' => $paged,
        'meta_key' => 'mep_uid_number',
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
    ];
    if (!empty($meta_query)) $args['meta_query'] = $meta_query;

    $query = new WP_Query($args);
    $count = $query->found_posts;

    // Filter Form (using WP native classes)
    ?>
    <form method="get" class="wp-filter" style="margin-bottom: 15px;padding:20px">
        <input type="hidden" name="page" value="<?php echo esc_attr($_GET['page'] ?? ''); ?>">
        <input type="hidden" name="post_type" value="<?php echo esc_attr($_GET['post_type'] ?? 'mep_uid_number'); ?>">

        <label style="margin-right:10px;">
            Event: 
            <select name="filter_event_id">
                <option value="">— Select Event —</option>
                <?php
                $events = get_posts(['post_type'=>'mep_events','posts_per_page'=>-1,'orderby'=>'title','order'=>'ASC']);
                foreach ($events as $event) echo '<option value="'.esc_attr($event->ID).'" '.selected($filter_event_id,$event->ID,false).'>'.esc_html($event->post_title).' (ID: '.$event->ID.')</option>';
                ?>
            </select>
        </label>

        <label style="margin-right:10px;">
            Category:
            <select name="filter_cat_id">
                <option value="">— Select Category —</option>
                <?php foreach ($terms as $term) {
                    $status = get_term_meta($term->term_id,'mep_uid_generated_status',true) ?: 'not_done';
                    if ($status != 'not_done') echo '<option value="'.esc_attr($term->term_id).'" '.selected($filter_cat_id,$term->term_id,false).'>'.esc_html($term->name).'</option>';
                } ?>
            </select>
        </label>

        <label style="margin-right:10px;">
            Order ID: <input type="text" name="filter_order_id" value="<?php echo esc_attr($filter_order_id); ?>">
        </label>

        <label style="margin-right:10px;">
            Status:
            <select name="filter_status">
                <option value="">All</option>
                <option value="Used" <?php selected($filter_status,'Used'); ?>>Used</option>
                <option value="Available" <?php selected($filter_status,'Available'); ?>>Available</option>
            </select>
        </label>

        <button type="submit" class="button button-primary">Filter</button>
    </form>
    <?php

    if ($count > 0 && $query->have_posts()) {
        echo '<table class="widefat striped">';
        echo '<thead><tr>
                <th>Number</th>
                <th>Category</th>
                <th>Attendee</th>
                <th>Ticket</th>
                <th>Event</th>
                <th>Order</th>
                <th>Status</th>
                <th>Action</th>
              </tr></thead><tbody>';

        while ($query->have_posts()) {
            $query->the_post();
            $uid_id = get_the_ID();
            $num = get_post_meta($uid_id,'mep_uid_number',true);
            $catID = get_post_meta($uid_id,'mep_uid_cat',true);
            $catName = $catID ? get_term($catID)->name : '-';
            $status = get_post_meta($uid_id,'mep_uid_status',true);
            $attendee_id = get_post_meta($uid_id,'mep_uid_attendee_id',true) ?: '-';
            $event_id = get_post_meta($uid_id,'mep_uid_event_id',true) ?: '-';
            $order_id = get_post_meta($uid_id,'mep_uid_order_id',true) ?: '-';

            $delete_url = wp_nonce_url(
                add_query_arg([
                    'action'=>'mep_delete_uid_number',
                    'uid_id'=>$uid_id,
                    'page'=>$_GET['page'] ?? '',
                    'post_type'=>$_GET['post_type'] ?? 'mep_uid_number',
                    'paged'=>$paged,
                ], admin_url('edit.php?post_type=mep_uid_number')),
                'mep_delete_uid_number_'.$uid_id
            );

            // Optional: highlight rows based on status
            $row_class = ($status === 'Used') ? ' style="background:#fce4e4;"' : '';

            echo '<tr'.$row_class.'>
                    <td>'.esc_html($num).'</td>
                    <td>'.esc_html($catName).'</td>
                    <td>'.esc_html(get_post_meta($attendee_id,'ea_name',true)).'</td>
                    <td>'.esc_html(get_post_meta($attendee_id,'ea_ticket_no',true)).'</td>
                    <td>'.esc_html(get_the_title($event_id)).'</td>
                    <td>'.esc_html($order_id).'</td>
                    <td>'.esc_html($status).'</td>
                    <td><a href="'.esc_url($delete_url).'" class="button button-small" onclick="return confirm(\'Are you sure to delete this UID/BiB number? This action will make this BIB Number available again and remove the BIB number from the Attendee.\');">Reset</a></td>
                  </tr>';
        }

        echo '</tbody></table>';

        // Pagination
        echo '<div class="tablenav"><div class="tablenav-pages">';
        echo paginate_links(['total'=>$query->max_num_pages,'current'=>$paged,'base'=>add_query_arg('paged','%#%'),'format'=>'']);
        echo '</div></div>';
    } else {
        echo '<p>No UID/BiB Numbers found.</p>';
    }

    wp_reset_postdata();
}




/**
 * Get the first available UID/BiB number for a given category.
 *
 * @param int|null $cat_id Optional. mep_uid_cat term ID. If null, searches all.
 * @return int|null The available number, or null if none found.
 */
function get_first_available_bib_number( $cat_id = null ) {
    $meta_query = [
        [
            'key'   => 'mep_uid_status',
            'value' => 'Available',
        ]
    ];
    if ( $cat_id ) {
        $meta_query[] = [
            'key'   => 'mep_uid_cat',
            'value' => intval($cat_id),
        ];
    }
    $query = new WP_Query([
        'post_type'      => 'mep_uid_number',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
        'meta_query'     => $meta_query,
        'fields'         => 'ids',
    ]);

    if ( $query->have_posts() ) {
        $post_id = $query->posts[0];
        return intval( get_post_meta( $post_id, 'mep_uid_number', true ) );
    }
    return null;
}

add_filter( 'mep_add_extra_column', 'mep_uid_dropdown_in_empty_column_name' );
	function mep_uid_dropdown_in_empty_column_name() {
		?>
            <th width="20%"><?php _e( 'UID/BIB Cat', 'mage-eventpress-uid-number' ); ?></th>           
		<?php 
	}

add_action( 'mep_add_extra_column_empty', 'mep_uid_dropdown_in_empty_row' );
	function mep_uid_dropdown_in_empty_row() {
    $terms = get_terms([
        'taxonomy' => 'mep_uid_cat',
        'hide_empty' => false,
    ]);
			?>
            <td>

            <select name="mep_uid_cat_select[]" id="mep_uid_cat_select" style="min-width: 250px; margin-left: 10px;">
                <option value=""><?php esc_html_e('-- Select a Category --', 'mage-eventpress-uid-number'); ?></option>
                <?php foreach ($terms as $term): 
                    $status = get_term_meta($term->term_id, 'mep_uid_generated_status', true) ? get_term_meta($term->term_id, 'mep_uid_generated_status', true) : 'not_done';
                    if($status != 'not_done') {                                       
                    ?>
                    <option value="<?php echo esc_attr($term->term_id); ?>" <?php selected( isset($_POST['mep_uid_cat_select']) ? intval($_POST['mep_uid_cat_select']) : 0, $term->term_id ); ?>>
                        <?php echo esc_html($term->name); ?>
                    </option>
                <?php } endforeach; ?>
            </select>                        
            </td>            
			<?php		
	}



add_filter( 'mep_add_extra_input_box', 'mep_uid_dropdown_row' );
function mep_uid_dropdown_row( $field ) {
    $terms = get_terms([
        'taxonomy'   => 'mep_uid_cat',
        'hide_empty' => false,
    ]);
    $selected_uid_cat = isset( $field['mep_uid_cat'] ) ? $field['mep_uid_cat'] : 0;
    //  $mep_event_ticket_type  = get_post_meta( get_the_ID(), 'mep_event_ticket_type', true );
    // print_r( $mep_event_ticket_type );
    // $uid_cat = mep_get_uid_cat_by_ticket_type( $mep_event_ticket_type, '5 Mile Run' );
    // echo get_first_available_bib_number($uid_cat);
    ?>
    <td>
        <select name="mep_uid_cat_select[]" id="mep_uid_cat_select" style="min-width: 250px; margin-left: 10px;">
            <option value=""><?php esc_html_e('-- Select a Category --', 'mage-eventpress-uid-number'); ?></option>
            <?php foreach ( $terms as $term ): 
                $status = get_term_meta( $term->term_id, 'mep_uid_generated_status', true ) ?: 'not_done';
                if ( $status != 'not_done' ) { ?>
                    <option value="<?php echo esc_attr( $term->term_id ); ?>" <?php selected( $selected_uid_cat, $term->term_id ); ?>>
                        <?php echo esc_html( $term->name ); ?>
                    </option>
            <?php } endforeach; ?>
        </select>
    </td>
    <?php
}


add_filter( 'mep_ticket_type_arr_save', 'mep_uid_dropdown_in_empty_row_save_data', 99 );
	function mep_uid_dropdown_in_empty_row_save_data( $data ) {
		$mep_uid_cat_select = $_POST['mep_uid_cat_select'] ? $_POST['mep_uid_cat_select'] : [];	
		if ( sizeof( $mep_uid_cat_select ) > 0 ) {
			$count = count( $mep_uid_cat_select );
			for ( $i = 0; $i < $count; $i ++ ) {
				$new[ $i ]['mep_uid_cat'] = ! empty( $mep_uid_cat_select[ $i ] ) ? stripslashes( strip_tags( $mep_uid_cat_select[ $i ] ) ) : '';
			}
			$final_data = mep_merge_saved_array( $data, $new );
		} else {
			$final_data = $data;
		}
		return $final_data;
	}


function mep_get_uid_cat_by_ticket_type( $mep_event_ticket_type, $ticket_type ) {
    if ( ! is_array( $mep_event_ticket_type ) || empty( $ticket_type ) ) {
        return '';
    }

    foreach ( $mep_event_ticket_type as $ticket_option ) {
        if ( isset( $ticket_option['option_name_t'], $ticket_option['mep_uid_cat'] ) 
            && $ticket_option['option_name_t'] === $ticket_type ) {
            return intval( $ticket_option['mep_uid_cat'] );
        }
    }

    return '';
}


/**
 * Get a post ID by a meta key and meta value for post type `mep_uid_number`.
 *
 * @param string $meta_key   Meta key to search.
 * @param string $meta_value Meta value to match.
 * @return int|null          Post ID if found, null otherwise.
 */
function get_uid_number_post_id( $meta_key, $meta_value ) {
    $posts = get_posts([
        'post_type'   => 'mep_uid_number',
        'meta_key'    => $meta_key,
        'meta_value'  => $meta_value,
        'fields'      => 'ids',
        'numberposts' => 1
    ]);

    return ! empty( $posts ) ? (int) $posts[0] : null;
}


add_filter( 'mep_event_attendee_dynamic_data', 'mep_sp_event_attendee_data_save', 10, 6 );
function mep_sp_event_attendee_data_save( $the_array, $pid, $type, $order_id, $event_id, $_user_info ) {

    // $mep_event_ticket_type  = get_post_meta( $event_id, 'mep_event_ticket_type', true );
    // $ticket_type            =  $_user_info['ticket_name'] ? stripslashes( sanitize_text_field( $_user_info['ticket_name'] ) ) : 'NoTicketType';
    
    $event_id               = get_post_meta($pid,'ea_event_id',true);
    $mep_event_ticket_type  = get_post_meta( $event_id, 'mep_event_ticket_type', true );
    $ticket_type            = get_post_meta($pid,'ea_ticket_type',true);

    $the_uid_cat            = mep_get_uid_cat_by_ticket_type( $mep_event_ticket_type, $ticket_type );
    $uid_bib_number         = get_first_available_bib_number( $the_uid_cat );
    $uid_post_id            = get_uid_number_post_id( 'mep_uid_number', $uid_bib_number );

    update_post_meta($uid_post_id,'mep_uid_status','Used');

    // Add first value ea_ticket_type ea_event_id ea_event_name ea_order_id ea_user_id
    $the_array[] = array(
        'name'  => 'ea_uid_cat',
        'value' => $the_uid_cat
    );

    // Add second value
    $the_array[] = array(
        'name'  => 'ea_uid_number',
        'value' => $uid_bib_number
    );

    $the_array[] = array(
        'name'  => 'uid_post_id',
        'value' => $uid_post_id
    );
    
    return $the_array;
}


/**
 * Get all attendee post IDs with their `uid_post_id` values
 * from `mep_events_attendees` posts matching both `ea_event_id` and `ea_order_id`.
 *
 * @param string|int $event_id Event ID to match.
 * @param string|int $order_id Order ID to match.
 * @return array     Array of arrays: [ 'post_id' => X, 'uid_post_id' => Y ]
 */
function get_attendees_with_uid_post_id( $event_id, $order_id ) {
    $posts = get_posts([
        'post_type'   => 'mep_events_attendees',
        'meta_query'  => [
            'relation' => 'AND',
            [
                'key'   => 'ea_event_id',
                'value' => $event_id,
            ],
            [
                'key'   => 'ea_order_id',
                'value' => $order_id,
            ]
        ],
        'fields'      => 'ids',
        'numberposts' => -1 // get all matches
    ]);

    $results = [];

    foreach ( $posts as $post_id ) {
        $uid_post_id = get_post_meta( $post_id, 'uid_post_id', true );
        $results[] = [
            'post_id'     => $post_id,
            'uid_post_id' => $uid_post_id
        ];
    }

    return $results;
}

function update_uid_post_meta_by_event_and_order( $event_id, $order_id ) {
    $attendees = get_attendees_with_uid_post_id( $event_id, $order_id );

    if ( empty( $attendees ) ) {
        return false; // nothing to update
    }

    foreach ( $attendees as $attendee ) {
        $attendee_post_id = $attendee['post_id'];
        $uid_post_id      = $attendee['uid_post_id'];

        if ( $uid_post_id ) {
            update_post_meta( $uid_post_id, 'mep_uid_status', 'Used' );
            update_post_meta( $uid_post_id, 'mep_uid_attendee_id', $attendee_post_id );
            update_post_meta( $uid_post_id, 'mep_uid_event_id', $event_id );
            update_post_meta( $uid_post_id, 'mep_uid_order_id', $order_id );
        }
    }

    return true;
}


function update_uid_post_id_status_available( $event_id, $order_id ) {
    $attendees = get_attendees_with_uid_post_id( $event_id, $order_id );

    if ( empty( $attendees ) ) {
        return false; // nothing to update
    }

    foreach ( $attendees as $attendee ) {
        $attendee_post_id = $attendee['post_id'];
        $uid_post_id      = $attendee['uid_post_id'];

        if ( $uid_post_id ) {
            update_post_meta( $uid_post_id, 'mep_uid_status', 'Available' );
            update_post_meta( $uid_post_id, 'mep_uid_attendee_id', '' );
            update_post_meta( $uid_post_id, 'mep_uid_event_id', '');
            update_post_meta( $uid_post_id, 'mep_uid_order_id', '' );
        }
    }

    return true;
}

add_action('mep_wc_order_status_change_single', 'mep_change_uid_number_status', 10, 5);
function mep_change_uid_number_status( $order_status,$event_id,$order_id,$cn, $event_arr) {
        $_order_status              = ! empty( $_user_set_status ) ? $_user_set_status : array( 'processing', 'completed' );
        if (get_post_type($event_id) == 'mep_events') {             
            if( in_array( $order_status, $_order_status )) {
                update_uid_post_meta_by_event_and_order( $event_id, $order_id );
            }else{
                update_uid_post_id_status_available( $event_id, $order_id );
            }           
        }      
}

add_action( 'mep_pdf_event_multidate', 'mep_uid_no_show_in_pdf', 10, 4 );
function mep_uid_no_show_in_pdf( $ticket_id, $event_id, $order_id, $ticket_type ) {
			$uid_no = get_post_meta( $ticket_id, 'ea_uid_number', true );
			if ( $uid_no ) {
				?>
				<li><strong><?php _e( 'BIB Number:', 'mage-eventpress-sp' ) ?></strong> <?php echo $uid_no; ?></li>
				<?php
			}
		}


add_action('mep_attendee_list_heading','mep_uid_in_attendee_column_head');
function mep_uid_in_attendee_column_head(){
    ?>
        <th><?php _e('BIB Number','mage_eventpress'); ?></th>
    <?php
}

add_action('mep_attendee_list_item','mep_uid_no_column_data');
function mep_uid_no_column_data($attendee_id){
 ?>
    <td>
        <?php echo get_post_meta( $attendee_id, 'ea_uid_number', true ); ?>
    </td>
 <?php
}


add_filter( 'mep_csv_fixed_cols', 'mep_sp_add_seat_col_name_in_csv' );
add_filter( 'mep_csv_fixed_cols_data', 'mep_sp_add_seat_col_data_in_csv', 10, 2 );

function mep_sp_add_seat_col_name_in_csv( $current_data ) {
			$uid_no = array( __( 'BIB No', 'mage-eventpress-sp' ) );
			return array_merge( $current_data, $uid_no );
}
function mep_sp_add_seat_col_data_in_csv( $current_data, $post_id ) {
			$uid_no = array( get_post_meta( $post_id, 'ea_uid_number', true ) );
			return array_merge( $current_data, $uid_no );
}

add_action( 'mep_attendee_table_row_end', 'mep_uid_show_in_attendee_details_page', 10, 4 );
function mep_uid_show_in_attendee_details_page( $ticket_id ) {
			$uid_no = get_post_meta( $ticket_id, 'ea_uid_number', true );
			if ( $uid_no ) {
				?>
				<tr>
						<td><?php _e( 'BIB No:', 'mage-eventpress-sp' ) ?></td>
						<td><strong><?php echo $uid_no; ?> </strong> </td>
					</tr>
				<?php
			}
}