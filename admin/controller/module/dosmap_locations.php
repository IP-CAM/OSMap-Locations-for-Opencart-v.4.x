<?php
/**
 * Controller Module D.OSMap Locations Class
 *
 * @version 1.0
 * 
 * @author D.art <d.art.reply@gmail.com>
 */

namespace Opencart\Admin\Controller\Extension\DOSMapLocations\Module;
class DOSMapLocations extends \Opencart\System\Engine\Controller {
	private $error = array();

    public function index(): void {
        $this->load->language('extension/dosmap_locations/module/dosmap_locations');

        $this->load->model('setting/module');
        $this->load->model('setting/setting');
        $this->load->model('localisation/language');
        $this->load->model('extension/dosmap_locations/module/dosmap_locations');

        $x = (version_compare(VERSION, '4.0.2.0', '>=')) ? '.' : '|';

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if (!isset($this->request->get['module_id'])) {
                // Get POST data.
                $post = $this->request->post;

                // Delete DB-data from POST.
                unset($post['filter'], $post['location_data'], $post['add_location']);

                // Add Module data to DB.
                $this->model_setting_module->addModule('dosmap_locations.dosmap_locations', $post);
                $module_id = $this->db->getLastId();

                if (isset($post['module_dosmap_locations_data_delete'])) {
                    $data_delete = $post['module_dosmap_locations_data_delete'];
                } else {
                    $data_delete = '0';
                }

                // Add Settings to DB.
                $config_settings = array('module_dosmap_locations_data_delete' => $data_delete);
                $this->model_setting_setting->editSetting('module_dosmap_locations', $config_settings);

                // Get Module data from DB.
                $module_settings = $this->model_setting_module->getModule($module_id);
                $module_settings['module_id'] = $module_id;

                // Add new data to Module DB.
                $this->model_setting_module->editModule($module_id, $module_settings);

                // Add Demo data to DB by Module ID.
                $location_id = $this->model_extension_dosmap_locations_module_dosmap_locations->addLocation($this->demoData(), $module_id);
            } else {
                // Get POST data.
                $post = $this->request->post;

                // Get Module ID.
                $post['module_id'] = $this->request->get['module_id'];

                // Delete DB-data from POST.
                unset($post['filter'], $post['location_data'], $post['add_location']);

                // Add Module data to DB.
                $this->model_setting_module->editModule($this->request->get['module_id'], $post);

                if (isset($post['module_dosmap_locations_data_delete'])) {
                    $data_delete = $post['module_dosmap_locations_data_delete'];
                } else {
                    $data_delete = '0';
                }

                // Add Settings to DB.
                $config_settings = array('module_dosmap_locations_data_delete' => $data_delete);
                $this->model_setting_setting->editSetting('module_dosmap_locations', $config_settings);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
        }

        $this->document->addScript(HTTP_CATALOG . 'admin/view/javascript/ckeditor/ckeditor.js');
        $this->document->addScript(HTTP_CATALOG . 'admin/view/javascript/ckeditor/adapters/jquery.js');

        $this->document->addStyle(HTTP_CATALOG . 'extension/dosmap_locations/admin/view/javascript/module-dosmap_locations/dosmap_locations.css');
        $this->document->addScript(HTTP_CATALOG . 'extension/dosmap_locations/admin/view/javascript/module-dosmap_locations/dosmap_locations.js');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (!empty($this->error) && (!isset($this->error['warning']) || count($this->error) > 1)) {
            $data['error_required'] = $this->language->get('error_required');
        } else {
            $data['error_required'] = '';
        }

        if (isset($this->request->get['filter_title'])) {
            $filter_title = $this->request->get['filter_title'];
        } else {
            $filter_title = '';
        }

        if (isset($this->request->get['filter_baloon_header'])) {
            $filter_baloon_header = $this->request->get['filter_baloon_header'];
        } else {
            $filter_baloon_header = '';
        }

        if (isset($this->request->get['filter_baloon_footer'])) {
            $filter_baloon_footer = $this->request->get['filter_baloon_footer'];
        } else {
            $filter_baloon_footer = '';
        }

        if (isset($this->request->get['filter_latitude'])) {
            $filter_latitude = $this->request->get['filter_latitude'];
        } else {
            $filter_latitude = '';
        }

        if (isset($this->request->get['filter_longitude'])) {
            $filter_longitude = $this->request->get['filter_longitude'];
        } else {
            $filter_longitude = '';
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = '';
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'title';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['module_id'])) {
            $url .= '&module_id=' . $this->request->get['module_id'];
        }

        if (isset($this->request->get['filter_title'])) {
            $url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_baloon_header'])) {
            $url .= '&filter_baloon_header=' . urlencode(html_entity_decode($this->request->get['filter_baloon_header'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_baloon_footer'])) {
            $url .= '&filter_baloon_footer=' . urlencode(html_entity_decode($this->request->get['filter_baloon_footer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_latitude'])) {
            $url .= '&filter_latitude=' . $this->request->get['filter_latitude'];
        }

        if (isset($this->request->get['filter_longitude'])) {
            $url .= '&filter_longitude=' . $this->request->get['filter_longitude'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        if (isset($this->request->get['tab'])) {
            $url .= '&tab=locations';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/dosmap_locations/module/dosmap_locations', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        $data['action'] = $this->url->link('extension/dosmap_locations/module/dosmap_locations' . $x . 'save', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

        $url = '';

        if (isset($this->request->get['module_id'])) {
            $url .= '&module_id=' . $this->request->get['module_id'];
        }

        $data['action_add'] = $this->url->link('extension/dosmap_locations/module/dosmap_locations' . $x . 'add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['action_open'] = $this->url->link('extension/dosmap_locations/module/dosmap_locations' . $x . 'open', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['action_edit'] = $this->url->link('extension/dosmap_locations/module/dosmap_locations' . $x . 'edit', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['action_delete'] = $this->url->link('extension/dosmap_locations/module/dosmap_locations' . $x . 'delete', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['action_update_json'] = $this->url->link('extension/dosmap_locations/module/dosmap_locations' . $x . 'updateJSON', 'user_token=' . $this->session->data['user_token'] . $url, true);

        if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $module_info = $this->model_setting_module->getModule($this->request->get['module_id']);

            if (empty($module_info)) {
                $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
            }
        }

        if (isset($this->request->get['module_id'])) {
            $data['module_id'] = $this->request->get['module_id'];
        } else {
            $data['module_id'] = 0;
        }

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($module_info)) {
            $data['name'] = $module_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['attr_ID'])) {
            $data['attr_ID'] = $this->request->post['attr_ID'];
        } elseif (!empty($module_info)) {
            $data['attr_ID'] = $module_info['attr_ID'];
        } else {
            $data['attr_ID'] = '';
        }

        if (isset($this->request->post['module_description'])) {
            $data['module_description'] = $this->request->post['module_description'];
        } elseif (!empty($module_info)) {
            $data['module_description'] = $module_info['module_description'];
        } else {
            $data['module_description'] = array();
        }

        if (isset($this->request->post['mapinit_latitude'])) {
            $data['mapinit_latitude'] = $this->request->post['mapinit_latitude'];
        } elseif (!empty($module_info)) {
            $data['mapinit_latitude'] = $module_info['mapinit_latitude'];
        } else {
            $data['mapinit_latitude'] = 25.197301;
        }

        if (isset($this->request->post['mapinit_longitude'])) {
            $data['mapinit_longitude'] = $this->request->post['mapinit_longitude'];
        } elseif (!empty($module_info)) {
            $data['mapinit_longitude'] = $module_info['mapinit_longitude'];
        } else {
            $data['mapinit_longitude'] = 55.274242;
        }

        if (isset($this->request->post['mapinit_zoom'])) {
            $data['mapinit_zoom'] = $this->request->post['mapinit_zoom'];
        } elseif (!empty($module_info)) {
            $data['mapinit_zoom'] = $module_info['mapinit_zoom'];
        } else {
            $data['mapinit_zoom'] = 11;
        }

        if (isset($this->request->post['mappan_zoom'])) {
            $data['mappan_zoom'] = $this->request->post['mappan_zoom'];
        } elseif (!empty($module_info)) {
            $data['mappan_zoom'] = $module_info['mappan_zoom'];
        } else {
            $data['mappan_zoom'] = 18;
        }

        if (isset($this->request->post['map_language'])) {
            $data['map_language'] = $this->request->post['map_language'];
        } elseif (!empty($module_info)) {
            $data['map_language'] = $module_info['map_language'];
        } else {
            $data['map_language'] = 'osm';
        }

        if (isset($this->request->post['map_language_custom'])) {
            $data['map_language_custom'] = $this->request->post['map_language_custom'];
        } elseif (!empty($module_info['map_language_custom'])) {
            $data['map_language_custom'] = $module_info['map_language_custom'];
        } else {
            $data['map_language_custom'] = '';
        }

        if (isset($this->request->post['clusterization'])) {
            $data['clusterization'] = $this->request->post['clusterization'];
        } elseif (!empty($module_info)) {
            $data['clusterization'] = $module_info['clusterization'];
        } else {
            $data['clusterization'] = 0;
        }

        if (isset($this->request->post['baloon_info'])) {
            $data['baloon_info'] = $this->request->post['baloon_info'];
        } elseif (!empty($module_info)) {
            $data['baloon_info'] = $module_info['baloon_info'];
        } else {
            $data['baloon_info'] = 0;
        }

        if (isset($this->request->post['json_coords'])) {
            $data['json_coords'] = $this->request->post['json_coords'];
        } elseif (!empty($module_info)) {
            $data['json_coords'] = $module_info['json_coords'];
        } else {
            $data['json_coords'] = '';
        }

        if (isset($this->request->post['limit_site'])) {
            $data['limit_site'] = $this->request->post['limit_site'];
        } elseif (!empty($module_info)) {
            $data['limit_site'] = $module_info['limit_site'];
        } else {
            $data['limit_site'] = 24;
        }

        if (isset($this->request->post['limit_admin'])) {
            $data['limit_admin'] = $this->request->post['limit_admin'];
        } elseif (!empty($module_info)) {
            $data['limit_admin'] = $module_info['limit_admin'];
        } else {
            $data['limit_admin'] = 24;
        }

        if (isset($this->request->post['ver_api'])) {
            $data['ver_api'] = $this->request->post['ver_api'];
        } elseif (!empty($module_info)) {
            $data['ver_api'] = $module_info['ver_api'];
        } else {
            $data['ver_api'] = 'leaflet_194';
        }

        if (isset($this->request->post['key_api'])) {
            $data['key_api'] = $this->request->post['key_api'];
        } elseif (!empty($module_info)) {
            $data['key_api'] = $module_info['key_api'];
        } else {
            $data['key_api'] = '';
        }

        if (isset($this->request->post['module_dosmap_locations_data_delete'])) {
            $data['data_delete'] = $this->request->post['module_dosmap_locations_data_delete'];
        } else if (!empty($this->config->get('module_dosmap_locations_data_delete'))) {
            $data['data_delete'] = $this->config->get('module_dosmap_locations_data_delete');
        } else {
            $data['data_delete'] = 0;
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($module_info)) {
            $data['status'] = $module_info['status'];
        } else {
            $data['status'] = 0;
        }

        if (isset($this->request->post['add_location'])) {
            $data['add_location'] = $this->request->post['add_location'];
        } else {
            $data['add_location'] = array();
        }

        /* Languages */

        $data['languages'] = $this->model_localisation_language->getLanguages();

        /* Locations */

        $data['locations'] = array();

        $filter_data = array(
            'language_id'          => (int)$this->config->get('config_language_id'),
            'module_id'            => $data['module_id'],
            'filter_title'         => $filter_title,
            'filter_baloon_header' => $filter_baloon_header,
            'filter_baloon_footer' => $filter_baloon_footer,
            'filter_latitude'      => $filter_latitude,
            'filter_longitude'     => $filter_longitude,
            'filter_status'        => $filter_status,
            'sort'                 => $sort,
            'order'                => $order,
            'start'                => ($page - 1) * $data['limit_admin'],
            'limit'                => $data['limit_admin']
        );

        $results = $this->model_extension_dosmap_locations_module_dosmap_locations->getLocations($filter_data);

        foreach ($results as $result) {
            $data['locations'][] = array(
                'location_id' => $result['location_id'],
                'module_id'   => $result['module_id'],
                'latitude'    => $result['latitude'],
                'longitude'   => $result['longitude'],
                'status'      => $result['status'],
                'description' => array(
                    'language_id'   => $result['language_id'],
                    'title'         => $result['title'],
                    'baloon_header' => $result['baloon_header'],
                    'baloon_body'   => $result['baloon_body'],
                    'baloon_footer' => $result['baloon_footer']
                )
            );
        }

        /* Empty locations message */

        if ((!empty($filter_title) || !empty($filter_baloon_header) || !empty($filter_baloon_footer) || 
             !empty($filter_latitude) || !empty($filter_longitude) || 
             (isset($filter_status) && $filter_status !== '')) && empty($data['locations'])) {
                $data['text_loc_list_empty'] = $this->language->get('text_loc_list_fempty');
        }

        /* Totals */

        $filter_data = array(
            'language_id'          => (int)$this->config->get('config_language_id'),
            'module_id'            => $data['module_id'],
            'filter_title'         => $filter_title,
            'filter_baloon_header' => $filter_baloon_header,
            'filter_baloon_footer' => $filter_baloon_footer,
            'filter_latitude'      => $filter_latitude,
            'filter_longitude'     => $filter_longitude,
            'filter_status'        => $filter_status,
        );

        $location_total = $this->model_extension_dosmap_locations_module_dosmap_locations->getTotalLocations($filter_data);

        /* Sort URLs */

        $url = '';

        if (isset($this->request->get['module_id'])) {
            $url .= '&module_id=' . $this->request->get['module_id'];
        }

        if (isset($this->request->get['filter_title'])) {
            $url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_baloon_header'])) {
            $url .= '&filter_baloon_header=' . urlencode(html_entity_decode($this->request->get['filter_baloon_header'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_baloon_footer'])) {
            $url .= '&filter_baloon_footer=' . urlencode(html_entity_decode($this->request->get['filter_baloon_footer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_latitude'])) {
            $url .= '&filter_latitude=' . $this->request->get['filter_latitude'];
        }

        if (isset($this->request->get['filter_longitude'])) {
            $url .= '&filter_longitude=' . $this->request->get['filter_longitude'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        if (isset($this->request->get['tab'])) {
            $url .= '&tab=locations';
        }

        $data['sort_location_id'] = $this->url->link('extension/dosmap_locations/module/dosmap_locations', 'user_token=' . $this->session->data['user_token'] . $url . '&sort=location_id', true);
        $data['sort_title'] = $this->url->link('extension/dosmap_locations/module/dosmap_locations', 'user_token=' . $this->session->data['user_token'] . $url . '&sort=title', true);
        $data['sort_baloon_header'] = $this->url->link('extension/dosmap_locations/module/dosmap_locations', 'user_token=' . $this->session->data['user_token'] . $url . '&sort=baloon_header', true);
        $data['sort_baloon_footer'] = $this->url->link('extension/dosmap_locations/module/dosmap_locations', 'user_token=' . $this->session->data['user_token'] . $url . '&sort=baloon_footer', true);
        $data['sort_latitude'] = $this->url->link('extension/dosmap_locations/module/dosmap_locations', 'user_token=' . $this->session->data['user_token'] . $url . '&sort=latitude', true);
        $data['sort_longitude'] = $this->url->link('extension/dosmap_locations/module/dosmap_locations', 'user_token=' . $this->session->data['user_token'] . $url . '&sort=longitude', true);
        $data['sort_status'] = $this->url->link('extension/dosmap_locations/module/dosmap_locations', 'user_token=' . $this->session->data['user_token'] . $url . '&sort=status', true);
        $data['sort_order'] = $this->url->link('extension/dosmap_locations/module/dosmap_locations', 'user_token=' . $this->session->data['user_token'] . $url . '&sort=sort_order', true);

        /* Pagination */

        $url = '';

        if (isset($this->request->get['filter_title'])) {
            $url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_baloon_header'])) {
            $url .= '&filter_baloon_header=' . urlencode(html_entity_decode($this->request->get['filter_baloon_header'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_baloon_footer'])) {
            $url .= '&filter_baloon_footer=' . urlencode(html_entity_decode($this->request->get['filter_baloon_footer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_latitude'])) {
            $url .= '&filter_latitude=' . $this->request->get['filter_latitude'];
        }

        if (isset($this->request->get['filter_longitude'])) {
            $url .= '&filter_longitude=' . $this->request->get['filter_longitude'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['module_id'])) {
            $url .= '&module_id=' . $this->request->get['module_id'];
        }

        $data['pagination'] = $this->load->controller('common/pagination', [
            'total' => $location_total,
            'page'  => $page,
            'limit' => $data['limit_admin'],
            'url'   => $this->url->link('extension/dosmap_locations/module/dosmap_locations', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}&tab=locations')
        ]);

        $data['results'] = sprintf($this->language->get('text_pagination'), ($location_total) ? (($page - 1) * $data['limit_admin']) + 1 : 0, ((($page - 1) * $data['limit_admin']) > ($location_total - $data['limit_admin'])) ? $location_total : ((($page - 1) * $data['limit_admin']) + $data['limit_admin']), $location_total, ceil($location_total / $data['limit_admin']));

        /* Filter data */

        if (isset($this->request->get['tab'])) {
            $url .= '&tab=locations';
        }

        $data['filter_title'] = $filter_title;
        $data['filter_baloon_header'] = $filter_baloon_header;
        $data['filter_baloon_footer'] = $filter_baloon_footer;
        $data['filter_latitude'] = $filter_latitude;
        $data['filter_longitude'] = $filter_longitude;
        $data['filter_status'] = $filter_status;

        $data['filter_url'] = $this->url->link('extension/dosmap_locations/module/dosmap_locations', 'user_token=' . $this->session->data['user_token'] . $url, true);
        //$data['filter_url'] = $this->url->link('extension/dosmap_locations/module/dosmap_locations' . $x . 'list', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $url = '';

        if (isset($this->request->get['module_id'])) {
            $url .= '&module_id=' . $this->request->get['module_id'];
        }

        $data['filter_autocomplete'] = $this->url->link('extension/dosmap_locations/module/dosmap_locations' . $x . 'autocomplete', 'user_token=' . $this->session->data['user_token'] . $url, true);

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $data['filter_reset_url'] = $this->url->link('extension/dosmap_locations/module/dosmap_locations', 'user_token=' . $this->session->data['user_token'] . $url, true);

        /* Sort/Order data */

		$data['sort'] = $sort;
		$data['order'] = $order;

        /* Output */

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/dosmap_locations/module/dosmap_locations', $data));
    }

    /**
    * List of Locations. AJAX.
    *
    * @return string
    */
	public function list(): string {
		$this->load->language('extension/dosmap_locations/module/dosmap_locations');

        $json = '';

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}

    /**
     * Save Module Data.
     *
     * @return void
     */
    public function save(): void {
        $this->load->language('extension/dosmap_locations/module/dosmap_locations');

        $json = [];

        if (!$this->user->hasPermission('modify', 'extension/dosmap_locations/module/dosmap_locations')) {
            $json['error']['warning'] = $this->language->get('error_permission');
        }

        if ((mb_strlen($this->request->post['name']) < 3) || (mb_strlen($this->request->post['name']) > 64)) {
            $json['error']['name'] = $this->language->get('error_name');
        }

        if (!$json) {
            $this->load->model('setting/module');
            $this->load->model('setting/setting');

            if (!isset($this->request->get['module_id'])) {
                $this->load->model('extension/dosmap_locations/module/dosmap_locations');

                // Get POST data.
                $post = $this->request->post;

                // Delete DB-data from POST.
                unset($post['filter'], $post['location_data'], $post['add_location']);

                // Add Module data to DB.
                $this->model_setting_module->addModule('dosmap_locations.dosmap_locations', $post);
                $module_id = $this->db->getLastId();

                if (isset($post['module_dosmap_locations_data_delete'])) {
                    $data_delete = $post['module_dosmap_locations_data_delete'];
                } else {
                    $data_delete = '0';
                }

                // Add Settings to DB.
                $config_settings = array('module_dosmap_locations_data_delete' => $data_delete);
                $this->model_setting_setting->editSetting('module_dosmap_locations', $config_settings);

                // Get Module data from DB.
                $module_settings = $this->model_setting_module->getModule($module_id);
                $module_settings['module_id'] = $module_id;

                // Add new data to Module DB.
                $this->model_setting_module->editModule($module_id, $module_settings);

                // Add Demo data to DB by Module ID.
                $location_id = $this->model_extension_dosmap_locations_module_dosmap_locations->addLocation($this->demoData(), $module_id);
            } else {
                // Get POST data.
                $post = $this->request->post;

                // Get Module ID.
                $post['module_id'] = $this->request->get['module_id'];

                // Delete DB-data from POST.
                unset($post['filter'], $post['location_data'], $post['add_location']);

                // Add Module data to DB.
                $this->model_setting_module->editModule($this->request->get['module_id'], $post);

                if (isset($post['module_dosmap_locations_data_delete'])) {
                    $data_delete = $post['module_dosmap_locations_data_delete'];
                } else {
                    $data_delete = '0';
                }

                // Add Settings to DB.
                $config_settings = array('module_dosmap_locations_data_delete' => $data_delete);
                $this->model_setting_setting->editSetting('module_dosmap_locations', $config_settings);
            }

            $json['success'] = $this->language->get('text_success');
        } else {
            if (!isset($json['error']['warning'])) {
                $json['error']['warning'] = $this->language->get('error_required');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    /**
    * Add Location. AJAX.
    *
    * @return void
    */
	public function add(): void {
		$this->load->language('extension/dosmap_locations/module/dosmap_locations');

        $json = array();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate_add()) {
            $this->load->model('setting/module');
            $this->load->model('extension/dosmap_locations/module/dosmap_locations');

            $module_id = (int)$this->request->get['module_id'];

            $location_id = $this->model_extension_dosmap_locations_module_dosmap_locations->addLocation($this->request->post['add_location'], $module_id);

            $module_info = $this->model_setting_module->getModule($module_id);

            $json['data'] = array(
                'location_id' => $location_id
            );

            if (isset($module_info['json_coords']) && $module_info['json_coords'] == 1) {
                $json = $this->updateJSONFile($json, (int)$module_info['json_coords'], $module_id);
            }

            $json['success'] = true;
            $json['message'] = $this->language->get('success_mes_add');
            $json['errors'] = false;
        } else {
            $json['success'] = false;
            $json['message'] = $this->language->get('error_mes_add');

            if (!empty($this->error)) {
                $json['errors'] = $this->error;
            } else {
                $json['errors'] = false;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}

    /**
    * Open Location. AJAX.
    * Get Location Descriptions.
    *
    * @return void
    */
	public function open(): void {
		$this->load->language('extension/dosmap_locations/module/dosmap_locations');

        $json = array();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate_open()) {
            $this->load->model('setting/module');
            $this->load->model('extension/dosmap_locations/module/dosmap_locations');

            $module_id = (int)$this->request->get['module_id'];

            $json['location'] = $this->model_extension_dosmap_locations_module_dosmap_locations->getLocationDescriptions($this->request->post['location_data']);

            $json['success'] = true;
            $json['message'] = $this->language->get('success_mes_open');
            $json['errors'] = false;
        } else {
            $json['success'] = false;
            $json['message'] = $this->language->get('error_mes_open');

            if (!empty($this->error)) {
                $json['errors'] = $this->error;
            } else {
                $json['errors'] = false;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}

    /**
    * Edit Location. AJAX.
    *
    * @return void
    */
	public function edit(): void {
		$this->load->language('extension/dosmap_locations/module/dosmap_locations');

        $json = array();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate_edit()) {
            $this->load->model('setting/module');
            $this->load->model('extension/dosmap_locations/module/dosmap_locations');

            $module_id = (int)$this->request->get['module_id'];

            $this->model_extension_dosmap_locations_module_dosmap_locations->editLocation($this->request->post['location_data']);

            $module_info = $this->model_setting_module->getModule($module_id);

            if (isset($module_info['json_coords']) && $module_info['json_coords'] == 1) {
                $json = $this->updateJSONFile($json, (int)$module_info['json_coords'], $module_id);
            }

            $json['success'] = true;
            $json['message'] = $this->language->get('success_mes_save');
            $json['errors'] = false;
            $json['language_id'] = (int)$this->config->get('config_language_id');
        } else {
            $json['success'] = false;
            $json['message'] = $this->language->get('error_mes_save');
            $json['language_id'] = (int)$this->config->get('config_language_id');

            if (!empty($this->error)) {
                $json['errors'] = $this->error;
            } else {
                $json['errors'] = false;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}

    /**
    * Delete Location. AJAX.
    *
    * @return void
    */
	public function delete(): void {
		$this->load->language('extension/dosmap_locations/module/dosmap_locations');

        $json = array();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate_delete()) {
            $this->load->model('setting/module');
            $this->load->model('extension/dosmap_locations/module/dosmap_locations');

            if (isset($this->request->post['location_selected'])) {
                $this->model_extension_dosmap_locations_module_dosmap_locations->deleteLocation($this->request->post['location_selected']);
            } else {
                $this->model_extension_dosmap_locations_module_dosmap_locations->deleteLocation($this->request->post['location_data']);
            }

            $module_id = (int)$this->request->get['module_id'];

            $module_info = $this->model_setting_module->getModule($module_id);

            if (isset($module_info['json_coords']) && $module_info['json_coords'] == 1) {
                $json = $this->updateJSONFile($json, (int)$module_info['json_coords'], $module_id);
            }

            $json['success'] = true;
            $json['errors'] = false;

            if (isset($this->request->post['location_selected'])) {
                $json['message'] = $this->language->get('success_mes_remove');
            } else {
                $json['message'] = $this->language->get('success_mes_rem');
            }
        } else {
            $json['success'] = false;

            if (!empty($this->error)) {
                $json['errors'] = $this->error;
            } else {
                $json['errors'] = false;
            }

            if (isset($this->request->post['location_selected'])) {
                $json['message'] = $this->language->get('error_mes_remove');
            } else {
                $json['message'] = $this->language->get('error_mes_rem');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}

    /**
    * Update JSON File. AJAX.
    *
    * @return void
    */
	public function updateJSON(): void {
		$this->load->language('extension/dosmap_locations/module/dosmap_locations');

        $this->load->model('setting/module');

        $json = array();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate_updateJSON()) {
            $module_id = (int)$this->request->get['module_id'];

            $module_info = $this->model_setting_module->getModule($module_id);

            if (isset($module_info['json_coords']) && $module_info['json_coords'] == 2) {
                $json = $this->updateJSONFile($json, (int)$module_info['json_coords'], $module_id);
            } else {
                $json['json_file']['success'] = false;
                $json['json_file']['message'] = $this->language->get('error_json_put');
                $json['errors'] = false;
            }
        } else {
            $json['json_file']['success'] = false;
            $json['json_file']['message'] = $this->language->get('error_json_put');

            if (!empty($this->error)) {
                $json['errors'] = $this->error;
            } else {
                $json['errors'] = false;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}

    /**
    * Regenerate JSON File.
    *
    * @param array $json
    * @param int $module_json_coords
    * @param int $module_id
    *
    * @return array $json
    */
	private function updateJSONFile(array $json, int $module_json_coords, int $module_id): array {
		$this->load->language('extension/dosmap_locations/module/dosmap_locations');

        if ($module_json_coords) {
            $this->load->model('setting/module');
            $this->load->model('localisation/language');
            $this->load->model('extension/dosmap_locations/module/dosmap_locations');

            /* Languages */

            $languages = $this->model_localisation_language->getLanguages();

            /* Locations */

            $locations = array();

            foreach ($languages as $language_code => $language) {
                $locations[$language['language_id']] = array(
                    'type'     => 'FeatureCollection',
                    'metadata' => array(
                        'name'    => 'OSMap Locations',
                        'creator' => 'DOSMapLocations Controller'
                    ),
                    'features' => array()
                );
            }

            $results = array();

            $filter_data = array(
                'module_id'            => $module_id,
                'filter_title'         => '',
                'filter_baloon_header' => '',
                'filter_baloon_footer' => '',
                'filter_latitude'      => '',
                'filter_longitude'     => '',
                'filter_status'        => '1',
                'sort'                 => 'title',
                'order'                => 'ASC',
                'start'                => 0,
                'limit'                => 999999
            );

            foreach ($languages as $language_code => $language) {
                $filter_data['language_id'] = $language['language_id'];

                $results[$language['language_id']] = $this->model_extension_dosmap_locations_module_dosmap_locations->getLocations($filter_data);
            }

            foreach ($results as $language_id => $language) {
                foreach ($language as $result) {
                    $locations[$language_id]['features'][] = array(
                        'type'     => 'Feature',
                        'id'       => (int)$result['location_id'],
                        'geometry' => array(
                            'type'        => 'Point',
                            'coordinates' => array((double)$result['latitude'],(double)$result['longitude']),
                        ),
                        'properties' => array(
                            'iconContent'          => '',
                            'iconCaption'          => $result['title'],
                            'balloonContent'       => '',
                            'balloonContentHeader' => html_entity_decode($result['baloon_header']), // header of balloon
                            'balloonContentBody'   => html_entity_decode($result['baloon_body']),   // balloon content
                            'balloonContentFooter' => html_entity_decode($result['baloon_footer']), // footer of balloon
                            'clusterCaption'       => '',
                            'hintContent'          => $result['title'] // when 'hover' on placemark
                        )
                    );
                }
            }

            // Template-name of JSON-file:
            // $jsonName = 'points--$module_id--$language_id--$timestamp.geojson';

            // Path to JSON-files.
            $jsonPath = DIR_IMAGE . 'module-dosmap_locations/json/';

            // Create directory of JSON-files if needed.
            if (!is_dir($jsonPath)) {
                mkdir($jsonPath, 0755, true);
            }

            // Get array of JSON-files.
            $jsonFiles = array_values(array_diff(scandir($jsonPath), array('.', '..')));

            /* Get new array from array of JSON-files */

            $jsonFilesArray = array();

            foreach ($jsonFiles as $file) {
                $fileArray = explode('--', $file);

                $fileArrayCount = count($fileArray);

                if ($fileArray[0] == 'points' && $fileArrayCount == 4) {
                    $lastIndex = $fileArrayCount - 1;

                    $fileType = explode('.', $fileArray[$lastIndex]);

                    if (isset($fileType[1]) && $fileType[1] == 'geojson') {
                        $jsonFilesArray[$fileArray[1]][(int)$fileArray[2]][] = $fileType[0];
                    }
                }
            }

            // Current Timestamp.
            $timestamp = time();

            /* Put file to JSON-directory */

            foreach ($languages as $language_code => $language) {
                // Encode array to JSON.
                $jsonLanguageLocations = json_encode($locations[$language['language_id']]);

                // Names of JSON-files.
                $jsonNameMin = 'points--' . $module_id . '--' . $language['language_id'] . '--';
                $jsonNameCurrent = 'points--' . $module_id . '--' . $language['language_id'] . '--' . $timestamp;

                // Count of JSON-file array by language_id.
                if (isset($jsonFilesArray[$module_id][$language['language_id']])) {
                    $jsonFilesArrayCount = count($jsonFilesArray[$module_id][$language['language_id']]);
                } else {
                    $jsonFilesArrayCount = 0;
                }

                // Delete JSON-file with minimum Timestamp (older file).
                if (($jsonFilesArrayCount >= 3)) {
                    $min_timestamp = $jsonFilesArray[$module_id][$language['language_id']][0];
                    foreach ($jsonFilesArray[$module_id][$language['language_id']] as $value_timestamp) {
                        if ($value_timestamp < $min_timestamp) {
                            $min_timestamp = $value_timestamp;
                        }
                    }

                    foreach ($jsonFilesArray[$module_id][$language['language_id']] as $value_timestamp) {
                        if ($value_timestamp == $min_timestamp) {
                            $fileMin = $jsonPath . $jsonNameMin . $value_timestamp . '.geojson';
                            if (file_exists($fileMin)) unlink($fileMin);
                            break;
                        }
                    }
                }

                // Generate JSON-file.
                file_put_contents($jsonPath . $jsonNameCurrent . '.geojson', $jsonLanguageLocations);
            }

            // Add array of JSON-file to $json.
            //$json['json_file']['array'] = $jsonFilesArray;

            // Other data.
            $json['json_file']['success'] = true;
            $json['json_file']['message'] = $this->language->get('success_json_put');
        } else {
            $json['json_file']['success'] = false;
            $json['json_file']['message'] = $this->language->get('error_json_put');
        }

        return $json;
	}

    /**
    * Filter Locations by field-option. AJAX.
    *
    * @return void
    */
    public function autocomplete(): void {
        $json = array();

        if (isset($this->request->get['module_id']) && 
           (isset($this->request->get['filter_title']) || 
            isset($this->request->get['filter_baloon_header']) || 
            isset($this->request->get['filter_baloon_footer']))) {

            $this->load->model('extension/dosmap_locations/module/dosmap_locations');

            if (isset($this->request->get['filter_title'])) {
                $filter_title = $this->request->get['filter_title'];
            } else {
                $filter_title = '';
            }

            if (isset($this->request->get['filter_baloon_header'])) {
                $filter_baloon_header = $this->request->get['filter_baloon_header'];
            } else {
                $filter_baloon_header = '';
            }

            if (isset($this->request->get['filter_baloon_footer'])) {
                $filter_baloon_footer = $this->request->get['filter_baloon_footer'];
            } else {
                $filter_baloon_footer = '';
            }

            if (isset($this->request->get['limit'])) {
                $limit = (int)$this->request->get['limit'];
            } else {
                $limit = 5;
            }

            $filter_data = array(
                'language_id'          => (int)$this->config->get('config_language_id'),
                'module_id'            => $this->request->get['module_id'],
                'filter_title'         => $filter_title,
                'filter_baloon_header' => $filter_baloon_header,
                'filter_baloon_footer' => $filter_baloon_footer,
                'start'                => 0,
                'limit'                => $limit
            );

            $results = $this->model_extension_dosmap_locations_module_dosmap_locations->getLocations($filter_data);

            foreach ($results as $result) {
                $json[] = array(
                    'location_id'   => $result['location_id'],
					'title'         => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8')),
					'baloon_header' => strip_tags(html_entity_decode($result['baloon_header'], ENT_QUOTES, 'UTF-8')),
					'baloon_footer' => strip_tags(html_entity_decode($result['baloon_footer'], ENT_QUOTES, 'UTF-8'))
                );
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    /**
    * Validate Permission and Setting fiels.
    *
    * @return bool $this->error
    */
    protected function validate(): bool {
        if (!$this->user->hasPermission('modify', 'extension/dosmap_locations/module/dosmap_locations')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((mb_strlen($this->request->post['name']) < 3) || (mb_strlen($this->request->post['name']) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        return !$this->error;
    }

    /**
    * Validate fiels from 'Add Location' tab.
    *
    * @return bool $this->error
    */
    protected function validate_add(): bool {
        if (empty($this->request->get['module_id'])) {
            $this->error['module_id'] = $this->language->get('error_module_id');
        }

        if ((int)$this->request->post['add_location']['latitude'] <= 0 || !$this->validateLatitude($this->request->post['add_location']['latitude'])) {
            $this->error['latitude'] = $this->language->get('error_latitude');
        }

        if ((int)$this->request->post['add_location']['longitude'] <= 0 || !$this->validateLongitude($this->request->post['add_location']['longitude'])) {
            $this->error['longitude'] = $this->language->get('error_longitude');
        }

        foreach ($this->request->post['add_location']['description'] as $language_id => $value) {
            if ((mb_strlen($value['title']) < 3) || (mb_strlen($value['title']) > 90)) {
                $this->error['description'][$language_id]['title'] = $this->language->get('error_title');
            }
        }

        return !$this->error;
    }

    /**
    * Validate Location fields from 'open()' method.
    *
    * @return bool $this->error
    */
    protected function validate_open(): bool {
        if (empty($this->request->get['module_id'])) {
            $this->error['module_id'] = $this->language->get('error_module_id');
        }

        foreach ($this->request->post['location_data'] as $location_id => $location) {
            if ((int)$location_id <= 0) {
                $this->error['location'][$location_id]['location_id'] = $this->language->get('error_mes_open');
            }
        }

        return !$this->error;
    }

    /**
    * Validate Location fields from 'edit()' method.
    *
    * @return bool $this->error
    */
    protected function validate_edit(): bool {
        if (empty($this->request->get['module_id'])) {
            $this->error['module_id'] = $this->language->get('error_module_id');
        }

        foreach ($this->request->post['location_data'] as $location_id => $location) {
            if ((int)$location_id <= 0) {
                $this->error['location'][$location_id]['location_id'] = $this->language->get('error_mes_save');
            }

            if ((int)$location['latitude'] <= 0 || !$this->validateLatitude($location['latitude'])) {
                $this->error['location'][$location_id]['latitude'] = $this->language->get('error_latitude');
            }

            if ((int)$location['longitude'] <= 0 || !$this->validateLongitude($location['longitude'])) {
                $this->error['location'][$location_id]['longitude'] = $this->language->get('error_longitude');
            }

            foreach ($location['description'] as $language_id => $value) {
                if ((mb_strlen($value['title']) < 3) || (mb_strlen($value['title']) > 90)) {
                    $this->error['location'][$location_id]['description'][$language_id]['title'] = $this->language->get('error_title');
                }
            }
        }

        return !$this->error;
    }

    /**
    * Validate Location fields from 'delete()' method.
    *
    * @return bool $this->error
    */
    protected function validate_delete(): bool {
        if (empty($this->request->get['module_id'])) {
            $this->error['module_id'] = $this->language->get('error_module_id');
        }

        if (!isset($this->request->post['location_selected'])) {
            foreach ($this->request->post['location_data'] as $location_id => $location) {
                if ((int)$location_id <= 0) {
                    $this->error['location'][$location_id]['location_id'] = $this->language->get('error_mes_rem');
                }
            }
        }

        return !$this->error;
    }

    /**
    * Validate Module ID from 'updateJSON()' method.
    *
    * @return bool $this->error
    */
    protected function validate_updateJSON(): bool {
        if (empty($this->request->get['module_id'])) {
            $this->error['module_id'] = $this->language->get('error_module_id');
        }

        return !$this->error;
    }

    /**
    * Validate a given latitude.
    *
    * @param string $lat
    *
    * @return string $lat
    */
    private function validateLatitude($lat): string {
        return preg_match('/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/', $lat);
    }

    /**
    * Validate a given longitude.
    *
    * @param string $long
    *
    * @return string $long
    */
    private function validateLongitude($long): string {
        return preg_match('/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/', $long);
    }

    /**
    * Demo data.
    *
    * @return array $demo_array
    */
    private function demoData(): array {
        $this->load->model('localisation/language');

        /* Languages */

        $languages = $this->model_localisation_language->getLanguages();

        /* Demo data */

        $demo_data = array();

        $demo_data['en'] = array(
            'title'         => 'Burj Khalifa',
            'baloon_header' => 'Burj Khalifa, Downtown Dubai',
            'baloon_body'   => '
&lt;p&gt;&lt;span style=&quot;color:#999999; font-size:13px&quot;&gt;Landmark&lt;/span&gt;&lt;/p&gt;
&lt;hr /&gt;
&lt;p&gt;&lt;a href=&quot;tel:+97148888888&quot; style=&quot;display: block; color: #000; font-size: 14px;&quot; target=&quot;_blank&quot;&gt;+971 4 8888888&lt;/a&gt;&lt;a href=&quot;https://www.burjkhalifa.ae/&quot; style=&quot;display: block; color: #000; font-size: 14px;&quot; target=&quot;_blank&quot;&gt;https://www.burjkhalifa.ae/&lt;/a&gt;&lt;/p&gt;
&lt;hr /&gt;
&lt;p&gt;&lt;span style=&quot;color:#000000; font-size:16px&quot;&gt;Burj Khalifa, Downtown Dubai, Dubai Emirate&lt;/span&gt;&lt;/p&gt;
&lt;p&gt;&lt;span style=&quot;color:#999999; font-size:12px&quot;&gt;A supertall skyscraper in Dubai, UAE, with a height of 828 meters, it is the tallest and most multi-story building in the world, as well as the tallest structure. The stepped shape of the building resembles a stalagmite.&lt;/span&gt;&lt;/p&gt;
&lt;hr /&gt;
&lt;p style=&quot;text-align:center&quot;&gt;&lt;a href=&quot;https://www.openstreetmap.org/way/446646206&quot; style=&quot;display: block; color: #000; font-size: 14px; text-align: center; text-decoration: none; background-color: #ffd633; padding: 5px 25px;&quot; target=&quot;_blank&quot;&gt;About the object&lt;/a&gt;&lt;/p&gt;
&lt;hr /&gt;
            ',
            'baloon_footer' => 'Burj Khalifa, Downtown Dubai, Dubai Emirate'
        );

        $demo_data['ru'] = array(
            'title'         => 'Бурдж-Халифа',
            'baloon_header' => 'Бурдж-Халифа, Даунтаун Дубай',
            'baloon_body'   => '
&lt;p&gt;&lt;span style=&quot;color:#999999; font-size:13px&quot;&gt;Достопримечательность&lt;/span&gt;&lt;/p&gt;
&lt;hr /&gt;
&lt;p&gt;&lt;a href=&quot;tel:+97148888888&quot; style=&quot;display: block; color: #000; font-size: 14px;&quot; target=&quot;_blank&quot;&gt;+971 4 8888888&lt;/a&gt;&lt;a href=&quot;https://www.burjkhalifa.ae/&quot; style=&quot;display: block; color: #000; font-size: 14px;&quot; target=&quot;_blank&quot;&gt;https://www.burjkhalifa.ae/&lt;/a&gt;&lt;/p&gt;
&lt;hr /&gt;
&lt;p&gt;&lt;span style=&quot;color:#000000; font-size:16px&quot;&gt;Бурдж-Халифа, Даунтаун Дубай, эмират Дубай&lt;/span&gt;&lt;/p&gt;
&lt;p&gt;&lt;span style=&quot;color:#999999; font-size:12px&quot;&gt;Cверхвысотный небоскрёб высотой 828 метров в Дубае (ОАЭ), самое высокое и самое многоэтажное здание в мире, а также самое высокое сооружение. Уступчатая форма здания напоминает сталагмит.&lt;/span&gt;&lt;/p&gt;
&lt;hr /&gt;
&lt;p style=&quot;text-align:center&quot;&gt;&lt;a href=&quot;https://www.openstreetmap.org/way/446646206&quot; style=&quot;display: block; color: #000; font-size: 14px; text-align: center; text-decoration: none; background-color: #ffd633; padding: 5px 25px;&quot; target=&quot;_blank&quot;&gt;Об объекте&lt;/a&gt;&lt;/p&gt;
&lt;hr /&gt;
            ',
            'baloon_footer' => 'Бурдж-Халифа, Даунтаун Дубай, эмират Дубай'
        );

        $demo_data['ua'] = array(
            'title'         => 'Бурдж-Халіфа',
            'baloon_header' => 'Бурдж-Халіфа, Даунтаун Дубай',
            'baloon_body'   => '
&lt;p&gt;&lt;span style=&quot;color:#999999; font-size:13px&quot;&gt;Визначна пам\'ятка&lt;/span&gt;&lt;/p&gt;
&lt;hr /&gt;
&lt;p&gt;&lt;a href=&quot;tel:+97148888888&quot; style=&quot;display: block; color: #000; font-size: 14px;&quot; target=&quot;_blank&quot;&gt;+971 4 8888888&lt;/a&gt;&lt;a href=&quot;https://www.burjkhalifa.ae/&quot; style=&quot;display: block; color: #000; font-size: 14px;&quot; target=&quot;_blank&quot;&gt;https://www.burjkhalifa.ae/&lt;/a&gt;&lt;/p&gt;
&lt;hr /&gt;
&lt;p&gt;&lt;span style=&quot;color:#000000; font-size:16px&quot;&gt;Бурдж-Халіфа, Даунтаун Дубай, емірат Дубай&lt;/span&gt;&lt;/p&gt;
&lt;p&gt;&lt;span style=&quot;color:#999999; font-size:12px&quot;&gt;Надвисокий хмарочос висотою 828 метрів у Дубаї (ОАЕ), найвища і багатоповерхова будівля у світі, а також найвища споруда. Поступкова форма будівлі нагадує сталагміт.&lt;/span&gt;&lt;/p&gt;
&lt;hr /&gt;
&lt;p style=&quot;text-align:center&quot;&gt;&lt;a href=&quot;https://www.openstreetmap.org/way/446646206&quot; style=&quot;display: block; color: #000; font-size: 14px; text-align: center; text-decoration: none; background-color: #ffd633; padding: 5px 25px;&quot; target=&quot;_blank&quot;&gt;Про об\'єкт&lt;/a&gt;&lt;/p&gt;
&lt;hr /&gt;
            ',
            'baloon_footer' => 'Бурдж-Халіфа, Даунтаун Дубай, емірат Дубай'
        );

        /* Demo array */

        $demo_array = array(
            'latitude'    => '25.197303',
            'longitude'   => '55.274247',
            'status'      => '1',
            'description' => array()
        );

        foreach ($languages as $language) {
            $check = false;

            foreach ($demo_data as $key => $data) {
                if (strpos($language['code'], $key) !== false) {
                    $demo_array['description'][$language['language_id']] = array(
                        'title'         => $data['title'],
                        'baloon_header' => $data['baloon_header'],
                        'baloon_body'   => $data['baloon_body'],
                        'baloon_footer' => $data['baloon_footer']
                    );

                    $check = true;
                    break;
                }
            }

            if (!$check) {
                $demo_array['description'][$language['language_id']] = array(
                    'title'         => $demo_data['en']['title'],
                    'baloon_header' => $demo_data['en']['baloon_header'],
                    'baloon_body'   => $demo_data['en']['baloon_body'],
                    'baloon_footer' => $demo_data['en']['baloon_footer']
                );
            }
        }

        return $demo_array;
    }

    /**
    * Install method.
    *
    * @return void
    */
    public function install(): void {
        $this->load->model('extension/dosmap_locations/module/dosmap_locations');

        // Add Demo array to DB.
        $demo_array = $this->demoData();
        $this->model_extension_dosmap_locations_module_dosmap_locations->installModule($demo_array);

        // Register Events.
        $this->registerEvents();
    }

    /**
    * Uninstall method.
    *
    * @return void
    */
    public function uninstall(): void {
        $this->load->model('setting/event');
        $this->load->model('extension/dosmap_locations/module/dosmap_locations');

        if (!empty($this->config->get('module_dosmap_locations_data_delete'))) {
            $this->model_extension_dosmap_locations_module_dosmap_locations->uninstallModule();

            // Delete directories.
            $dirPath = DIR_IMAGE . 'module-dosmap_locations/';
            $this->deleteDir($dirPath);
        } else {
            $this->model_extension_dosmap_locations_module_dosmap_locations->deleteDemo();
        }

        // Delete events.
        $this->model_setting_event->deleteEventByCode('dosmap_locations_1');
    }

    /**
    * Register Events.
    *
    * @return void
    */
    protected function registerEvents(): void {
        // Events array.
        $events = array();

        // Event #1.
        $events[] = array(
            'code'        => 'dosmap_locations_1',
            'description' => 'Event for «YMap Locations» module. Modification «localisation/language/addLanguage» model method.',
            'trigger'     => 'admin/model/localisation/language/addLanguage/after',
            'action'      => 'extension/dosmap_locations/event/dosmap_locations',
            'status'      => 1,
            'sort_order'  => 0,
        );

        // Loading event model.
        $this->load->model('setting/event');

        // Register Events in DB.
        if (version_compare(VERSION, '4.0.0.0', '>')) {
            foreach($events as $event){
                $this->model_setting_event->addEvent($event);
            }
        } else {
            foreach($events as $event){
                $this->model_setting_event->addEvent($event['code'], $event['description'], $event['trigger'], $event['action'], $event['status'], $event['sort_order'], );
            }
        }
    }
    
    /**
    * Delete directory.
    *
    * @param string $dirPath
    *
    * @return void
    */
    private function deleteDir(string $dirPath): void {
        if (is_dir($dirPath)) {
            $files = scandir($dirPath);

            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $filePath = $dirPath . '/' . $file;

                    if (is_dir($filePath)) {
                        $this->deleteDir($filePath);
                    } else {
                        unlink($filePath);
                    }
                }
            }

            rmdir($dirPath);
        }
    }
}