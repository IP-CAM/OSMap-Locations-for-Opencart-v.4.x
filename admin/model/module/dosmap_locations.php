<?php
/**
 * Model Module D.OSMap Locations Class
 *
 * @version 1.0
 * 
 * @author D.art <d.art.reply@gmail.com>
 */

namespace Opencart\Admin\Model\Extension\DOSMapLocations\Module;
class DOSMapLocations extends \Opencart\System\Engine\Model {

    /**
    * Add Location.
    *
    * @param array $data
    *
    * @return int $location_id
    */
    public function addLocation(array $data, int $module_id = 0): int {
        if (isset($data['latitude'])) $lat = $data['latitude'];
        else  $lat = 0;

        if (isset($data['longitude'])) $long = $data['longitude'];
        else  $long = 0;

        $this->db->query("INSERT INTO " . DB_PREFIX . "dosmap_location SET module_id = '" . (int)$module_id . "', latitude = '" . $this->db->escape($lat) . "', longitude = '" . $this->db->escape($long) . "', status = '" . (int)$data['status'] . "', date_added = NOW(), date_modified = NOW()");

        $location_id = $this->db->getLastId();

		foreach ($data['description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "dosmap_location_description SET location_id = '" . (int)$location_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape(isset($value['title']) ? $value['title'] : '') . "', baloon_header = '" . $this->db->escape(isset($value['baloon_header']) ? $value['baloon_header'] : '') . "', baloon_body = '" . $this->db->escape(isset($value['baloon_body']) ? $value['baloon_body'] : '') . "', baloon_footer = '" . $this->db->escape(isset($value['baloon_footer']) ? $value['baloon_footer'] : '') . "'");
		}

        return $location_id;
    }

    /**
    * Edit Location.
    *
    * @param array $data
    *
    * @return void
    */
    public function editLocation(array $data): void {
        foreach ($data as $location_id => $location) {
            if (isset($location['latitude'])) $lat = $location['latitude'];
            else  $lat = 0;

            if (isset($location['longitude'])) $long = $location['longitude'];
            else  $long = 0;

            $this->db->query("UPDATE " . DB_PREFIX . "dosmap_location yl SET yl.module_id = '" . (int)$location['module_id'] . "', yl.latitude = '" . $this->db->escape($lat) . "', yl.longitude = '" . $this->db->escape($long) . "', yl.status = '" . (int)$location['status'] . "', yl.date_modified = NOW() WHERE yl.location_id = '" . (int)$location_id . "'");

            $this->db->query("DELETE FROM " . DB_PREFIX . "dosmap_location_description WHERE location_id = '" . (int)$location_id . "'");

            foreach ($location['description'] as $language_id => $value) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "dosmap_location_description SET location_id = '" . (int)$location_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape(isset($value['title']) ? $value['title'] : '') . "', baloon_header = '" . $this->db->escape(isset($value['baloon_header']) ? $value['baloon_header'] : '') . "', baloon_body = '" . $this->db->escape(isset($value['baloon_body']) ? $value['baloon_body'] : '') . "', baloon_footer = '" . $this->db->escape(isset($value['baloon_footer']) ? $value['baloon_footer'] : '') . "'");
            }
        }
    }

    /**
    * Delete Location.
    *
    * @param array $data
    *
    * @return void
    */
    public function deleteLocation(array $data): void {
		foreach ($data as $location_id => $location) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "dosmap_location WHERE location_id = '" . (int)$location_id . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "dosmap_location_description WHERE location_id = '" . (int)$location_id . "'");
		}
    }

    /**
    * Delete Demo data.
    *
    * @return void
    */
    public function deleteDemo(): void {
        $sql = "SELECT yl.location_id FROM " . DB_PREFIX . "dosmap_location yl WHERE yl.module_id = '0'";

        $query = $this->db->query($sql);

        foreach ($query->rows as $row) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "dosmap_location WHERE location_id = '" . (int)$row['location_id'] . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "dosmap_location_description WHERE location_id = '" . (int)$row['location_id'] . "'");
        }
    }

    /**
    * Get Locations.
    *
    * @param array $data
    *
    * @return array $query->rows
    */
    public function getLocations(array $data = array()): array {
        if (!isset($data['module_id'])) return array();

        $sql = "SELECT * FROM " . DB_PREFIX . "dosmap_location yl LEFT JOIN " . DB_PREFIX . "dosmap_location_description yd ON (yl.location_id = yd.location_id) WHERE yl.module_id = '" . (int)$data['module_id'] . "'";

        if (!empty($data['language_id'])) {
            $sql .= " AND yd.language_id = '" . (int)$data['language_id'] . "'";
        }

        if (!empty($data['filter_title'])) {
            $sql .= " AND yd.title LIKE '%" . $this->db->escape($data['filter_title']) . "%'";
        }

        if (!empty($data['filter_baloon_header'])) {
            $sql .= " AND yd.baloon_header LIKE '%" . $this->db->escape($data['filter_baloon_header']) . "%'";
        }

        if (!empty($data['filter_baloon_footer'])) {
            $sql .= " AND yd.baloon_footer LIKE '%" . $this->db->escape($data['filter_baloon_footer']) . "%'";
        }

        if (!empty($data['filter_latitude'])) {
            $sql .= " AND yl.latitude LIKE '" . $this->db->escape($data['filter_latitude']) . "%'";
        }

        if (!empty($data['filter_longitude'])) {
            $sql .= " AND yl.longitude LIKE '" . $this->db->escape($data['filter_longitude']) . "%'";
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $sql .= " AND yl.status = '" . (int)$data['filter_status'] . "'";
        }

        $sql .= " GROUP BY yl.location_id";

        $sort_data = array(
            'location_id',
            'title',
            'baloon_header',
            'baloon_footer',
            'latitude',
            'longitude',
            'status'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            switch ($data['sort']) {
                case 'location_id':
                case 'latitude':
                case 'longitude':
                case 'status':
                    $sql .= " ORDER BY yl." . $data['sort'];
                    break;
                default:
                    $sql .= " ORDER BY yd." . $data['sort'];
                    break;
            }
        } else {
            $sql .= " ORDER BY yd.title";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 24;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

		return $query->rows;
    }

    /**
    * Get Location Descriptions.
    *
    * @param array $data
    *
    * @return array $location_description_data
    */
    public function getLocationDescriptions(array $data): array {
        $location_description_data = array();

        foreach ($data as $location_id => $location) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "dosmap_location_description yd WHERE yd.location_id = '" . (int)$location_id . "'");

            foreach ($query->rows as $result) {
                $location_description_data[$location_id][$result['language_id']] = array(
                    'title'         => $result['title'],
                    'baloon_header' => $result['baloon_header'],
                    'baloon_body'   => $result['baloon_body'],
                    'baloon_footer' => $result['baloon_footer']
                );
            }
        }

        return $location_description_data;
    }

    /**
    * Copy current language to new language.
    *
    * @param int $language_id_add
    *
    * @return void
    */
    public function copyCurrentLanguageLocation(int $language_id_add): void {
        $table_name_yl = DB_PREFIX . 'dosmap_location';
        $table_name_yd = DB_PREFIX . 'dosmap_location_description';

        if ($this->tableExists($table_name_yl) && $this->tableExists($table_name_yd)) {
            $language_id_current = (int)$this->config->get('config_language_id');

            $query = $this->db->query("SELECT * FROM " . $table_name_yl . " yl LEFT JOIN " . $table_name_yd . " yd ON (yl.location_id = yd.location_id) WHERE yd.language_id = '" . (int)$language_id_current . "'");

            foreach ($query->rows as $location) {
                $this->db->query("INSERT INTO " . $table_name_yd . " SET location_id = '" . (int)$location['location_id'] . "', language_id = '" . (int)$language_id_add . "', title = '" . $this->db->escape(isset($location['title']) ? $location['title'] : '') . "', baloon_header = '" . $this->db->escape(isset($location['baloon_header']) ? $location['baloon_header'] : '') . "', baloon_body = '" . $this->db->escape(isset($location['baloon_body']) ? $location['baloon_body'] : '') . "', baloon_footer = '" . $this->db->escape(isset($location['baloon_footer']) ? $location['baloon_footer'] : '') . "'");
            }
        }
    }

    /**
    * Get total of Locations.
    *
    * @param array $data
    *
    * @return int $query->row['total']
    */
    public function getTotalLocations(array $data = array()): int {
        $sql = "SELECT COUNT(DISTINCT yl.location_id) AS total FROM " . DB_PREFIX . "dosmap_location yl LEFT JOIN " . DB_PREFIX . "dosmap_location_description yd ON (yl.location_id = yd.location_id) WHERE yl.module_id = '" . (int)$data['module_id'] . "'";

        if (!empty($data['language_id'])) {
            $sql .= " AND yd.language_id = '" . (int)$data['language_id'] . "'";
        }

        if (!empty($data['filter_title'])) {
            $sql .= " AND yd.title LIKE '%" . $this->db->escape($data['filter_title']) . "%'";
        }

        if (!empty($data['filter_baloon_header'])) {
            $sql .= " AND yd.baloon_header LIKE '%" . $this->db->escape($data['filter_baloon_header']) . "%'";
        }

        if (!empty($data['filter_baloon_footer'])) {
            $sql .= " AND yd.baloon_footer LIKE '%" . $this->db->escape($data['filter_baloon_footer']) . "%'";
        }

        if (!empty($data['filter_latitude'])) {
            $sql .= " AND yl.latitude LIKE '" . $this->db->escape($data['filter_latitude']) . "%'";
        }

        if (!empty($data['filter_longitude'])) {
            $sql .= " AND yl.longitude LIKE '" . $this->db->escape($data['filter_longitude']) . "%'";
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $sql .= " AND yl.status = '" . (int)$data['filter_status'] . "'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    /**
    * Get Module data.
    *
    * @param int $module_id
    *
    * @return array $module_info
    */
    public function getModule(int $module_id): array {
        $module_info = array();

        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` WHERE `module_id` = '" . (int)$module_id . "'");

        if ($query->row) {
            $module_info = array(
                'module_id' => $query->row['module_id'],
                'code'      => $query->row['code'],
                'setting'   => json_decode($query->row['setting'], true)
            );
        }

        return $module_info;
    }

    /**
     * A list of existing tables.
     * 
     * @param string $tables_name
     * 
     * @return bool $exists
     */
    private function tableExists(string $tables_name): bool {
        return $this->db->query("SHOW TABLES LIKE '" . $tables_name . "'")->num_rows > 0;
    }

    /**
    * Install Module data.
    *
    * @param array $data
    *
    * @return void
    */
    public function installModule(array $data): void {
        $table_name_yl = DB_PREFIX . 'dosmap_location';
        $table_name_yd = DB_PREFIX . 'dosmap_location_description';

        if (!$this->tableExists($table_name_yl)) {
            // Table structure.
            $query = $this->db->query("
                CREATE TABLE `" . $table_name_yl . "` (
                    `location_id` int(11) NOT NULL,
                    `module_id` int(11) NOT NULL DEFAULT 0,
                    `latitude` decimal(8,6) NOT NULL DEFAULT 0.000000,
                    `longitude` decimal(9,6) NOT NULL DEFAULT 0.000000,
                    `status` tinyint(1) NOT NULL DEFAULT 0,
                    `date_added` datetime NOT NULL,
                    `date_modified` datetime NOT NULL
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
            ");

            // Table index.
            $query = $this->db->query("ALTER TABLE `" . $table_name_yl . "` ADD PRIMARY KEY (`location_id`);");

            // AUTO_INCREMENT for table.
            $query = $this->db->query("ALTER TABLE `" . $table_name_yl . "` MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;");
        }

        if (!$this->tableExists($table_name_yd)) {
            // Table structure.
            $query = $this->db->query("
                CREATE TABLE `" . $table_name_yd . "` (
                    `location_id` int(11) NOT NULL,
                    `language_id` int(11) NOT NULL,
                    `title` varchar(255) NOT NULL,
                    `baloon_header` varchar(255) NOT NULL,
                    `baloon_body` text NOT NULL,
                    `baloon_footer` varchar(255) NOT NULL
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
            ");

            // Table index.
            $query = $this->db->query("ALTER TABLE `" . $table_name_yd . "` ADD PRIMARY KEY (`location_id`,`language_id`), ADD KEY `title` (`title`);");
        }

        // Add Demo data.
        $this->addLocation($data);
    }

    /**
    * Uninstall Module data.
    *
    * @return void
    */
    public function uninstallModule(): void {
        // Delete tables.
        $this->db->query("DROP TABLE `" . DB_PREFIX . "dosmap_location`;");
        $this->db->query("DROP TABLE `" . DB_PREFIX . "dosmap_location_description`;");
    }
}