<?php
/**
 * Model Module D.OSMap Locations Class
 *
 * @version 1.0
 * 
 * @author D.art <d.art.reply@gmail.com>
 */

namespace Opencart\Catalog\Model\Extension\DOSMapLocations\Module;
class DOSMapLocations extends \Opencart\System\Engine\Model {

    /**
    * Get Locations.
    *
    * @param array $data
    *
    * @return array $query->rows
    */
    public function getLocations(array $data = array()): array {
        if (!isset($data['module_id'])) return array();

        $sql = "SELECT * FROM " . DB_PREFIX . "dosmap_location yl LEFT JOIN " . DB_PREFIX . "dosmap_location_description yd ON (yl.location_id = yd.location_id) WHERE yl.module_id = '" . (int)$data['module_id'] . "' AND yd.language_id = '" . (int)$data['language_id'] . "'";

        if (isset($data['status']) && $data['status'] !== '') {
            $sql .= " AND yl.status = '" . (int)$data['status'] . "'";
        }

        $sql .= " GROUP BY yl.location_id";

        $sort_data = array(
            'title',
            'baloon_header',
            'baloon_footer',
            'latitude',
            'longitude',
            'status'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            switch ($data['sort']) {
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
    * Get total of Locations.
    *
    * @param array $data
    *
    * @return int $query->row['total']
    */
    public function getTotalLocations(array $data = array()): int {
        $sql = "SELECT COUNT(DISTINCT yl.location_id) AS total FROM " . DB_PREFIX . "dosmap_location yl LEFT JOIN " . DB_PREFIX . "dosmap_location_description yd ON (yl.location_id = yd.location_id) WHERE yl.module_id = '" . (int)$data['module_id'] . "' AND yd.language_id = '" . (int)$data['language_id'] . "'";

        if (isset($data['status']) && $data['status'] !== '') {
            $sql .= " AND yl.status = '" . (int)$data['status'] . "'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }
}