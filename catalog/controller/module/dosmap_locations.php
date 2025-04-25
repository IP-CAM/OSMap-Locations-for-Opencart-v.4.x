<?php
/**
 * Controller Module D.OSMap Locations Class
 *
 * @version 1.0
 * 
 * @author D.art <d.art.reply@gmail.com>
 */

namespace Opencart\Catalog\Controller\Extension\DOSMapLocations\Module;
class DOSMapLocations extends \Opencart\System\Engine\Controller {
	public function index(array $setting): string {
        if ($setting['status']) {
            static $module = 0;

            $this->load->language('extension/dosmap_locations/module/dosmap_locations');

            $this->load->model('localisation/language');
            $this->load->model('extension/dosmap_locations/module/dosmap_locations');

            $x = (version_compare(VERSION, '4.0.2.0', '>=')) ? '.' : '|';

            if (isset($this->request->get['dosmap_sort'])) {
                $sort = $this->request->get['dosmap_sort'];
            } else {
                $sort = 'title';
            }

            if (isset($this->request->get['dosmap_order'])) {
                $order = $this->request->get['dosmap_order'];
            } else {
                $order = 'ASC';
            }

            if (isset($this->request->get['dosmap_page'])) {
                $page = (int)$this->request->get['dosmap_page'];
            } else {
                $page = 1;
            }

            $limit = $setting['limit_site'];

            $module_id = (int)$setting['module_id'];
            $language_id = (int)$this->config->get('config_language_id');

            $data['ver_api'] = $setting['ver_api'] ? $setting['ver_api'] : 'leaflet_194';
            $data['dosmap_init_latitude'] = $setting['mapinit_latitude'] ? $setting['mapinit_latitude'] : 25.197301;
            $data['dosmap_init_longitude'] = $setting['mapinit_longitude'] ? $setting['mapinit_longitude'] : 55.274242;
            $data['dosmap_init_zoom'] = $setting['mapinit_zoom'] ? $setting['mapinit_zoom'] : 11;
            $data['dosmap_pan_zoom'] = $setting['mappan_zoom'] ? $setting['mappan_zoom'] : 18;
            $data['dosmap_clusterization'] = $setting['clusterization'] ? true : false;
            $data['dosmap_baloon_info'] = $setting['baloon_info'] ? true : false;

            $data['heading_title'] = html_entity_decode($setting['module_description'][$language_id]['title'], ENT_QUOTES, 'UTF-8');
            $data['description'] = html_entity_decode($setting['module_description'][$language_id]['description'], ENT_QUOTES, 'UTF-8');
            $data['attr_ID'] = $setting['attr_ID'];

            /* Map Marker URLs */

            // Icon Points, custom-default
            $data['url_icon_point'] = HTTP_SERVER . 'extension/dosmap_locations/catalog/view/javascript/module-dosmap_locations/icons/icon-point-default.png';

            // Icon Clusters, custom-default
            $data['url_icon_cluster'] = HTTP_SERVER . 'extension/dosmap_locations/catalog/view/javascript/module-dosmap_locations/icons/icon-cluster-default.svg';

            /* GeoJSON-file */

            // Template-name of JSON-file:
            // $fileName = 'points--$module_id--$language_id--$timestamp.geojson';

            // Path to JSON-files.
            $jsonPath = DIR_IMAGE . 'module-dosmap_locations/json/';

            // Check directory of JSON-files.
            if (!is_dir($jsonPath)) return '';

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

            // Get maximum Timestamp from new array.
            $max_timestamp = 0;
            if (isset($jsonFilesArray[$module_id][$language_id])) {
                $max_timestamp = $jsonFilesArray[$module_id][$language_id][0];
                foreach ($jsonFilesArray[$module_id][$language_id] as $value_timestamp) {
                    if ($value_timestamp > $max_timestamp) {
                        $max_timestamp = $value_timestamp;
                    }
                }
            } else {
                return '';
            }

            // Points. JSON-file URL.
            $data['url_dosmap_points'] = HTTP_SERVER . 'image/module-dosmap_locations/json/points--' . $module_id . '--' . $language_id . '--' . $max_timestamp . '.geojson';

            /* Languages */

            $languages = $this->model_localisation_language->getLanguages();

            /* Map Language */

            $language_map = $setting['map_language'] ? $setting['map_language'] : 'ru_RU';
            $language_map_custom = $setting['map_language_custom'] ? $setting['map_language_custom'] : 'ru_RU';

            $language_code_map = 'ru_RU';

            switch ($language_map) {
                case '--':
                    $language_code_map = $language_map_custom;
                    break;
                case '##':
                    foreach ($languages as $language) {
                        if ($language['language_id'] == $language_id) {
                            $language_code = explode('_', str_replace('-', '_', $language['code']));
                            $language_code_map = $language_code[0] . (isset($language_code[1]) ? '_' . strtoupper($language_code[1]) : '');
                            break;
                        }
                    }
                    break;
                default:
                    $language_code_map = $language_map;
                    break;
            }

            /* Map Styles/Scripts */

            switch ($data['ver_api']) {
                case 'leaflet_194':
                    // Leaflet API.
                    $this->document->addStyle(HTTP_SERVER . 'extension/dosmap_locations/catalog/view/javascript/module-dosmap_locations/api/leaflet-1.9.4/leaflet.css');
                    $this->document->addScript(HTTP_SERVER . 'extension/dosmap_locations/catalog/view/javascript/module-dosmap_locations/api/leaflet-1.9.4/leaflet.js');

                    // Cluster Plugin for Leaflet API.
                    if ($data['dosmap_clusterization']) {
                        $this->document->addStyle(HTTP_SERVER . 'extension/dosmap_locations/catalog/view/javascript/module-dosmap_locations/api/leaflet-1.9.4/plugin/markercluster-1.4.1/MarkerCluster.Custom.css');
                        $this->document->addScript(HTTP_SERVER . 'extension/dosmap_locations/catalog/view/javascript/module-dosmap_locations/api/leaflet-1.9.4/plugin/markercluster-1.4.1/leaflet.markercluster.custom.js');
                    }
                    break;
                default:
                    break;
            }

            /* Module Styles/Scripts */

            $this->document->addStyle(HTTP_SERVER . 'extension/dosmap_locations/catalog/view/javascript/module-dosmap_locations/dosmap_locations.css');
            $this->document->addScript(HTTP_SERVER . 'extension/dosmap_locations/catalog/view/javascript/module-dosmap_locations/dosmap_locations.js');

            /* Filter data */

            $filter_data = array(
                'language_id' => $language_id,
                'module_id'   => $module_id,
                'status'      => '1',
                'sort'        => $sort,
                'order'       => $order,
                'start'       => ($page - 1) * $limit,
                'limit'       => $limit
            );

            /* Locations */

            $data['locations'] = array();

            $results = $this->model_extension_dosmap_locations_module_dosmap_locations->getLocations($filter_data);

            foreach ($results as $result) {
                $data['locations'][] = array(
                    'location_id'   => $result['location_id'],
                    'title'         => $result['title'],
                    'baloon_header' => $result['baloon_header'],
                    'baloon_body'   => $result['baloon_body'],
                    'baloon_footer' => $result['baloon_footer'],
                    'latitude'      => $result['latitude'],
                    'longitude'     => $result['longitude'],
                    'status'        => $result['status']
                );
            }

            if (empty($data['locations'])) {
                return '';
            }

            /* Totals */

            $location_total = $this->model_extension_dosmap_locations_module_dosmap_locations->getTotalLocations($filter_data);

            /* Pagination */

            $url = '';

            $queries = array();
            parse_str($_SERVER['QUERY_STRING'], $queries);

            unset($queries['route']);
            unset($queries['_route_']);
            unset($queries['dosmap_page']);
            unset($queries['dosmap_order']);

            if (isset($this->request->get['route'])) {
                $route = $this->request->get['route'];
            } else {
                $route = 'common/home';
            }

            switch ($route) {
                // Information Page.
                case 'information/information':
                    if (isset($this->request->get['information_id'])) {
                        $url .= '&information_id=' . $this->request->get['information_id'];
                    }

                    break;

                // Product Pages.
                case 'product/product':
                case 'product/manufacturer/info':
                case 'product/category':
                    if (isset($this->request->get['path'])) {
                        $url .= '&path=' . $this->request->get['path'];
                    }

                    if (isset($this->request->get['filter'])) {
                        $url .= '&filter=' . $this->request->get['filter'];
                        unset($queries['filter']);
                    }
                case 'product/search':
                    if (isset($this->request->get['category_id'])) {
                        $url .= '&category_id=' . $this->request->get['category_id'];
                        unset($queries['category_id']);
                    }

                    if (isset($this->request->get['sub_category'])) {
                        $url .= '&sub_category=' . $this->request->get['sub_category'];
                        unset($queries['sub_category']);
                    }

                    if (isset($this->request->get['search'])) {
                        $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
                        unset($queries['search']);
                    }

                    if (isset($this->request->get['tag'])) {
                        $url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
                        unset($queries['tag']);
                    }

                    if (isset($this->request->get['description'])) {
                        $url .= '&description=' . $this->request->get['description'];
                        unset($queries['description']);
                    }
                case 'product/special':
                    if (isset($this->request->get['sort'])) {
                        $url .= '&sort=' . $this->request->get['sort'];
                        unset($queries['sort']);
                    }

                    if (isset($this->request->get['order'])) {
                        $url .= '&order=' . $this->request->get['order'];
                        unset($queries['order']);
                    }

                    if (isset($this->request->get['limit'])) {
                        $url .= '&limit=' . $this->request->get['limit'];
                        unset($queries['limit']);
                    }

                    // For Manufacturer Page.
                    if (isset($this->request->get['manufacturer_id'])) {
                        $url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
                    }

                    // For Product Page.
                    if (isset($this->request->get['product_id'])) {
                        $url .= '&product_id=' . (int)$this->request->get['product_id'];
                    }

                    break;

                // ocStore Blog Pages.
                case 'blog/category':
                    if (isset($this->request->get['blog_category_id'])) {
                        $url .= '&blog_category_id=' . $this->request->get['blog_category_id'];
                    }
                case 'blog/latest':
                    if (isset($this->request->get['sort'])) {
                        $url .= '&sort=' . $this->request->get['sort'];
                        unset($queries['sort']);
                    }

                    if (isset($this->request->get['order'])) {
                        $url .= '&order=' . $this->request->get['order'];
                        unset($queries['order']);
                    }

                    if (isset($this->request->get['limit'])) {
                        $url .= '&limit=' . $this->request->get['limit'];
                        unset($queries['limit']);
                    }

                    break;
                default:
                    break;
            }

            foreach ($queries as $key => $value) {
                $url .= '&' . $key . '=' . $value;
            }

            if (isset($this->request->get['dosmap_order'])) {
                $url .= '&dosmap_order=' . $this->request->get['dosmap_order'];
            }

            $data['pagination'] = $this->load->controller('common/pagination', [
                'total' => $location_total,
                'page'  => $page,
                'limit' => $limit,
                'url'   => $this->url->link($route, $url . '&dosmap_page={page}')
            ]);

            $data['pagination'] = str_replace(array('&dosmap_page={page}', '&amp;dosmap_page={page}', '?dosmap_page={page}'), '', $data['pagination']);

            $data['results'] = sprintf($this->language->get('text_pagination'), ($location_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($location_total - $limit)) ? $location_total : ((($page - 1) * $limit) + $limit), $location_total, ceil($location_total / $limit));

            /* Output */

            $data['module'] = $module++;

            return $this->load->view('extension/dosmap_locations/module/dosmap_locations', $data);
        } else {
            return '';
        }
	}
}