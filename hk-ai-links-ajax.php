<?php


/*  register clicks */
function hkail_click_ajax_func() {

  global $hkail_obj;

  /* get data */
  $data = json_decode(stripslashes($_REQUEST["data"]));
  $id = $user = "";
  if (!empty($data) && is_object($data)) {
    if (!empty($data->id)) { $id = $data->id; }
    if (!empty($data->user)) { $user = $data->user; }
  }

  /* insert data in database */
  echo $hkail_obj->click($user, $id);

  die(); // this is required to return a proper result
}
add_action("wp_ajax_hkail_click", "hkail_click_ajax_func");
add_action("wp_ajax_nopriv_hkail_click", "hkail_click_ajax_func");


/* get most clicked links */
function hkail_get_most_clicked_ajax_func() {

  global $hkail_obj;

  /* get data */
  $data = json_decode(stripslashes($_REQUEST["data"]));
  $user = "";
  $numLinks = "10";
  if (!empty($data) /*&& is_object($data)*/) {
    if (!empty($data->user)) { $user = $data->user; }
    if (!empty($data->numLinks)) { $numLinks = $data->numLinks; }
  }

  /* get data from database */
  echo $hkail_obj->getMostClicked($user, $numLinks);

  die(); // this is required to return a proper result}
}
add_action("wp_ajax_hkail_get_most_clicked", "hkail_get_most_clicked_ajax_func");
add_action("wp_ajax_nopriv_hkail_get_most_clicked", "hkail_get_most_clicked_ajax_func");




/* ADMIN AJAX FUNCTIONS */
function hkail_add_new_ajax_func() {
  global $hkail_obj;

  /* get data */
  $data = json_decode(stripslashes($_REQUEST["data"]));

  $name = "";
  $href = "";
  $groups = "";
  $classes = "";
  if (!empty($data) && is_object($data)) {
    if (!empty($data->name)) { $name = $data->name; }
    if (!empty($data->href)) { $href = $data->href; }
    if (!empty($data->groups)) { $groups = $data->groups; }
    if (!empty($data->classes)) { $classes = $data->classes; }
  }

  /* add new link */
  echo $hkail_obj->add($name, $href, $groups, $classes);

  die(); // this is required to return a proper result}
}
add_action("wp_ajax_hkail_add_new", "hkail_add_new_ajax_func");
add_action("wp_ajax_nopriv_hkail_add_new", "hkail_add_new_ajax_func");

function hkail_update_ajax_func() {
  global $hkail_obj;

  /* get data */
  $data = json_decode(stripslashes($_REQUEST["data"]));

  $id = "";
  $name = "";
  $href = "";
  $groups = "";
  $classes = "";
  if (!empty($data) && is_object($data)) {
    if (!empty($data->id)) { $id = $data->id; }
    if (!empty($data->name)) { $name = $data->name; }
    if (!empty($data->href)) { $href = $data->href; }
    if (!empty($data->groups)) { $groups = $data->groups; }
    if (!empty($data->classes)) { $classes = $data->classes; }
  }

  /* add new link */
  echo $hkail_obj->update($id, $name, $href, $groups, $classes);

  die(); // this is required to return a proper result}
}
add_action("wp_ajax_hkail_update", "hkail_update_ajax_func");
add_action("wp_ajax_nopriv_hkail_update", "hkail_update_ajax_func");

function hkail_delete_ajax_func() {
  global $hkail_obj;

  /* get data */
  $data = json_decode(stripslashes($_REQUEST["data"]));

  $id = "";
  if (!empty($data) && is_object($data)) {
    if (!empty($data->id)) { $id = $data->id; }
  }

  /* add new link */
  echo $hkail_obj->delete($id);

  die(); // this is required to return a proper result}
}
add_action("wp_ajax_hkail_delete", "hkail_delete_ajax_func");
add_action("wp_ajax_nopriv_hkail_delete", "hkail_delete_ajax_func");


function hkail_get_link_list_ajax_func() {
  global $hkail_obj;

  /* get link list */
  echo $hkail_obj->getAdminLinkList();

  die(); // this is required to return a proper result}
}
add_action("wp_ajax_hkail_get_link_list", "hkail_get_link_list_ajax_func");
add_action("wp_ajax_nopriv_hkail_get_link_list", "hkail_get_link_list_ajax_func");
