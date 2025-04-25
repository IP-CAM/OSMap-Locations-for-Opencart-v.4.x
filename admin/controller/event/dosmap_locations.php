<?php
/**
 * Controller Module D.OSMap Locations Class
 *
 * @version 1.0
 * 
 * @author D.art <d.art.reply@gmail.com>
 */

namespace Opencart\Admin\Controller\Extension\DOSMapLocations\Event;
class DOSMapLocations extends \Opencart\System\Engine\Controller {

    /**
     * Index.
     * Event trigger: admin/model/localisation/language/addLanguage/after
     *
     * @param  mixed $route
     * @param  mixed $data
     * @param  mixed $output
     *
     * @return void
     */
    public function index(string &$route = '', array &$data = array(), mixed &$output = ''): void {
        foreach ($data as $data_language) {
            if (isset($data_language['code'])) {
                $this->load->model('localisation/language');
                $this->load->model('extension/dosmap_locations/module/dosmap_locations');

                /* Languages */

                $languages = $this->model_localisation_language->getLanguages();

                /* Language ID */

                $language_id = 0;

                foreach ($languages as $language) {
                    if ($language['code'] == $data_language['code']) {
                        $language_id = $language['language_id'];
                        break;
                    }
                }

                /* Copy Language data */

                if ($language_id) {
                    $this->model_extension_dosmap_locations_module_dosmap_locations->copyCurrentLanguageLocation($language_id);
                }
            }
        }
    }
}