<?php

namespace coc\core;

use coc\ClockOfChange;
use coc\shortcodes\ShUserwall;
use DateTime;

class ListTable extends \WP_List_Table
{
    public $items;

    public function __construct()
    {
        add_filter('removable_query_args', [$this, 'rmqa'], 10, 1);
        parent::__construct(
            [
                'singular' => __('Entry', 'coc'), //singular name of the listed records
                'plural'   => __('Entries', 'coc'), //plural name of the listed records
                'ajax'     => false //should this table support ajax?
            ]
        );
    }

    public function rmqa($removable_query_args)
    {
        $removable_query_args[] = 'action';
        $removable_query_args[] = 'entry';

        return $removable_query_args;
    }

    /**
     * Render the bulk edit checkbox
     *
     * @param array $item
     * @return string
     */
    function column_cb($item)
    {
        // use this line to get the multi action checkbox
        return sprintf('<input type="checkbox" name="bulk-entries[]" value="%s" />', $item['ID']);
    }

    /**
     * Retrieve customer’s data from the database
     *
     * @param int $per_page
     * @param int $page_number
     * @return mixed
     */
    public static function getEntries($per_page = 5, $page_number = 1)
    {
        $result    = null;
        $offset    = $page_number === 1 ? 0 : $page_number * $per_page;
        $orderBy   = isset($_GET['orderby']) ? $_GET['orderby'] : null;
        $order     = isset($_GET['order']) ? $_GET['order'] : null;
        $confirmed = isset($_REQUEST['confirmed']) ? $_REQUEST['confirmed'] : null;
        $status    = isset($_REQUEST['status']) ? $_REQUEST['status'] : null;

        $filter = [
            'active'    => false,
            'confirmed' => $confirmed,
            'status'    => $status,
        ];

        $users = ClockOfChange::app()->cocAPI()->getUsers($offset, $filter, $orderBy, $order);
        if (!empty($users) && isset($users->results)) {
            foreach ($users->results as $user) {
                $userArr['ID']              = $user->id;
                $userArr['email']           = $user->email;
                $userArr['firstname']       = $user->firstname;
                $userArr['lastname']        = $user->lastname;
                $userArr['message']         = $user->message;
                $userArr['country']         = $user->country;
                $userArr['email_confirmed'] = $user->email_confirmed === 1 ? 'Yes' : 'No';
                $userArr['status']          = $user->status === 1 ? 'Active' : 'Inactive';
                $userArr['anon']            = $user->anon === 1 ? 'Yes' : 'No';
                $userArr['created_at']      = date('d-m-Y', $user->created_at / 1000);
                $userArr['updated_at']      = date('d-m-Y', $user->updated_at / 1000);
                $userArr['confirmed_at']    = date('d-m-Y', $user->confirmed_at / 1000);
                $userArr['image']           = $user->image !== '' ? '<img style="width:75px;height:75px;" src="' . $user->image . '"/>' : '<img style="width:75px;height:75px;" src="' . ClockOfChange::$pluginAssetsUri . '/images/coc-placeholder.jpg"/>';
                $result[]                   = $userArr;
            }
        }

        return $result;
    }

    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public static function record_count()
    {
        $confirmed = isset($_REQUEST['confirmed']) ? $_REQUEST['confirmed'] : null;
        $status    = isset($_REQUEST['status']) ? $_REQUEST['status'] : null;

        $filter = [
            'active'    => false,
            'confirmed' => $confirmed,
            'status'    => $status,
        ];

        return ClockOfChange::app()->cocAPI()->getCount($filter);
    }

    /** Text displayed when no customer data is available */
    public function no_items()
    {
        _e('No entries avaliable.', 'coc');
    }

    /**
     * Returns an associative array containing the bulk action
     *
     * @return array
     */
    public function get_bulk_actions()
    {
        $actions = [
            'bulk-activate' => 'Activate',
            'bulk-disable'  => 'Disable',
            'bulk-delete'   => 'Delete',
        ];

        return $actions;
    }

    public function processBulkAction()
    {
        if (current_user_can('manage_options') && $this->current_action() !== false && in_array(
                $this->current_action(), array_keys($this->get_bulk_actions())
            )) {
            $action = 'bulk-' . $this->_args['plural'];

            if (!wp_verify_nonce($_GET['_wpnonce'], $action)) {
                die('Go get a life script kiddies');
            }

            // check for toggle action && correct page
            if ($_GET['page'] === 'coc_entries') {
                $ids = $_GET['bulk-entries'];
                if ($this->current_action() === 'bulk-activate') {
                    foreach ($ids as $id) {
                        if ((int) $id > 0) {
                            $result = ClockOfChange::app()->cocAPI()->toggleStatus($id, 'cocactivate');
                        }
                    }
                } else if ($this->current_action() === 'bulk-disable') {
                    foreach ($ids as $id) {
                        if ((int) $id > 0) {
                            $result = ClockOfChange::app()->cocAPI()->toggleStatus($id, 'cocdisable');
                        }
                    }
                } else if ($this->current_action() === 'bulk-delete') {
                    foreach ($ids as $id) {
                        if ((int) $id > 0) {
                            $result = ClockOfChange::app()->cocAPI()->deleteEntry($id);
                        }
                    }
                }
            }
        }
    }

    public function processSingleAction()
    {
        $singleActions = [
            'cocactivate',
            'cocdisable',
            'cocdelete',
        ];

        if (current_user_can('manage_options') && $this->current_action() !== false && in_array(
                $this->current_action(), $singleActions
            )) {
            if (!wp_verify_nonce($_GET['_wpnonce'], 'hc_toggle_coc_user')) {
                die('Go get a life script kiddies');
            }

            // check for toggle action && correct page
            if (($this->current_action() === 'cocactivate' || $this->current_action(
                    ) === 'cocdisable') && $_GET['page'] === 'coc_entries') {
                // toggle entry status
                // object(stdClass)#7800 (2) { ["success"]=> bool(true) ["message"]=> string(14) "toggled status" }
                $result = ClockOfChange::app()->cocAPI()->toggleStatus($_GET['entry'], $this->current_action());
                if (isset($result->success) && $result->success === true) {
                    return true;
                }
            }

            // check for toggle action && correct page
            if ($this->current_action() === 'cocdelete' && $_GET['page'] === 'coc_entries') {
                if ($_GET['entry'] && (int) $_GET['entry'] > 0) {
                    $result = ClockOfChange::app()->cocAPI()->deleteEntry($_GET['entry']);
                    if (isset($result->success) && $result->success === true) {
                        return true;
                    }
                }

                return true;
            }
        }
    }

    /**
     * Render a column when no column specific method exist.
     *
     * @param array  $item
     * @param string $column_name
     * @return mixed
     */
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'created_at':
            case 'updated_at':
            case 'confirmed_at':
                if ($item[$column_name]) {
                    $dateTime = DateTime::createFromFormat('d-m-Y', $item[$column_name]);

                    return $dateTime->format('d.m.Y');
                }
                break;
            case 'ID':
            case 'email':
            case 'firstname':
            case 'lastname':
            case 'message':
            case 'country':
            case 'email_confirmed':
            case 'status':
            case 'anon':
            case 'image':
                return $item[$column_name];
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    /**
     * Method for status column
     *
     * @param array $item an array of DB data
     * @return string
     */
    function column_status($item)
    {
        // create a nonce
        $nonce = wp_create_nonce('hc_toggle_coc_user');

        $title = '<strong>' . $item['status'] . '</strong>';

        $actions = [
            'cocactivate' => sprintf(
                '<a href="?page=%s&action=%s&entry=%s&_wpnonce=%s&paged=%s#entry-%s">Activate</a>',
                esc_attr($_REQUEST['page']), 'cocactivate', absint($item['ID']), $nonce, $this->get_pagenum(),
                absint($item['ID'])
            ),
            'cocdisable'  => sprintf(
                '<a href="?page=%s&action=%s&entry=%s&_wpnonce=%s&paged=%s">Disable</a>',
                esc_attr($_REQUEST['page']), 'cocdisable', absint($item['ID']), $nonce, $this->get_pagenum()
            ),
            'cocdelete'   => sprintf(
                '<a href="?page=%s&action=%s&entry=%s&_wpnonce=%s&paged=%s" onclick="return confirm(\'Really delete entry? This cannot be undone.\');">Delete</a>',
                esc_attr($_REQUEST['page']), 'cocdelete', absint($item['ID']), $nonce, $this->get_pagenum()
            ),
        ];

        return $title . $this->row_actions($actions);
    }

    /**
     * Columns to make sortable.
     *
     * @return array
     */
    public function get_sortable_columns()
    {
        return [
            'ID'              => ['id', false],
            'email'           => ['email', false],
            'firstname'       => ['firstname', false],
            'lastname'        => ['lastname', false],
            'country'         => ['country', false],
            'email_confirmed' => ['email_confirmed', false],
            'status'          => ['status', false],
            'anon'            => ['anon', false],
            'created_at'      => ['created_at', true],
            'confirmed_at'    => ['confirmed_at', true],
        ];
    }

    /**
     *  Associative array of columns
     *
     * @return array
     */
    function get_columns()
    {
        $columns = [
            'cb'              => '<input type="checkbox" />',
            'ID'              => __('ID', 'coc'),
            'email'           => __('Email', 'coc'),
            'firstname'       => __('Firstname', 'coc'),
            'lastname'        => __('Lastname', 'coc'),
            'message'         => __('Message', 'coc'),
            'country'         => __('Country', 'coc'),
            'email_confirmed' => __('EMail Confirmed', 'coc'),
            'status'          => __('Status', 'coc'),
            'anon'            => __('Anonymous', 'coc'),
            'created_at'      => __('Created', 'coc'),
            //			'updated_at'    => __( 'Updated', 'coc' ),
            'confirmed_at'    => __('Confirmed', 'coc'),
            'image'           => __('Image', 'coc'),
        ];

        return $columns;
    }

    /**
     * Handles data query and filter, sorting, and pagination.
     */
    public function prepare_items()
    {
        $this->_column_headers = $this->get_column_info();
        /* Process bulk action */
        $this->processSingleAction();
        $this->processBulkAction();
        $per_page     = $this->get_items_per_page('entries_per_page', ShUserwall::PAGE_SIZE);
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();
        $this->set_pagination_args(
            [
                'total_items' => $total_items, //WE have to calculate the total number of items
                'per_page'    => $per_page //WE have to determine how many items to show on a page
            ]
        );
        $this->items = self::getEntries($per_page, $current_page);
    }

    /**
     * Generates content for a single row of the table
     *
     * @param object $item The current item
     * @since 3.1.0
     */
    public function single_row($item)
    {
        echo '<tr id="entry-' . $item['ID'] . '">';
        $this->single_row_columns($item);
        echo '</tr>';
    }

    public function extra_tablenav($which)
    {
        if ($which === 'top') {
            $confirmed = isset($_REQUEST['confirmed']) ? $_REQUEST['confirmed'] : null;
            $status    = isset($_REQUEST['status']) ? $_REQUEST['status'] : null;
            ?>
            <div class="alignleft actions">
                <label for="filter-by-confirmed" class="screen-reader-text">Filter by email confirmed</label>
                <select name="confirmed" id="filter-by-confirmed" class="postform">
                    <option value="all">All email confirmed</option>
                    <option value="yes" <?= $confirmed === 'yes' ? 'selected="selected"' : ''; ?>>Email confirmed
                    </option>
                    <option value="no" <?= $confirmed === 'no' ? 'selected="selected"' : ''; ?>>Email not confirmed
                    </option>
                </select>
                <select name="status" id="filter-by-status" class="postform">
                    <option value="all">All status</option>
                    <option value="active" <?= $status === 'active' ? 'selected="selected"' : ''; ?>>Active</option>
                    <option value="inactive" <?= $status === 'inactive' ? 'selected="selected"' : ''; ?>>Inactive
                    </option>
                </select>
                <?php submit_button('Filter', 'action', 'filter-action', false); ?>
            </div>
            <?php
        }
    }

    /**
     * Display the bulk actions dropdown.
     *
     * @param string $which The location of the bulk actions: 'top' or 'bottom'.
     *                      This is designated as optional for backward compatibility.
     * @since 3.1.0
     */
    protected function bulk_actions($which = '')
    {
        if (is_null($this->_actions)) {
            $this->_actions = $this->get_bulk_actions();
            /**
             * Filters the list table Bulk Actions drop-down.
             * The dynamic portion of the hook name, `$this->screen->id`, refers
             * to the ID of the current screen, usually a string.
             * This filter can currently only be used to remove bulk actions.
             *
             * @param string[] $actions An array of the available bulk actions.
             * @since 3.5.0
             */
            $this->_actions = apply_filters(
                "bulk_actions-{$this->screen->id}", $this->_actions
            );  // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
            $two            = '';
        } else {
            $two = '2';
        }

        if (empty($this->_actions)) {
            return;
        }

        echo '<label for="bulk-action-selector-' . esc_attr($which) . '" class="screen-reader-text">' . __(
                'Select bulk action'
            ) . '</label>';
        echo '<select name="action' . $two . '" id="bulk-action-selector-' . esc_attr($which) . "\">\n";
        echo '<option value="-1">' . __('Bulk Actions') . "</option>\n";

        foreach ($this->_actions as $name => $title) {
            $class = 'edit' === $name ? ' class="hide-if-no-js"' : '';

            echo "\t" . '<option value="' . $name . '"' . $class . ' ' . ($name === 'bulk-delete' ? 'onclick="return confirm(\'Info: Deleting entries is permanent and cannot be undone! \');"' : '') . '>' . $title . "</option>\n";
        }

        echo "</select>\n";

        submit_button(__('Apply'), 'action', '', false, ['id' => "doaction$two"]);
        echo "\n";
    }
}
