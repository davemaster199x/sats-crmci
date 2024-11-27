<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Corded_window_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = "corded_window";
        $this->columns = ["job_id", "covering", "ftllt1_6m", "tag_present", "clip_rfc", "clip_present", "loop_lt220m", "seventy_n", "cw_image", "location", "num_of_windows"];
    }

    public function get($params = [], $single = false, $num_rows = false)
    {
        $this->db->flush_cache();
        if ($num_rows) {
            $this->db->select("COUNT({$this->table}.corded_window_id) as totalRecord");
        } elseif (
            isset($params["select_column"]) &&
            !empty($params["select_column"])
        ) {
            $this->db->select($params["select_column"]);
        } else {
            $this->db->select("*");
        }

        $this->db->from($this->table);

        # joins 
        if (
            isset($params['custom_joins']) &&
            !empty($params['custom_joins'])
        ) {
            foreach ($params['custom_joins'] as $custom_joins) {
                $this->db->join($custom_joins['join_table'], $custom_joins['join_on'], $custom_joins['join_type']);
            }
        }

        # filters

        if (
            isset($params['conditions']) &&
            !empty($params['conditions'])
        ) {
            if (!is_array($params['conditions'])) {
                $params['conditions'] = [$params['conditions']];
            }

            foreach ($params['conditions'] as $condition) {
                if ($condition["type"] == "raw") {
                    $this->db->where($condition["column"], false);
                } elseif (
                    !empty(@$condition["column"]) &&
                    !empty(@$condition["value"])
                ) {
                    $this->db->{$condition["type"]}($condition["column"], $condition["value"]);
                }
            }
        }

        if (!$num_rows) {
            if (isset($params['limit']) && isset($params['offset'])) {
                $this->db->limit($params['limit'], $params['offset']);
            } elseif (isset($params['limit']) && !empty($params['limit'])) {
                $this->db->limit($params['limit']);
            } else {
                //$this->db->limit(10);
            }
        }

        if (
            isset($params['orderby']) &&
            !empty($params['orderby'])
        ) {
            $this->db->order_by($params['orderby'], (isset($params['orderstate']) && !empty($params['orderstate']) ? $params['orderstate'] : 'DESC'));
        } else {
            $this->db->order_by("{$this->table}.corded_window_id", 'DESC');
        }

        $query = $this->db->get();

        if (
            isset($params['debug']) &&
            $params['debug']
        ) {
            echo $this->db->last_query();
        }

        if ($num_rows) {
            $row = $query->row();
            return (isset($row->totalRecord) && !empty($row->totalRecord) ? $row->totalRecord : "0");
        }

        if ($single) {
            return $query->row();
        } elseif (
            isset($params['corded_window_id']) &&
            !empty($params['corded_window_id']) &&
            !is_array($params['corded_window_id'])
        ) {
            return $query->row();
        }
        return $query->result();
    }

    public function set_data($params, $id = 0)
    {

        if (empty($params)) {
            return false;
        }

        $model_data = array();

        
        foreach ($this->columns as $value) {
            if (isset($params[$value])) {
                $model_data[$value] = $params[$value];
            }
        }

        if (empty($model_data)) {
            return false;
        }

        /*
        if (
            isset($params["corded_window_id"]) &&
            !empty($params["corded_window_id"])
        ) {
            $id = $params["corded_window_id"];
            unset($params["corded_window_id"]);
        }
        */
        
        $this->db->flush_cache();
        $this->db->trans_begin();

        if (!empty($id)) {
            $this->db->where('corded_window_id', $id);
            $this->db->update($this->table, $model_data);
        } else {
            $this->db->insert($this->table, $model_data);
            $id = $this->db->insert_id();
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return $id;
    }

    public function delete($params)
    {

        $this->db->flush_cache();
        $this->db->trans_begin();

        # filters

        if (
            isset($params['conditions']) &&
            !empty($params['conditions'])
        ) {
            if (!is_array($params['conditions'])) {
                $params['conditions'] = [$params['conditions']];
            }

            foreach ($params['conditions'] as $condition) {
                if ($condition["type"] == "raw") {
                    $this->db->where($condition["column"], false);
                } elseif (
                    !empty(@$condition["column"]) &&
                    !empty(@$condition["value"])
                ) {
                    $this->db->{$condition["type"]}($condition["column"], $condition["value"]);
                }
            }
        } else {
            return false;
        }

        $this->db->delete($this->table);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }
}
