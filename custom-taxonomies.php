<?php
/**
 * Add and configure custom taxonomies for this theme.
 */
namespace SDES\BaseTheme\Taxonomies;
use SDES\CustomTaxonomy as CustomTaxonomy;

require_once( get_stylesheet_directory().'/functions/class-custom-taxonomy.php' );

/**
 * Describes organizational groups
 *
 * @author Chris Conover
 **/
class OrganizationalGroups extends CustomTaxonomy
{
	public
	$name               = 'org_groups',
	$general_name       = 'Organizational Groups',
	$singular_name      = 'Organizational Group',
	$search_items       = 'Search Organizational Groups',
	$popular_items      = 'Popular Organizational Groups',
	$all_times          = 'All Organizational Groups',
	$parent_item        = 'Parent Organizational Group',
	$parent_item_colon  = 'Parent Organizational Group:',
	$edit_item          = 'Edit Organizational Group',
	$update_item        = 'Update Organizational Group',
	$add_new_item       = 'Add New Organizational Group',
	$new_item_name      = 'New Tag Organizational Group',
	
	$show_admin_column  = True,
	$hierarchical       = True;
} // END class 

/**
 * Describes archival storage groups for Records
 */
class ArchivalGroups extends CustomTaxonomy
{
	public
	$name               = 'arc_groups',
	$general_name       = 'Archival Groups',
	$singular_name      = 'Archival Group',
	$search_items       = 'Search Archival Groups',
	$popular_items      = 'Popular Archival Groups',
	$all_times          = 'All Archival Groups',
	$parent_item        = 'Parent Archival Group',
	$parent_item_colon  = 'Parent Archival Group:',
	$edit_item          = 'Edit Archival Group',
	$update_item        = 'Update Archival Group',
	$add_new_item       = 'Add New Archival Group',
	$new_item_name      = 'New Tag Archival Group',
	
	$show_admin_column  = True,
	$hierarchical       = True;
}

function register_custom_taxonomies() {
	CustomTaxonomy::Register_Taxonomies( array(
		__NAMESPACE__.'\OrganizationalGroups',
		__NAMESPACE__.'\ArchivalGroups',
		));
}
add_action('init', __NAMESPACE__.'\register_custom_taxonomies');
