<?php
class HKAILinks {
  private $tbl_links = NULL;
  private $tbl_user_link_clicks = NULL;
  private $link_types = NULL;
  function __construct() {
    global $table_prefix;
    $this->tbl_links = $table_prefix . 'hkail_links';
    $this->tbl_user_link_clicks = $table_prefix . 'hkail_user_link_clicks';
    $this->link_types = array(1 => __('System', 'hkail'),
                              2 => __('Bra att ha', 'hkail'),
                              3 => __('Jag vill', 'hkail'),
                              4 => __('Till dig som är', 'hkail'));
  }

  function getLinkTypeName($index) {
    if (!empty($this->link_types[$index])) {
      return $this->link_types[$index];
    }
    return __("No link type found.", 'hkail');
  }

  function getLinkTypeFromName($name) {
    // return input if is int
    if (is_int($name)) {
      return $name;
    }
    // else find key
    $key = array_search($name, $this->link_types);
    if (!empty($key)) {
      return $key;
    }
    return __("No link type found.", 'hkail');
  }

  /* Echo link list in option page */
  function echoLinkList() {
    $ret = "<div class='hkail_link_list'></div>";
    echo $ret;
  }

  /* Echo add new link in option page */
  function echoLinkOption() {
    $ret = "<div class='hkail_add_new'>";
    $ret .= "<h2>" . __("Link handling", 'hkail') . "</h2>";
    $ret .= "<span class='hkail_hidden'><label>" . __("ID", 'hkail') . "</label><input id='hkail_add_id' name='hkail_add_id' type='text' /></span>";
    $ret .= "<span><label>" . __("Name", 'hkail') . "</label><input id='hkail_add_name' name='hkail_add_name' type='text' /></span>";
    $ret .= "<span><label>" . __("Url", 'hkail') . "</label><input id='hkail_add_href' name='hkail_add_href' type='text' /></span>";
    $ret .= "<span><label>" . __("Group", 'hkail') . "</label><select id='hkail_add_groups' name='hkail_add_groups' >";
    $ret .= "<option value=''>" . __("Choose group", 'hkail') . "</option>";
    foreach( $this->link_types as $key => $value ) {
      $ret .= "<option value='$key'>$value</option>";
    }
    $ret .= "</select></span>";
    $ret .= "<span><label>" . __("Classes", 'hkail') . "</label><input id='hkail_add_classes' name='hkail_add_classes' type='text' /></span>";
    $ret .= "<span><label>&nbsp;</label><input id='hkail_add' name='hkail_add' type='submit' value='" . __('Add', 'hkail') . "' />";
    $ret .= "<input id='hkail_delete' name='hkail_delete' type='submit' value='" . __('Delete', 'hkail') . "' />";
    $ret .= "<input id='hkail_update' name='hkail_update' type='submit' value='" . __('Update', 'hkail') . "' />";
    $ret .= "<input id='hkail_cancel' name='hkail_cancel' type='submit' value='" . __('Cancel', 'hkail') . "' /></span>";
    $ret .= "<span id='hkail_add_message'></span>";
    $ret .= "</div>";
    echo $ret;
  }


  /* admin list links */
  function getAdminLinkList() {
    global $wpdb;

    $sql = "SELECT * FROM " . $this->tbl_links . " ORDER BY name ASC";
    $rows = $wpdb->get_results( $wpdb->prepare($sql) );
    $table = "";
    foreach ($rows as $key => $value) {
      $table .= "<span class='hkail_link_wrapper'>";
      $table .= "<a class='hkail_link' data-id='" . $value->id . "' data-groups='" . $value->groups . "' data-classes='" . $value->classes . "' href='" . $value->href . "'>" . $value->name . "</a>";
      $table .= (empty($value->groups))?" (" . __("no groups", 'hkail') . ")":sprintf( " (%s)", $this->getLinkTypeName($value->groups));
      $table .= (empty($value->classes))?"":sprintf( " [%s]", $value->classes);
      $table .= "<a href='#' class='hkail_delete hkail_action'>" . __("Delete", 'hkail') . "</a>";
      $table .= "<a href='#' class='hkail_edit hkail_action'>" . __("Edit", 'hkail') . "</a>";
      $table .= "</span>";
    }
    if (!empty($table)) {
      echo $table;
    }
    else {
      echo __("No links added yet.", 'hkail');
    }
  }

  /* most clicked links */
  function getMostClicked($user, $numLinks = '5', $columns = '1') {
    global $wpdb;
    $table = "";

    $sql = "SELECT * FROM ( SELECT count(*) as count, link_id FROM " . $this->tbl_user_link_clicks . " WHERE name_id = '$user' GROUP BY link_id ORDER BY count DESC LIMIT 0, $numLinks ) AS clicks LEFT JOIN " . $this->tbl_links . " AS links ON links.id = clicks.link_id";

    $rows = $wpdb->get_results( $wpdb->prepare($sql) );
    if (!empty($rows)) {
      $num_rows = count($rows);
      $num_rows_per_col = ceil($num_rows / $columns);
      if ($num_rows > 0) {
        $table .= "<div class='rek-link-listener cols cols-" . $columns . "' data-rek-pagetype='Mest klickade'>";
        $i = 0;
        foreach ($rows as $key => $value) {
          $table .= "<a data-count='" . $value->count . "' data-rek-description='" . $value->id . "' data-id='" . $value->id . "' class='hkail_link rek-link-listener' class='" . $value->classes . "' href='" . $value->href . "'>" . $value->name . "</a>"; /* target='_blank'*/
          if (((++$i) % $num_rows_per_col) == 0) {
            $table .= "</div><div class='cols cols-" . $columns . "'>";
          }
        }
        $table .= "</div>";
      }
      if (!empty($table)) {
        return "<div class='hkail_links_wrapper'>" . $table . "</div>";
      }
    }
    return '';
  }


  /* list links */
  function getLinkList($columns = 1, $groups = '') {
    global $wpdb;
    $sql = "SELECT * FROM " . $this->tbl_links;
    if (!empty($groups)) {
      $group_id = $this->getLinkTypeFromName($groups);
      if (!empty($group_id)) {
        $sql .= " WHERE `groups` = '" . $group_id . "'";
      }
    }
    $sql .= " ORDER BY name ASC";

    $rows = $wpdb->get_results( $wpdb->prepare($sql) );
    $num_rows = count($rows);
    $num_rows_per_col = ceil($num_rows / $columns);
    $table = "";
    if ($num_rows > 0) {
      $table .= "<div class='rek-link-listener cols cols-" . $columns . "' data-rek-pagetype='" . $groups . "'>";
      $i = 0;
      foreach ($rows as $key => $value) {
        $table .= "<a data-id='" . $value->id . "' data-rek-description='" . $value->id . "' class='hkail_link " . $value->classes . " rek-link-listener' href='" . $value->href . "'>" . $value->name . "</a>"; /*target='_blank' */
        if (((++$i) % $num_rows_per_col) == 0) {
          $table .= "</div><div class='rek-link-listener cols cols-" . $columns . "' data-rek-pagetype='" . $groups . "'>";
        }
      }
      $table .= "</div>";
    }
    if (!empty($table)) {
      return "<div class='hkail_links_wrapper'>$table</div>";
    }
    else {
      return __("No links to show.", 'hkail');
    }
  }

  /* add click to database */
  function click($name_id, $link_id) {
    global $wpdb;

    if (!empty($name_id) && !empty($link_id)) {
      if (false !== $wpdb->insert( $this->tbl_user_link_clicks,
                  array( 'name_id' => $name_id, 'link_id' => $link_id, 'clickdate' => wp_date('Y-m-d'), 'clicktime' => wp_date('H:i:s') ) ) ) {
        return $this->jsonReturn( true, __("Click added.", 'hkail') );
      }
      else {
        return $this->jsonReturn( false, sprintf( __("Error: Insert click failed. last_error: '%s' num_rows: '%s'.", 'hkail'), $wpdb->last_error, $wpdb->num_rows ) );
      }
    }
    else {
      return $this->jsonReturn( false, __("Error: empty click variables.", 'hkail') );
    }
  }

  /* add new link to database */
  function add($name, $href, $groups, $classes) {
    global $wpdb;

    if (!empty($name) && !empty($href)) {
      $sql = "SELECT count(*) FROM " . $this->tbl_links . " WHERE `name` = '" . $name . "'";
      $exist = $wpdb->get_var( $wpdb->prepare($sql) );
      if ( $exist == 0 ) {
        if (false !== $wpdb->insert( $this->tbl_links,
                    array( 'name' => $name, 'href' => $href, 'groups' => $groups, 'classes' => $classes ) ) ) {
          return $this->jsonReturn( true, __("Link added.", 'hkail') );
        }
        else {
          return $this->jsonReturn( false, sprintf( __("Error: Insert link failed. last_error: '%s' num_rows: '%s'.", 'hkail'), $wpdb->last_error, $wpdb->num_rows ) );
        }

      }
      else {
        return $this->jsonReturn( false, sprintf( __("Error: Link '%s' already exist.", 'hkail'), $name) );
      }
    }
    else {
      return $this->jsonReturn( false, __("Error: empty variables.", 'hkail') );
    }
  }

  /* update link in database */
  function update($id, $name, $href, $groups, $classes) {
    global $wpdb;

    if (!empty($id) && !empty($name) && !empty($href)) {
      $sql = "SELECT count(*) FROM " . $this->tbl_links . " WHERE `id` = '" . $id . "'";
      $exist = $wpdb->get_var( $wpdb->prepare($sql) );
      if ( $exist == 1 ) {
        if ($wpdb->update( $this->tbl_links,
                    array( 'name' => $name, 'href' => $href, 'groups' => $groups, 'classes' => $classes ),
                    array( 'id' => $id ),
                    array( '%s', '%s', '%s', '%s' ),
                    array( '%d' ) ) ) {
          return $this->jsonReturn( true, __("Link updated.", 'hkail') );
        }
        else {
          return $this->jsonReturn( false, sprintf( __("Error: Update link failed. last_error: '%s' num_rows: '%s'.", 'hkail'), $wpdb->last_error, $wpdb->num_rows.$classes ) );
        }

      }
      else {
        return $this->jsonReturn( false, sprintf( __("Error: Link '%s' doesn't exist.", 'hkail'), $name) );
      }
    }
    else {
      return $this->jsonReturn( false, __("Error: empty variables.", 'hkail') );
    }
  }


  /* delete link in database */
  function delete($id) {
    global $wpdb;

    if (!empty($id)) {
      $sql = "SELECT count(*) FROM " . $this->tbl_links . " WHERE `id` = '" . $id . "'";
      $exist = $wpdb->get_var( $wpdb->prepare($sql) );
      if ( $exist == 1 ) {
        if ($wpdb->delete( $this->tbl_links,
                    array( 'id' => $id ),
                    array( '%d' ) ) ) {
          return $this->jsonReturn( true, __("Link deleted.", 'hkail') );
        }
        else {
          return $this->jsonReturn( false, sprintf( __("Error: Delete link failed. Log: %s.", 'hkail'), $wpdb->last_error ) );
        }

      }
      else {
        return $this->jsonReturn( false, sprintf( __("Error: Link '%s' doesn't exist.", 'hkail'), $id) );
      }
    }
    else {
      return $this->jsonReturn( false, __("Error: empty variables.", 'hkail') );
    }
  }


  /* create databases, used in activate plugin */
  function createDatabases() {
    global $wpdb;

    require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );

    #Check to see if the table exists already, if not, then create it
    if($wpdb->get_var( "show tables like '".$this->tbl_links."'" ) != $this->tbl_links)
    {
        $sql = "CREATE TABLE " . $this->tbl_links . " (
          `id`  int(11)   NOT NULL auto_increment,
          `name`  VARCHAR(128),
          `href`  VARCHAR(1280),
          `groups`  VARCHAR(1280),
          `classes`  VARCHAR(1280),
          PRIMARY KEY (`id`)
        ) " . $charset_collate . ";";
        //echo $sql;
        dbDelta($sql);
    }

    #Check to see if the table exists already, if not, then create it
    if($wpdb->get_var( "show tables like '".$this->tbl_user_link_clicks."'" ) != $this->tbl_user_link_clicks)
    {
        $sql = "CREATE TABLE " . $this->tbl_user_link_clicks . " (
          `id`  int(11)   NOT NULL auto_increment,
          `link_id`  int(11),
          `name_id`  int(11),
          `clickdate`  DATE,
          `clicktime`  TIME,
          PRIMARY KEY (`id`)
        ) " . $charset_collate . ";";
        //echo $sql;
        dbDelta($sql);
    }

  }


  /* JSON helper function */
  function jsonReturn($success, $result) {
    return json_encode( array('success' => $success, 'result' => $result ) );
  }
}

?>
