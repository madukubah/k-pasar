<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery_model extends MY_Model
{
  protected $table = "gallery";

  function __construct() {
      parent::__construct( $this->table );
      parent::set_join_key( 'gallery_id' );
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
    if( !$this->delete_foreign( $data_param, ["store_gallery_model"] ) )
    {
      $this->set_error("gagal");//('gallery_delete_unsuccessful');
      return FALSE;
    }
    //foreign
    $this->db->trans_begin();

    $this->db->delete($this->table, $data_param );
    if ($this->db->trans_status() === FALSE)
    {
      $this->db->trans_rollback();

      $this->set_error("gagal");//('gallery_delete_unsuccessful');
      return FALSE;
    }

    $this->db->trans_commit();

    $this->set_message("berhasil");//('gallery_delete_successful');
    return TRUE;
  }

    /**
   * gallery
   *
   * @param int|array|null $id = id_gallerys
   * @return static
   * @author madukubah
   */
  public function gallery( $id = NULL  )
  {
      if (isset($id))
      {
        $this->where($this->table.'.id', $id);
      }

      $this->limit(1);
      $this->order_by($this->table.'.id', 'desc');

      $this->galleries( 1 );

      return $this;
  }

  /**
   * gallerys
   *
   *
   * @return static
   * @author madukubah
   */
  public function galleries( $type = NULL ,$start = 0 , $limit = NULL )
  {
      $_type = [ "gallery", "sliders", "structures" , "iklan","gallery" ];

      if (isset( $limit ))
      {
        $this->limit( $limit );
      }

      if (isset( $type ))
      {
        $this->where( $this->table.'.type', $type );
      }
      $this->select( $this->table.'.*' );
      $this->select( "CONCAT( '".base_url("uploads/".$_type[ $type -1 ]."/")."' , {$this->table}.file ) as images" );
      $this->offset( $start );
      $this->order_by( $this->table.'.id', 'asc');
      return $this->fetch_data();
  }

  /**
   * gallerys
   *
   *
   * @return static
   * @author madukubah
   */
  public function galleries_by_store_id( $store_id, $type = NULL ,$start = 0 , $limit = NULL )
  {
      $_type = [ "gallery", "sliders", "structures" , "iklan","gallery" ];

      if (isset( $limit ))
      {
        $this->limit( $limit );
      }

      if (isset( $type ))
      {
        $this->where( $this->table.'.type', $type );
      }
      $this->select( $this->table.'.*' );
      $this->select( "CONCAT( '".base_url("uploads/".$_type[ $type -1 ]."/")."' , {$this->table}.file ) as images" );
      $this->join( 
        'store_gallery',
        'store_gallery.gallery_id = '.$this->table.'.id',
        "inner"
      );

      $this->where( 'store_gallery.store_id', $store_id );
      $this->offset( $start );
      $this->order_by( $this->table.'.id', 'asc');
      return $this->fetch_data();
  }

}
?>
