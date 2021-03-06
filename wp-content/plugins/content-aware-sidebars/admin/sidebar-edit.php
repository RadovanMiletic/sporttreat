<?php
/**
 * @package Content Aware Sidebars
 * @author Joachim Jensen <joachim@dev.institute>
 * @license GPLv3
 * @copyright 2019 by Joachim Jensen
 */

defined('ABSPATH') || exit;

final class CAS_Sidebar_Edit extends CAS_Admin
{

    /**
     * Intro tour manager
     * @var WP_Pointer_Tour
     */
    private $_tour_manager;

    /**
     * Add filters and actions for admin dashboard
     * e.g. AJAX calls
     *
     * @since  3.5
     * @return void
     */
    public function admin_hooks()
    {
        $this->_tour_manager = new WP_Pointer_Tour(CAS_App::META_PREFIX.'cas_tour');

        add_action(
            'delete_post',
            array($this,'remove_sidebar_widgets')
        );
        add_action(
            'save_post_'.CAS_App::TYPE_SIDEBAR,
            array($this,'save_post'),
            10,
            2
        );

        add_filter(
            'wp_insert_post_data',
            array($this,'add_duplicate_title_suffix'),
            99,
            2
        );
        add_filter(
            'get_edit_post_link',
            array($this,'get_edit_post_link'),
            10,
            3
        );
        add_filter(
            'get_delete_post_link',
            array($this,'get_delete_post_link'),
            10,
            3
        );

        add_action(
            'admin_enqueue_scripts',
            array($this,'add_general_scripts_styles')
        );


        if (!cas_fs()->can_use_premium_code()) {
            add_action(
                'wp_ajax_cas_dismiss_review_notice',
                array($this,'ajax_review_clicked')
            );
            add_action(
                'all_admin_notices',
                array($this,'admin_notice_review')
            );
            add_action(
                'wpca/modules/init',
                array($this,'add_modules')
            );
        }
    }

    /**
     * Add filters and actions for frontend
     *
     * @since  3.5
     * @return void
     */
    public function frontend_hooks()
    {
    }

    /**
     * Set up admin menu and get current screen
     *
     * @since  3.4
     * @return string
     */
    public function get_screen()
    {
        $post_type_object = get_post_type_object(CAS_App::TYPE_SIDEBAR);
        return add_submenu_page(
            CAS_App::BASE_SCREEN,
            $post_type_object->labels->add_new_item,
            $post_type_object->labels->add_new,
            $post_type_object->cap->edit_posts,
            CAS_App::BASE_SCREEN.'-edit',
            array($this,'render_screen')
        );
    }

    /**
     * Authorize user for screen
     *
     * @since  3.5
     * @return boolean
     */
    public function authorize_user()
    {
        return true;
    }

    /**
     * Prepare screen load
     *
     * @since  3.4
     * @return void
     */
    public function prepare_screen()
    {
        add_action(
            'cas/admin/add_meta_boxes',
            array($this,'create_meta_boxes')
        );

        global $nav_tabs, $post, $title, $active_post_lock;

        $post_type = CAS_App::TYPE_SIDEBAR;
        $post_type_object = get_post_type_object($post_type);
        $post_id = isset($_REQUEST['sidebar_id']) ? $_REQUEST['sidebar_id'] : 0;

        //process actions
        $this->process_actions($post_id);

        /**
         * Edit mode
         */
        if ($post_id) {
            $post = get_post($post_id, OBJECT, 'edit');

            if (! $post) {
                wp_die(__('The sidebar no longer exists.'));
            }
            if (! current_user_can($post_type_object->cap->edit_post, $post_id)) {
                wp_die(__('You are not allowed to edit this sidebar.'));
            }
            if ('trash' == $post->post_status) {
                wp_die(__('You cannot edit this sidebar because it is in the Trash. Please restore it and try again.'));
            }

            if (! empty($_GET['get-post-lock'])) {
                check_admin_referer('lock-post_' . $post_id);
                wp_set_post_lock($post_id);
                wp_redirect(get_edit_post_link($post_id, 'url'));
                exit();
            }

            if (! wp_check_post_lock($post->ID)) {
                $active_post_lock = wp_set_post_lock($post->ID);
                //wp_enqueue_script('autosave');
            }

            $title = $post_type_object->labels->edit_item;

        /**
         * New Mode
         */
        } else {
            if (! (current_user_can($post_type_object->cap->edit_posts) || current_user_can($post_type_object->cap->create_posts))) {
                wp_die(
                    '<p>' . __('You are not allowed to create sidebars.', 'content-aware-sidebars') . '</p>',
                    403
                );
            }

            //wp_enqueue_script( 'autosave' );

            $post = get_default_post_to_edit($post_type, true);

            $title = $post_type_object->labels->add_new_item;
        }

        $nav_tabs = array(
            'conditions' => __('Conditions', 'content-aware-sidebars'),
            'action'     => __('Action', 'content-aware-sidebars'),
            'design'     => __('Design', 'content-aware-sidebars'),
            'schedule'   => __('Schedule', 'content-aware-sidebars'),
            'advanced'   => __('Options', 'content-aware-sidebars')
        );
        $nav_tabs = apply_filters('cas/admin/nav-tabs', $nav_tabs);

        do_action('cas/admin/add_meta_boxes', $post);
    }

    /**
     * @since 3.9
     * @param WPCATypeManager $types
     *
     * @return void
     */
    public function add_modules($types)
    {
        if (!$types->has(CAS_App::TYPE_SIDEBAR)) {
            return;
        }

        $pro_label = '(Pro)';
        $type = $types->get(CAS_App::TYPE_SIDEBAR);
        $path = plugin_dir_path(__FILE__);

        require($path.'../conditions/placeholder.php');

        $module = new CASConditionPlaceholder('cas_url', __('URLs', 'content-aware-sidebars').' '.$pro_label);
        $type->add($module, 'cas_url');

        if (function_exists('bp_is_active')) {
            $module = new CASConditionPlaceholder('cas_bbp', __('BuddyPress Groups', 'content-aware-sidebars').' '.$pro_label, '', '', 'plugins');
            $type->add($module, 'cas_bbp');
        }
    }

    /**
     * Process actions
     *
     * @since  3.4
     * @param  int  $post_id
     * @return void
     */
    public function process_actions($post_id)
    {
        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
        if (isset($_POST['deletepost'])) {
            $action = 'delete';
        }

        if ($action && $post_id) {
            //wp_reset_vars( array( 'action' ) );
            $sendback = wp_get_referer();
            $sendback = remove_query_arg(
                array('action','trashed', 'untrashed', 'deleted', 'ids'),
                $sendback
            );
            if (isset($_REQUEST['_cas_section']) && $_REQUEST['_cas_section']) {
                $sendback .= $_REQUEST['_cas_section'];
            }

            $post = get_post($post_id);
            if (! $post) {
                wp_die(__('The sidebar no longer exists.', 'content-aware-sidebars'));
            }

            check_admin_referer($action . '-post_' . $post_id);

            switch ($action) {
                case 'update':

                    $post_id = $this->update_sidebar_type();

                    // Session cookie flag that the post was saved
                    if (isset($_COOKIE['wp-saving-post']) && $_COOKIE['wp-saving-post'] === $post_id . '-check') {
                        setcookie('wp-saving-post', $post_id . '-saved', time() + DAY_IN_SECONDS, ADMIN_COOKIE_PATH, COOKIE_DOMAIN, is_ssl());
                    }

                    $status = get_post_status($post_id);
                    if (isset($_POST['original_post_status']) && $_POST['original_post_status'] == $status) {
                        $message = 1;
                    } else {
                        switch ($status) {
                            case CAS_App::STATUS_SCHEDULED:
                                //gets scheduled
                                $message = 9;
                                break;
                            case CAS_App::STATUS_INACTIVE:
                                //gets deactivated
                                $message = 10;
                                break;
                            case CAS_App::STATUS_ACTIVE:
                                //gets activated
                                $message = 6;
                                break;
                            default:
                                $message = 1;
                        }
                    }

                    $sendback = add_query_arg(array(
                        'sidebar_id' => $post_id,
                        'message'    => $message,
                        'page'       => 'wpcas-edit'
                    ), $sendback);
                    wp_safe_redirect($sendback);
                    exit();
                case 'trash':

                    if (! current_user_can('delete_post', $post_id)) {
                        wp_die(__('You are not allowed to move this sidebar to the Trash.', 'content-aware-sidebars'));
                    }

                    if ($user_id = wp_check_post_lock($post_id)) {
                        $user = get_userdata($user_id);
                        wp_die(sprintf(__('You cannot move this sidebar to the Trash. %s is currently editing.', 'content-aware-sidebars'), $user->display_name));
                    }

                    if (! wp_trash_post($post_id)) {
                        wp_die(__('Error in moving to Trash.'));
                    }

                    $sendback = remove_query_arg('sidebar_id', $sendback);

                    wp_safe_redirect(add_query_arg(
                        array(
                            'page'    => 'wpcas',
                            'trashed' => 1,
                            'ids'     => $post_id
                        ),
                        $sendback
                    ));
                    exit();
                case 'untrash':

                    if (! current_user_can('delete_post', $post_id)) {
                        wp_die(__('You are not allowed to restore this sidebar from the Trash.', 'content-aware-sidebars'));
                    }

                    if (! wp_untrash_post($post_id)) {
                        wp_die(__('Error in restoring from Trash.'));
                    }

                    wp_safe_redirect(add_query_arg('untrashed', 1, $sendback));
                    exit();
                case 'delete':

                    if (! current_user_can('delete_post', $post_id)) {
                        wp_die(__('You are not allowed to delete this sidebar.', 'content-aware-sidebars'));
                    }

                    if (! wp_delete_post($post_id, true)) {
                        wp_die(__('Error in deleting.'));
                    }

                    $sendback = remove_query_arg('sidebar_id', $sendback);
                    wp_safe_redirect(add_query_arg(array(
                        'page'    => 'wpcas',
                        'deleted' => 1
                    ), $sendback));
                    exit();
                default:
                    do_action('cas/admin/action', $action, $post);
                    break;
            }
        }
    }

    /**
     * Render screen
     *
     * @since  3.4
     * @return void
     */
    public function render_screen()
    {
        global $nav_tabs, $post, $active_post_lock;

        $post_type_object = get_post_type_object($post->post_type);

        $post_id = isset($_REQUEST['sidebar_id']) ? $_REQUEST['sidebar_id'] : 0;

        $notice = false;
        $form_extra = '';
        if ('auto-draft' == $post->post_status) {
            if (isset($_REQUEST['sidebar_id'])) {
                $post->post_title = '';
            }
            //$autosave = false;
            $form_extra .= "<input type='hidden' id='auto_draft' name='auto_draft' value='1' />";
        }
        // else {
        // 	$autosave = wp_get_post_autosave( $post->ID );
        // }

        // Detect if there exists an autosave newer than the post and if that autosave is different than the post
        // if ( $autosave && mysql2date( 'U', $autosave->post_modified_gmt, false ) > mysql2date( 'U', $post->post_modified_gmt, false ) ) {
        // 	foreach ( _wp_post_revision_fields( $post ) as $autosave_field => $_autosave_field ) {
        // 		if ( normalize_whitespace( $autosave->$autosave_field ) != normalize_whitespace( $post->$autosave_field ) ) {
        // 			$notice = sprintf( __( 'There is an autosave of this post that is more recent than the version below. <a href="%s">View the autosave</a>' ), get_edit_post_link( $autosave->ID ) );
        // 			break;
        // 		}
        // 	}
        // 	// If this autosave isn't different from the current post, begone.
        // 	if ( ! $notice )
        // 		wp_delete_post_revision( $autosave->ID );
        // 	unset($autosave_field, $_autosave_field);
        // }

        //Not only for decoration
        //Older wp versions inject updated message after first h2
        if (version_compare(get_bloginfo('version'), '4.3', '<')) {
            $tag = 'h2';
        } else {
            $tag = 'h1';
        }

        if ($post_id) {
            $title = __('Edit');
        } else {
            $title = $post_type_object->labels->new_item;
        }

        echo '<div class="wrap">';
        echo '<'.$tag.'>';
        echo '<a href="'.admin_url('admin.php?page=wpcas').'">'.$post_type_object->labels->all_items.'</a> &raquo; ';
        echo esc_html($title);
        if (isset($_REQUEST['sidebar_id'])) {
            if (current_user_can('edit_theme_options')) {
                echo ' <a href="' . esc_url(admin_url('widgets.php#'.CAS_App::SIDEBAR_PREFIX.$post->ID)) . '" class="page-title-action add-new-h2">' . __('Manage Widgets', 'content-aware-sidebars') . '</a>';
            }
        }

        echo '</'.$tag.'>';

        $this->sidebar_updated_messages($post);

        echo '<form name="post" action="admin.php?page=wpcas-edit" method="post" id="post">';
        $referer = wp_get_referer();
        wp_nonce_field('update-post_' . $post->ID);
        echo '<input type="hidden" id="user-id" name="user_ID" value="'.(int)get_current_user_id().'" />';
        echo '<input type="hidden" id="_cas_section" name="_cas_section" value="" />';
        echo '<input type="hidden" id="hiddenaction" name="action" value="update" />';
        echo '<input type="hidden" id="post_author" name="post_author" value="'.esc_attr($post->post_author).'" />';
        echo '<input type="hidden" id="original_post_status" name="original_post_status" value="'.esc_attr($post->post_status).'" />';
        echo '<input type="hidden" id="referredby" name="referredby" value="'.($referer ? esc_url($referer) : '').'" />';
        echo '<input type="hidden" id="post_ID" name="sidebar_id" value="'.esc_attr($post->ID).'" />';
        if (! empty($active_post_lock)) {
            echo '<input type="hidden" id="active_post_lock" value="'.esc_attr(implode(':', $active_post_lock)).'" />';
        }
        if (get_post_status($post) != CAS_App::STATUS_INACTIVE) {
            wp_original_referer_field(true, 'previous');
        }
        echo $form_extra;

        echo '<div id="poststuff">';
        echo '<div id="post-body" class="cas-metabox-holder metabox-holder columns-2">';
        echo '<div id="post-body-content">';
        echo '<div id="titlediv">';
        echo '<div id="titlewrap">';
        echo '<label class="screen-reader-text" id="title-prompt-text" for="title">'.__('Enter title here').'</label>';
        echo '<input type="text" name="post_title" size="30" value="'.esc_attr($post->post_title).'" id="title" spellcheck="true" autocomplete="off" />';
        echo '</div></div>';
        $this->render_section_nav($nav_tabs);
        echo '</div>';
        $this->render_sections($nav_tabs, $post, $post->post_type);
        echo '</div>';
        echo '<br class="clear" />';
        echo '</div></form></div>';
    }

    /**
     * Render tab navigation
     *
     * @since  3.4
     * @param  array  $tabs
     * @return void
     */
    public function render_section_nav($tabs)
    {
        echo '<h2 class="nav-tab-wrapper js-cas-tabs hide-if-no-js " style="padding-bottom:0;">';
        foreach ($tabs as $id => $label) {
            echo '<a class="js-nav-link nav-tab nav-tab-section-'.$id.'" href="#top#section-'.$id.'">'.$label.'</a>';
        }
        echo '</h2>';
    }

    /**
     * Render meta box sections
     *
     * @since  3.4
     * @param  array    $tabs
     * @param  WP_Post  $post
     * @param  string   $post_type
     * @return void
     */
    public function render_sections($tabs, $post, $post_type)
    {
        echo '<div id="postbox-container-1" class="postbox-container">';
        do_meta_boxes(CAS_App::BASE_SCREEN.'-edit', 'side', $post);
        echo '</div>';
        echo '<div id="postbox-container-2" class="postbox-container">';
        foreach ($tabs as $id => $label) {
            $name = 'section-'.$id;
            echo '<div id="'.$name.'" class="cas-section">';
            do_meta_boxes(CAS_App::BASE_SCREEN.'-edit', $name, $post);
            echo '</div>';
        }
        //boxes across sections
        do_meta_boxes(CAS_App::BASE_SCREEN.'-edit', 'normal', $post);
        echo '</div>';
    }

    /**
     * Update sidebar post type
     *
     * @since  3.4
     * @return int
     */
    public function update_sidebar_type()
    {
        global $wpdb;

        $post_ID = (int) $_POST['sidebar_id'];
        $post = get_post($post_ID);
        $post_data['post_type'] = CAS_App::TYPE_SIDEBAR;
        $post_data['ID'] = (int) $post_ID;
        $post_data['post_title'] = $_POST['post_title'];
        $post_data['comment_status'] = 'closed';
        $post_data['ping_status'] = 'closed';
        $post_data['post_author'] = get_current_user_id();
        $post_data['menu_order'] = intval($_POST['menu_order']);

        $ptype = get_post_type_object($post_data['post_type']);

        if (!current_user_can('edit_post', $post_ID)) {
            wp_die(__('You are not allowed to edit this sidebar.', 'content-aware-sidebars'));
        } elseif (! current_user_can($ptype->cap->create_posts)) {
            return new WP_Error('edit_others_posts', __('You are not allowed to create sidebars.', 'content-aware-sidebars'));
        } elseif ($post_data['post_author'] != $_POST['post_author']
             && ! current_user_can($ptype->cap->edit_others_posts)) {
            return new WP_Error('edit_others_posts', __('You are not allowed to edit this sidebar.', 'content-aware-sidebars'));
        }

        if (isset($_POST['post_status'])) {
            $post_data['post_status'] = CAS_App::STATUS_ACTIVE;
            //if sidebar has been future before, we need to reset date
            if ($_POST['post_status'] != $_POST['original_post_status']) {
                $post_data['post_date'] = current_time('mysql');
            }
        } elseif ($_POST['sidebar_activate']) {
            $_POST['post_status'] = CAS_App::STATUS_SCHEDULED; //yoast seo expects this
            $post_data['post_status'] = CAS_App::STATUS_SCHEDULED;
            $post_data['post_date'] = $_POST['sidebar_activate'];
        } else {
            $_POST['post_status'] = CAS_App::STATUS_INACTIVE;
            $post_data['post_status'] = CAS_App::STATUS_INACTIVE;
        }

        if ($post_data['post_status'] != CAS_App::STATUS_INACTIVE
            && $_POST['sidebar_deactivate']) {
            $this->reschedule_deactivation($post_ID, $_POST['sidebar_deactivate']);
        } else {
            $this->reschedule_deactivation($post_ID);
        }

        if (isset($post_data['post_date'])) {
            $post_data['post_date_gmt'] = get_gmt_from_date($post_data['post_date']);
        }

        if (post_type_supports(CAS_App::TYPE_SIDEBAR, 'revisions')) {
            $revisions = wp_get_post_revisions($post_ID, array(
                'order'          => 'ASC',
                'posts_per_page' => 1
            ));
            $revision = current($revisions);
            // Check if the revisions have been upgraded
            if ($revisions && _wp_get_post_revision_version($revision) < 1) {
                _wp_upgrade_revisions_of_post($post, wp_get_post_revisions($post_ID));
            }
        }

        update_post_meta($post_ID, '_edit_last', $post_data['post_author']);
        $success = wp_update_post($post_data);
        wp_set_post_lock($post_ID);

        return $post_ID;
    }

    /**
     * Handle schedule for deactivation
     *
     * @since  3.4
     * @param  int    $post_id
     * @param  string $time
     * @return void
     */
    public function reschedule_deactivation($post_id, $time = false)
    {
        $name = 'cas/event/deactivate';
        if (wp_next_scheduled($name, array($post_id)) !== false) {
            wp_clear_scheduled_hook($name, array($post_id));
        }

        if ($time) {
            //Requires to be in GMT
            $utime = get_gmt_from_date($time, 'U');
            wp_schedule_single_event($utime, $name, array($post_id));
            update_post_meta($post_id, CAS_App::META_PREFIX.'deactivate_time', $time);
        } else {
            delete_post_meta($post_id, CAS_App::META_PREFIX.'deactivate_time');
        }
    }

    /**
     * Create update messages
     *
     * @param  array  $messages
     * @return array
     */
    public function sidebar_updated_messages($post)
    {
        $manage_widgets = sprintf(' <a href="%1$s">%2$s</a>', esc_url(admin_url('widgets.php#'.CAS_App::SIDEBAR_PREFIX.$post->ID)), __('Manage widgets', 'content-aware-sidebars'));
        $messages = array(
            1 => __('Sidebar updated.', 'content-aware-sidebars').$manage_widgets,
            6 => __('Sidebar activated.', 'content-aware-sidebars').$manage_widgets,
            9 => sprintf(
                __('Sidebar scheduled for: <strong>%1$s</strong>.', 'content-aware-sidebars'),
                // translators: Publish box date format, see http://php.net/date
                date_i18n(__('M j, Y @ G:i'), strtotime($post->post_date))
            ).$manage_widgets,
            10 => __('Sidebar deactivated.', 'content-aware-sidebars').$manage_widgets,
        );
        $messages = apply_filters('cas/admin/messages', $messages, $post);

        if (isset($_GET['message'])) {
            $_GET['message'] = absint($_GET['message']);
            if (isset($messages[$_GET['message']])) {
                echo '<div id="message" class="updated notice notice-success is-dismissible"><p>'.$messages[$_GET['message']].'</p></div>';
            }
        }
    }

    /**
     * Set pointers for tour and enqueue script
     *
     * @since  3.3
     * @return void
     */
    private function create_pointers()
    {
        if ($this->_tour_manager->user_has_finished_tour()) {
            return;
        }

        $this->_tour_manager->set_pointers(array(
            array(
                'content' => sprintf(
                    '<h3>%s</h3>%s',
                    __('Get Started in 60 Seconds', 'content-aware-sidebars'),
                    '<p>'.__('Welcome to Content Aware Sidebars!', 'content-aware-sidebars').'</p>'.
                    '<p>'.__('This interactive guide will show you just how easy it is to create a widget area and control where, how, and when to display it.', 'content-aware-sidebars').'</p>'
                ),
                'ref_id'   => '#titlediv',
                'position' => array(
                    'edge'  => 'top',
                    'align' => 'center'
                ),
                'pointerWidth' => 400,
                'next'         => __('Start Quick Tour', 'content-aware-sidebars'),
                'dismiss'      => __('Skip - I know what to do', 'content-aware-sidebars')
            ),
            array(
                'content' => sprintf(
                    '<h3>%s</h3>%s',
                    '1/5 '.__('Where to display', 'content-aware-sidebars'),
                    '<p>'.__('Choose from the extensive Display Conditions with built-in support for other plugins. You will never be asked to enter widget logic PHP code!', 'content-aware-sidebars').'</p>'.
                    '<p>'.__('Select anything to continue the tour. You can change it later.', 'content-aware-sidebars').'</p>'
                ),
                'ref_id'   => '.cas-group-new',
                'position' => array(
                    'edge'  => 'top',
                    'align' => 'center'
                ),
                'prev'      => false,
                'next'      => '.js-wpca-add-or, .js-wpca-add-quick',
                'nextEvent' => 'select2:select click',
                'dismiss'   => false
            ),
            array(
                'content' => sprintf(
                    '<h3>%s</h3>%s',
                    '2/5 '.__('Where to display', 'content-aware-sidebars'),
                    '<p>'.__('Click on the input field and select the content you want - just type to search. Changes are saved automatically!', 'content-aware-sidebars').'</p>'.
                    '<p>'.__('You can add multiple content types to the same group. Try e.g. "All Posts" and an Author to display on all posts written by that author.', 'content-aware-sidebars').'</p>'.
                    '<p>'.sprintf(__('<a href="%s" target="_blank">Learn more about AND vs OR conditions</a>', 'content-aware-sidebars'), 'https://dev.institute/docs/content-aware-sidebars/getting-started/display-sidebar-advanced/').'</p>'
                ),
                'ref_id'   => '#cas-groups > ul',
                'position' => array(
                    'edge'  => 'top',
                    'align' => 'center'
                ),
                'dismiss' => __('Close Tour', 'content-aware-sidebars')
            ),
            array(
                'content' => sprintf(
                    '<h3>%s</h3>%s',
                    '3/5 '.__('How to display', 'content-aware-sidebars'),
                    '<p>'.__('Replace any sidebar or widget area in your theme, or add widgets by merging with them.', 'content-aware-sidebars').'</p>'.
                    '<p>'.__('You can also use the shortcode to display widgets inside a page or post.', 'content-aware-sidebars').'</p>'
                ),
                'ref_id'   => '.nav-tab-wrapper.js-cas-tabs .nav-tab-section-action',
                'position' => array(
                    'edge'  => 'left',
                    'align' => 'left'
                ),
                'dismiss' => __('Close Tour', 'content-aware-sidebars')
            ),
            array(
                'content' => sprintf(
                    '<h3>%s</h3>%s',
                    '4/5 '.__('When to activate', 'content-aware-sidebars'),
                    '<p>'.__('Create a widget area and manage its widgets today, then publish it when you are ready.', 'content-aware-sidebars').'</p>'.
                    '<p>'.__('To schedule automatic activation or deactivation, just pick a date and time!', 'content-aware-sidebars').'</p>'.
                    '<p>'.__('By default, new widget areas will be activated when created.', 'content-aware-sidebars').'</p>'
                ),
                'ref_id'   => '.nav-tab-wrapper.js-cas-tabs .nav-tab-section-schedule',
                'position' => array(
                    'edge'  => 'left',
                    'align' => 'left'
                ),
                'dismiss' => __('Close Tour', 'content-aware-sidebars')
            ),
            array(
                'content' => sprintf(
                    '<h3>%s</h3>%s',
                    '5/5 '.__('How to look', 'content-aware-sidebars'),
                    '<p>'.__('Personalize the styling without writing any code!', 'content-aware-sidebars').'</p>'.
                    '<p>'.__('You can modify the HTML and CSS classes of the widget area itself, each widget, as well as widget titles.', 'content-aware-sidebars').'</p>'
                ),
                'ref_id'   => '.nav-tab-wrapper.js-cas-tabs .nav-tab-section-design',
                'position' => array(
                    'edge'  => 'left',
                    'align' => 'left'
                ),
                'next' => __('Finish Tour', 'content-aware-sidebars')
            ),
            array(
                'content' => sprintf(
                    '<h3>%s</h3>%s',
                    __("That's it", 'content-aware-sidebars'),
                    '<p>'.__('Hit the Create button to save your first custom widget area.', 'content-aware-sidebars').'</p>'.
                    '<p>'.__('If you need more help, check out the links below.', 'content-aware-sidebars').'</p>'
                ),
                'ref_id'   => '#submitdiv',
                'position' => array(
                    'edge'  => 'right',
                    'align' => 'top'
                ),
                'dismiss' => __('Close', 'content-aware-sidebars')
            )
        ));
        $this->_tour_manager->enqueue_scripts();
    }

    /**
     * Meta boxes for sidebar edit
     * @global object $post
     * @return void
     */
    public function create_meta_boxes($post)
    {
        $this->create_pointers();
        CAS_App::instance()->manager()->populate_metadata();
        $path = plugin_dir_path(__FILE__).'../view/';

        $cas_fs = cas_fs();

        $boxes = array();
        $boxes[] = array(
            'id'       => 'submitdiv',
            'title'    => __('Publish'),
            'view'     => 'submit',
            'context'  => 'side',
            'priority' => 'high'
        );
        $boxes[] = array(
            'id'       => 'cas-options',
            'title'    => __('How to display', 'content-aware-sidebars'),
            'view'     => 'action',
            'context'  => 'section-action',
            'priority' => 'default'
        );
        $boxes[] = array(
            'id'       => 'cas-status',
            'title'    => __('Status', 'content-aware-sidebars'),
            'view'     => 'status',
            'context'  => 'section-schedule',
            'priority' => 'default'
        );
        $boxes[] = array(
            'id'       => 'cas-widget-html',
            'title'    => __('Styles', 'content-aware-sidebars'),
            'view'     => 'html',
            'context'  => 'section-design',
            'priority' => 'default'
        );
        $boxes[] = array(
            'id'       => 'cas-advanced',
            'title'    => __('Options', 'content-aware-sidebars'),
            'view'     => 'advanced',
            'context'  => 'section-advanced',
            'priority' => 'default'
        );
        $boxes[] = array(
            'id'       => 'cas-plugin-links',
            'title'    => __('Recommendations', 'content-aware-sidebars'),
            'view'     => 'support',
            'context'  => 'side',
            'priority' => 'default'
        );

        if (!$cas_fs->can_use_premium_code()) {
            $boxes[] = array(
                'id'       => 'cas-schedule',
                'title'    => __('Time Schedule', 'content-aware-sidebars').' <span class="cas-pro-label">'.__('Pro', 'content-aware-sidebars').'</span>',
                'view'     => 'schedule',
                'context'  => 'section-schedule',
                'priority' => 'default'
            );
            $boxes[] = array(
                'id'       => 'cas-design',
                'title'    => __('Design', 'content-aware-sidebars').' <span class="cas-pro-label">'.__('Pro', 'content-aware-sidebars').'</span>',
                'view'     => 'design',
                'context'  => 'section-design',
                'priority' => 'default'
            );
        }

        //Add meta boxes
        foreach ($boxes as $box) {
            $view = WPCAView::make($path.'meta_box_'.$box['view'].'.php', array(
                'post' => $post
            ));

            add_meta_box(
                $box['id'],
                $box['title'],
                array($view,'render'),
                CAS_App::BASE_SCREEN.'-edit',
                $box['context'],
                $box['priority']
            );
        }

        //todo: refactor add of meta box
        //with new bootstrapper, legacy core might be loaded
        if (method_exists('WPCACore', 'render_group_meta_box')) {
            WPCACore::render_group_meta_box($post, CAS_App::BASE_SCREEN.'-edit', 'section-conditions', 'default');
        }
    }

    /**
     * Admin notice for Plugin Review
     *
     * @since  3.1
     * @return void
     */
    public function admin_notice_review()
    {
        $has_reviewed = get_user_option(CAS_App::META_PREFIX.'cas_review');
        $tour_taken = (int) $this->_tour_manager->get_user_option();
        if ($has_reviewed === false && $tour_taken && (time() - $tour_taken) >= WEEK_IN_SECONDS) {
            $path = plugin_dir_path(__FILE__).'../view/';
            $view = WPCAView::make($path.'notice_review.php', array(
                'current_user' => wp_get_current_user()
            ))->render();
        }
    }

    /**
     * Set review flag for user
     *
     * @since  3.1
     * @return void
     */
    public function ajax_review_clicked()
    {
        $dismiss = isset($_POST['dismiss']) ? (int)$_POST['dismiss'] : 0;
        if (!$dismiss) {
            $dismiss = time();
        }

        echo json_encode(update_user_option(get_current_user_id(), CAS_App::META_PREFIX.'cas_review', $dismiss));
        die();
    }

    /**
     * Create form field for metadata
     * @global object $post
     * @param  array $setting
     * @return void
     */
    public static function form_field($id, $class = '', $icon = '')
    {
        $setting = CAS_App::instance()->manager()->metadata()->get($id);
        $current = $setting->get_data(get_the_ID(), true, $setting->get_input_type() != 'multi');
        $icon = $icon ? '<span class="'.$icon.'"></span> ' : '';

        echo '<div class="'.$class.'">'.$icon.'<strong>' . $setting->get_title() . '</strong>';
        echo '<p>';
        switch ($setting->get_input_type()) {
            case 'select':
                echo '<select style="width:250px;" name="' . $id . '" class="js-cas-'.$id.'">' . "\n";
                foreach ($setting->get_input_list() as $key => $value) {
                    $disabled = '';
                    if (is_string($key) && strpos($key, '__') === 0) {
                        $disabled = ' disabled="disabled"';
                    }
                    echo '<option value="' . $key . '"' . selected($current, $key, false) . $disabled . '>' . $value . '</option>' . "\n";
                }
                echo '</select>' . "\n";
                break;
            case 'checkbox':
                echo '<ul>' . "\n";
                foreach ($setting->get_input_list() as $key => $value) {
                    echo '<li><label><input type="checkbox" name="' . $id . '[]" class="js-cas-'.$id.'" value="' . $key . '"' . (in_array($key, $current) ? ' checked="checked"' : '') . ' /> ' . $value . '</label></li>' . "\n";
                }
                echo '</ul>' . "\n";
                break;
            case 'multi':
                echo '<div><select style="width:100%;" class="js-cas-'.$id.'" multiple="multiple"  name="' . $id . '[]" data-value="'.implode(',', $current).'"></select></div>';
                break;
            case 'text':
            default:
                echo '<input style="width:200px;" type="text" name="' . $id . '" value="' . $current . '" />' . "\n";
                break;
        }
        echo '</p></div>';
    }

    /**
     * Save meta values for post
     * @param  int $post_id
     * @return void
     */
    public function save_post($post_id, $post)
    {

        //Verify nonce, check_admin_referer dies on false
        //TODO: check other nonce instead
        if (!(isset($_POST[WPCACore::NONCE])
            && wp_verify_nonce($_POST[WPCACore::NONCE], WPCACore::PREFIX.$post_id))) {
            return;
        }

        // Check permissions
        if (!current_user_can(CAS_App::CAPABILITY, $post_id)) {
            return;
        }

        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Save metadata
        // todo: wrap this in metadata manager?
        foreach (CAS_App::instance()->manager()->metadata() as $field) {
            $field->save($post_id);
        }
    }

    /**
     * Add suffix when creating sidebar with existing name
     * Does not stop duplicate titles on update
     *
     * @since  3.4.3
     * @param  array  $insert_data
     * @param  array  $data
     * @return array
     */
    public function add_duplicate_title_suffix($insert_data, $data)
    {
        if ($data['post_type'] == CAS_App::TYPE_SIDEBAR && !$data['ID']) {
            $sidebars = CAS_App::instance()->manager()->sidebars;
            $sidebar_titles = array();
            foreach ($sidebars as $sidebar) {
                $sidebar_titles[$sidebar->post_title] = 1;
            }
            //if title exists, add a suffix
            $i = 0;
            $title = wp_unslash($insert_data['post_title']);
            $new_title = $title;
            while (isset($sidebar_titles[$new_title])) {
                $new_title = $title.' ('.++$i.')';
            }
            if ($i) {
                $insert_data['post_title'] = wp_slash($new_title);
            }
        }
        return $insert_data;
    }

    /**
     * Remove widget when its sidebar is removed
     * @param  int $post_id
     * @return void
     */
    public function remove_sidebar_widgets($post_id)
    {

        // Authenticate and only continue on sidebar post type
        if (!current_user_can(CAS_App::CAPABILITY) || get_post_type($post_id) != CAS_App::TYPE_SIDEBAR) {
            return;
        }

        $id = CAS_App::SIDEBAR_PREFIX . $post_id;

        //Get widgets
        $sidebars_widgets = wp_get_sidebars_widgets();

        // Check if sidebar exists in database
        if (!isset($sidebars_widgets[$id])) {
            return;
        }

        // Remove widgets settings from sidebar
        foreach ($sidebars_widgets[$id] as $widget_id) {
            $widget_type = preg_replace('/-[0-9]+$/', '', $widget_id);
            $widget_settings = get_option('widget_' . $widget_type);
            $widget_id = substr($widget_id, strpos($widget_id, '-') + 1);
            if ($widget_settings && isset($widget_settings[$widget_id])) {
                unset($widget_settings[$widget_id]);
                update_option('widget_' . $widget_type, $widget_settings);
            }
        }

        // Remove sidebar
        unset($sidebars_widgets[$id]);
        wp_set_sidebars_widgets($sidebars_widgets);
    }

    /**
     * Get sidebar edit link
     * TODO: Consider changing post type _edit_link instead
     *
     * @since  3.4
     * @param  string  $link
     * @param  int     $post_id
     * @param  string  $context
     * @return string
     */
    public function get_edit_post_link($link, $post_id, $context)
    {
        $post = get_post($post_id);
        if ($post->post_type == CAS_App::TYPE_SIDEBAR) {
            $sep = '&';
            if ($context == 'display') {
                $sep = '&amp;';
            }
            $link = admin_url('admin.php?page=wpcas-edit'.$sep.'sidebar_id='.$post_id);

            //load page in all languages for wpml
            if (defined('ICL_SITEPRESS_VERSION') || defined('POLYLANG_VERSION')) {
                $link .= $sep.'lang=all';
            }
        }
        return $link;
    }

    /**
     * Get sidebar delete link
     * TODO: Consider changing post type _edit_link instead
     *
     * @since  3.4
     * @param  string   $link
     * @param  int      $post_id
     * @param  boolean  $force_delete
     * @return string
     */
    public function get_delete_post_link($link, $post_id, $force_delete)
    {
        $post = get_post($post_id);
        if ($post->post_type == CAS_App::TYPE_SIDEBAR) {
            $action = ($force_delete || !EMPTY_TRASH_DAYS) ? 'delete' : 'trash';

            $link = add_query_arg(
                'action',
                $action,
                admin_url('admin.php?page=wpcas-edit&sidebar_id='.$post_id)
            );
            $link = wp_nonce_url($link, "$action-post_{$post_id}");
        }
        return $link;
    }

    /**
     * Add general scripts to admin screens
     *
     * @since 3.4.1
     */
    public function add_general_scripts_styles()
    {
        wp_register_script('cas/admin/general', plugins_url('../js/general.min.js', __FILE__), array('jquery'), CAS_App::PLUGIN_VERSION, true);
        wp_enqueue_script('cas/admin/general');
        wp_localize_script('cas/admin/general', 'CAS', array(
            'showPopups'    => !cas_fs()->can_use_premium_code(),
            'enableConfirm' => __('This sidebar is already scheduled to be activated. Do you want to activate it now?', 'content-aware-sidebars')
        ));
    }

    /**
     * Register and enqueue scripts styles
     * for screen
     *
     * @since 3.4
     */
    public function add_scripts_styles()
    {
        if (is_multisite()) {
            add_action('admin_footer', '_admin_notice_post_locked');
        } else {
            $check_users = get_users(array( 'fields' => 'ID', 'number' => 2 ));
            if (count($check_users) > 1) {
                add_action('admin_footer', '_admin_notice_post_locked');
            }
        }

        wp_enqueue_script('wp-a11y');

        if (wp_is_mobile()) {
            wp_enqueue_script('jquery-touch-punch');
        }


        // Add the local autosave notice HTML
        //add_action( 'admin_footer', '_local_storage_notice' );

        WPCACore::enqueue_scripts_styles('');

        wp_register_script('flatpickr', plugins_url('../js/flatpickr.min.js', __FILE__), array(), '3.0.6', false);

        wp_register_script('cas/admin/edit', plugins_url('../js/cas_admin.min.js', __FILE__), array('jquery','flatpickr','wp-color-picker'), CAS_App::PLUGIN_VERSION, false);

        wp_register_style('flatpickr', plugins_url('../css/flatpickr.dark.min.css', __FILE__), array(), '3.0.6');
        wp_register_style('cas/admin/style', plugins_url('../css/style.css', __FILE__), array('flatpickr','wp-color-picker'), CAS_App::PLUGIN_VERSION);

        $visibility = array();
        foreach (CAS_App::instance()->_manager->metadata()->get('visibility')->get_input_list() as $category_key => $category) {

            //legacy format
            if (!is_array($category)) {
                $visibility[] = array(
                    'id'   => $category_key,
                    'text' => $category
                );
                continue;
            }

            $data = array(
                'text'     => $category['label'],
                'children' => array()
            );
            foreach ($category['options'] as $key => $value) {
                $data['children'][] = array(
                    'id'   => $key,
                    'text' => $value
                );
            }
            $visibility[] = $data;
        }

        if (!cas_fs()->can_use_premium_code()) {
            $visibility[] = array(
                'text'     => __('Upgrade to Pro for more options', 'content-aware-sidebars'),
                'children' => array()
            );
        }

        global $wp_locale;

        wp_enqueue_script('cas/admin/edit');
        wp_localize_script('cas/admin/edit', 'CASAdmin', array(
            'allVisibility' => __('All Users', 'content-aware-sidebars'),
            'visibility'    => $visibility,
            'weekdays'      => array(
                'shorthand' => array_values($wp_locale->weekday_abbrev),
                'longhand'  => array_values($wp_locale->weekday)
            ),
            'months' => array(
                'shorthand' => array_values($wp_locale->month_abbrev),
                'longhand'  => array_values($wp_locale->month)
            ),
            'weekStart'  => get_option('start_of_week', 0),
            'timeFormat' => get_option('time_format'),
            'dateFormat' => __('F j, Y') //default long date
        ));

        wp_enqueue_style('cas/admin/style');

        //badgeos compat
        //todo: check that developers respond with a fix soon
        wp_register_script('badgeos-select2', '');
        wp_register_style('badgeos-select2-css', '');
    }
}
