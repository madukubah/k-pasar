<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Store_model extends MY_Model
{
  protected $table = "store";

  function __construct() {
      parent::__construct( $this->table );
      parent::set_join_key( 'store_id' );
  }

  /**
   * create
   *
   * @param array  $data
   * @return static
   * @author madukubah
   */
  public function create( $data )
  {
      // Filter the data passed
      $data = $this->_filter_data($this->table, $data);

      $this->db->insert($this->table, $data);
      $id = $this->db->insert_id($this->table . '_id_seq');
    
      if( isset($id) )
      {
        $this->set_message("berhasil");
        return $id;
      }
      $this->set_error("gagal");
          return FALSE;
  }
  /**
   * update
   *
   * @param array  $data
   * @param array  $data_param
   * @return bool
   * @author madukubah
   */
  public function update( $data, $data_param  )
  {
    $this->db->trans_begin();
    $data = $this->_filter_data($this->table, $data);

    $this->db->update($this->table, $data, $data_param );
    if ($this->db->trans_status() === FALSE)
    {
      $this->db->trans_rollback();

      $this->set_error("gagal");
      return FALSE;
    }

    $this->db->trans_commit();

    $this->set_message("berhasil");
    return TRUE;
  }
  /**
   * delete
   *
   * @param array  $data_param
   * @return bool
   * @author madukubah
   */
  public function delete( $data_param  )
  {
    //foreign
    //delete_foreign( $data_param. $models[]  )
    if( !$this->delete_foreign( $data_param ) )
    {
      $this->set_error("gagal");//('store_delete_unsuccessful');
      return FALSE;
    }
    //foreign
    $this->db->trans_begin();

    $this->db->delete($this->table, $data_param );
    if ($this->db->trans_status() === FALSE)
    {
      $this->db->trans_rollback();

      $this->set_error("gagal");//('store_delete_unsuccessful');
      return FALSE;
    }

    $this->db->trans_commit();

    $this->set_message("berhasil");//('store_delete_successful');
    return TRUE;
  }

    /**
   * store
   *
   * @param int|array|null $id = id_stores
   * @return static
   * @author madukubah
   */
  public function store( $id = NULL  )
  {
      if (isset($id))
      {
        $this->where($this->table.'.id', $id);
      }

      $this->limit(1);
      $this->order_by($this->table.'.id', 'desc');

      $this->stores(  );

      return $this;
  }

  /**
   * store
   *
   * @param int|array|null $id = id_stores
   * @return static
   * @author madukubah
   */
  public function store_by_user_id( $user_id )
  {
      $this->where($this->table.'.user_id', $user_id);

      $this->limit(1);
      $this->order_by($this->table.'.id', 'desc');

      $this->stores( );

      return $this;
  }

  /**
   * stores
   *
   *
   * @return static
   * @author madukubah
   */
  public function stores( $start = 0 , $limit = NULL )
  {
      if (isset( $limit ))
      {
        $this->limit( $limit );
      }
      $this->select( $this->table.'.*' );
      $this->select( "CONCAT( '".base_url("uploads/store/")."' , {$this->table}.image ) as images" );
      $this->offset( $start );
      $this->order_by( $this->table.'.id', 'asc');
      return $this->fetch_data();
  }

}
?>
