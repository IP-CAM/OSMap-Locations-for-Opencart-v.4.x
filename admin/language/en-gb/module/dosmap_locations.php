<?php
/**
 * Module D.OSMap Locations
 *
 * @version 1.0
 * 
 * @author D.art <d.art.reply@gmail.com>
 * @copyright Copyright (C) Jan 2023 D.art <d.art.reply@gmail.com>.
 * @license	GNU General Public License version 3
 */

// Heading
$_['heading_title']        = 'OSMap Locations';

// Tab
$_['tab_general']          = 'General';
$_['tab_locations']        = 'List of Addresses';
$_['tab_add_location']     = 'Add address';
$_['tab_settings']         = 'Settings';

// Entry
$_['entry_title']          = 'Title';
$_['entry_attr_id']        = 'Attribute ID';
$_['entry_general_name']   = 'Module Name';
$_['entry_general_title']  = 'Heading Title';
$_['entry_general_descr']  = 'Description';
$_['entry_filter']         = 'Filter';
$_['entry_locations']      = 'List of Addresses';
$_['entry_location_id']    = 'ID';
$_['entry_addition_info']  = 'Additional Information';
$_['entry_action']         = 'Action';
$_['entry_baloon_header']  = 'Header';
$_['entry_baloon_body']    = 'Description';
$_['entry_baloon_footer']  = 'Footer';
$_['entry_latitude']       = 'Latitude';
$_['entry_longitude']      = 'Longitude';
$_['entry_coords']         = 'Coordinates (lat,long)';
$_['entry_status']         = 'Status';
$_['entry_key_api']        = 'API Key';
$_['entry_ver_api']        = 'API Version';
$_['entry_limit_site']     = 'Addresses per page (Site)';
$_['entry_limit_admin']    = 'Addresses per page (Admin)';
$_['entry_map_settings']   = 'Map Settings';
$_['entry_language']       = 'Language';
$_['entry_language_code']  = 'Enter language code';
$_['entry_clusterization'] = 'Clusterization';
$_['entry_baloon_info']    = 'Address information (on click)';
$_['entry_init_coords']    = 'Centering the map on page load';
$_['entry_init_zoom']      = 'Map scaling (Zoom) on page load';
$_['entry_pan_zoom']       = 'Map scaling (Zoom) when going to an address';
$_['entry_json_coords']    = 'Update JSON-coordinates';
$_['entry_data_delete']    = 'Delete all data after uninstall the module (global setting)';

// Text
$_['text_extension']       = 'Extensions';
$_['text_success']         = 'Success: You have modified module!';
$_['text_edit']            = 'Edit Module';
$_['text_loc_list_empty']  = 'The address list is empty.<br><button type="button" class="add_location-list">Add new address</button>';
$_['text_loc_list_fempty'] = 'No addresses found.<br>Change filter options.';
$_['text_update_not']      = "Don't update";
$_['text_update_auto']     = 'Automatically. When adding/updating/deleting address.';
$_['text_update_manual']   = 'Manually. By clicking the "Update JSON file" button.';
$_['text_loc_place']       = 'Country, City, Street, House, ...';
$_['text_filter_title']    = 'Enter a Title';
$_['text_filter_header']   = 'Enter a Header';
$_['text_filter_footer']   = 'Enter a Footer';
$_['text_filter_lat']      = 'Enter a Latitude';
$_['text_filter_long']     = 'Enter a Longitude';

// Help
$_['help_btn_add']         = 'Add';
$_['help_btn_rem_check']   = 'Delete selected addresses';
$_['help_key_api']         = 'Key for «JavaScript API и HTTP Геокодер»';
$_['help_language_code']   = 'Enter the language code in the format: language_REGION. For example, for English: en_US';

// Button
$_['button_add']           = 'Add';
$_['button_add_location']  = 'Add Address';
$_['button_remove_check']  = 'Delete selected addresses';
$_['button_lock']          = 'Lock';
$_['button_unlock']        = 'Unlock';
$_['button_filter']        = 'Filter';
$_['button_filter_reset']  = 'Reset';
$_['button_update_json']   = 'Update JSON file';
$_['button_save_sets']     = 'Save Settings';

// Alert
$_['alert_confirm_rem']    = 'Confirm address deletion';
$_['alert_confirm_remove'] = 'Confirm deletion of selected addresses';

// Success
$_['success_json_put']     = 'Success: JSON file updated successfully!';
$_['success_mes_add']      = 'Success: Address was added successfully! To see the address in the list, <a href="#" onclick="location.reload(); return false;" style="font-weight: 700; text-decoration: underline;">reload the page</a>.';
$_['success_mes_open']     = 'Success: Address was obtained successfully!';
$_['success_mes_save']     = 'Success: Address was updated successfully!';
$_['success_mes_rem']      = 'Success: Address was removed successfully!';
$_['success_mes_remove']   = 'Success: Addresses was removed successfully!';

// Error
$_['error_permission']     = 'Warning: You do not have permission to modify module!';
$_['error_required']       = 'Warning: Check the module for errors!';
$_['error_key_api']        = 'Warning: Key API must be from 3 characters!';
$_['error_json_put']       = 'Warning: JSON file not updated! Check Settings!';
$_['error_name']           = 'Module Name must be from 3 to 64 characters!';
$_['error_mes_add']        = 'Address not added! Check the form!';
$_['error_mes_open']       = 'Address not obtained! Please refresh the page and try again!';
$_['error_mes_save']       = 'Address not updated! Check the form!';
$_['error_mes_rem']        = 'Address not removed! Check the form!';
$_['error_mes_remove']     = 'Addresses not removed! Check the form!';
$_['error_title']          = 'Title must be from 3 to 90 characters!';
$_['error_latitude']       = 'Latitude not correct!';
$_['error_longitude']      = 'Longitude not correct!';
$_['error_coords']         = 'Coordinates must be from 10 to 30 characters!';
$_['error_module_id']      = 'Missing module ID!';