<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud extends CI_Model {

    function insert($data,$table){
        $query = $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    function insert_batch($data,$table){
        $query = $this->db->insert_batch($table, $data);
        return $this->db->insert_id();
    }

    function delete($where,$table){
        $this->db
            ->where($where)
            ->delete($table);
        return $this->db->affected_rows();
    }

    function update($where,$data,$table){
        $this->db
            ->where($where)
            ->update($table,$data);
        return $this->db->affected_rows();
    }

    function update_batch($key,$data,$table){
        $this->db->update_batch($table, $data, $key);
        return $this->db->affected_rows();
    }

    function get($table){
        return $this->db->get($table);
    }

    function get_where($where, $table){
        return $this->db->get_where($table, $where);
    }
    function get_where_limit($limit,$where, $table){
        $this->db->limit($limit);
        return $this->db->get_where($table, $where);
    }

    function count($table, $where=null, $isStrict=TRUE){
        if(!is_null($where)){
            if($isStrict==TRUE){
                $this->db->where($where);
            }
            else{
                $this->db->where($where, '', $isStrict);
            }
        }
        $this->db->from($table);
        return $this->db->count_all_results();
    }
}