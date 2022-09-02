<?php
class WP_Randomize_Table extends WP_List_Table
{
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items() {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $action = $this->current_action();

        $data = $this->table_data();
        usort($data, array(&$this, 'sort_data'));

        $perPage = 20;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args(array(
            'total_items' => $totalItems,
            'per_page' => $perPage,
        ));

        $data = array_slice($data, (($currentPage - 1) * $perPage), $perPage);
        $this->_column_headers = array($columns, $hidden, $sortable);
       
        $this->items = $data;
    }
    
    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns() {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => 'Title',
            'category' => 'Category',
            'entries' => 'Entries',
            'date' => 'Date'
        );

        return $columns;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns() {
        return array();
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns() {
        return array(
            'title' => array('title', true),
            'entries' => array('entries', true),
            'category' => array('category', true),
            'date' => array('date', true)
        );
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data() {
        global $wpdb;
        $data = array();
        
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wp_randomize");
        if($results){
            foreach($results as $result){
                $compressdata = $result->fields;
                $compressdata = ((!empty($compressdata)) ? unserialize($compressdata): []);
                
                $counts = 0;
                if(is_array($compressdata) && sizeof($compressdata) > 0){
                    $counts = sizeof($compressdata);
                }
                
                $arr = [
                    'ID' => $result->ID,
                    'title' => $result->title,
                    'category' => get_cat_name( $result->category ),
                    'entries' => $counts,
                    'date' => date("Y-m-d h:i:s a", strtotime($result->date))
                ];
                
                $data[] = $arr;
            }
        }

        return $data;
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default($item, $column_name) {
        switch ($column_name) {
            case $column_name:
                return $item[$column_name];
            default:
                return print_r($item, true);
        }
    }

    public function column_title($item) {
        $actions = array(
            'view' => '<a href="?page=wp-randomize&action=edit&id='.$item['ID'].'">Edit</a>',
            'delete' => '<a href="?page=wp-randomize&action=delete&id='.$item['ID'].'">Delete</a>'
        );

        return sprintf('%1$s %2$s', $item['title'], $this->row_actions($actions));
    }

    public function get_bulk_actions() {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    public function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />', $item['ID']
        );
    }

    // All form actions
    public function current_action() {
        global $wpdb;
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete' && isset($_REQUEST['id'])) {
            if(is_array($_REQUEST['id'])){
                $ids = $_REQUEST['id'];
                foreach($ids as $ID){
                    $wpdb->query("DELETE FROM {$wpdb->prefix}wp_randomize WHERE ID = $ID");
                }
            }else{
                $ID = intval($_REQUEST['id']);
                $wpdb->query("DELETE FROM {$wpdb->prefix}wp_randomize WHERE ID = $ID");
            }

            wp_safe_redirect( admin_url( 'admin.php?page=wp-randomize' ) );
            exit;
        }
    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data($a, $b) {
        // If no sort, default to user_login
        $orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'title';
        // If no order, default to asc
        $order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc';
        // Determine sort order
        $result = strnatcmp($a[$orderby], $b[$orderby]);
        // Send final sort direction to usort
        return ($order === 'asc') ? $result : -$result;
    }

} //class
