<?php

namespace FewMailSmtp;

class Db
{
    private $db;

    private $table;

    private static $instance;

    public static $phpmailer_error;

    public static $id;

    public static function create()
    {
        if (!self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    private function __construct()
    {
        global $wpdb;

        $this->db = $wpdb;
        $this->table = $wpdb->prefix . 'fewmail_smtp_logs';
    }

    public function insert($data)
    {
        array_walk($data, function (&$value, $key) {
            if (is_array($value)) {
                $value = maybe_serialize($value);
            }
        });

        $result_set = $this->db->insert(
            $this->table,
            $data,
            array_fill(0, count($data), '%s')
        );

        if (!$result_set) {
            error_log('FewMail SMTP Log insert error: ' . $this->db->last_error);

            return false;
        }

        return $this->db->insert_id;
    }

    public function update($data, $where = [])
    {
        array_walk($data, function (&$value, $key) {
            if (is_array($value)) {
                $value = maybe_serialize($value);
            }
        });

        $this->db->update(
            $this->table,
            $data,
            $where,
            array_fill(0, count($data), '%s'),
            array('%d')
        );
    }

    public function get()
    {
        $columns = ['id', 'to', 'timestamp', 'subject', 'error'];
        $search = '';

        if (!empty($_GET['search']['value'])) {
            $search = sanitize_text_field($_GET['search']['value']);
            $search = '%' . $this->db->esc_like($search) . '%';
        }

        $limit = isset($_GET['length']) ? absint($_GET['length']) : 10;
        $offset = isset($_GET['start']) ? absint($_GET['start']) : 0;

        $orderBy = 'timestamp';
        $order = 'DESC';

        if (!empty($_GET['order'][0])) {
            $colNum = absint($_GET['order'][0]['column']);
            $orderBy = $columns[$colNum];
            $order = (!empty($_GET['order'][0]['dir']) && $_GET['order'][0]['dir'] == 'desc') ? 'DESC' : 'ASC';
        }

        if (!empty($search)) {
            $sql = $this->db->prepare(
                "SELECT * FROM %i WHERE `id` = %s OR `to` LIKE %s OR `subject` LIKE %s OR `error` LIKE %s ORDER BY %i %1s LIMIT %d, %d;",
                [$this->table, $search, $search, $search, $search, $orderBy, $order, $offset, $limit]
            );

            error_log($sql);

            return $this->db->get_results($sql, ARRAY_A);
        } else {
            $sql = $this->db->prepare(
                "SELECT * FROM %i ORDER BY %i %1s LIMIT %d, %d;",
                [$this->table, $orderBy, $order, $offset, $limit]
            );

            error_log($sql);

            return $this->db->get_results($sql, ARRAY_A);
        }
    }

    public function records_count()
    {
        $sql = $this->db->prepare("SELECT COUNT(*) FROM %i;", [$this->table]);
        return $this->db->get_var($sql);
    }
}
