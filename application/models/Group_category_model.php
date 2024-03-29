<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group_category_model extends MY_Model
{
    protected $table = "group_category";
    protected $menu_list = array();

    function __construct() {
        parent::__construct( $this->table );
        parent::set_join_key( 'group_category_id' );
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
        $this->set_error("gagal");//('menu_delete_unsuccessful');
        return FALSE;
      }
      //foreign
      $this->db->trans_begin();

      $this->db->delete($this->table, $data_param );
      if ($this->db->trans_status() === FALSE)
      {
        $this->db->trans_rollback();

        $this->set_error("gagal");//('menu_delete_unsuccessful');
        return FALSE;
      }

      $this->db->trans_commit();

      $this->set_message("berhasil");//('menu_delete_successful');
      return TRUE;
  }

  	/**
	 * menu
	 *
	 * @param int|array|null $id = id_group_category
	 * @return static
	 * @author madukubah
	 */
	public function group_category( $id = NULL  )
  {
      if (isset($id))
      {
        $this->where($this->table.'.id', $id);
      }

      $this->limit(1);
      $this->order_by($this->table.'.id', 'desc');

      $this->group_categories(  );
      return $this;
  }
  /**
	 * menu
	 *
	 * @param int|array|null $id = id_group_category
	 * @return static
	 * @author madukubah
	 */
	public function get_by_group_id( $group_id )
  {
      $this->where($this->table.'.group_id', $group_id);

      $this->limit(1);
      $this->order_by($this->table.'.id', 'desc');

      $this->group_categories(  );

      return $this;
  }
  /**
   * group_category
   *
   *
   * @return static
   * @author madukubah
   */
  public function group_categories(  )
  {
    $this->select($this->table.'.*');
    $this->select("groups.description as group_name");
    $this->select("category.name as category_name");

    $this->join(
      "category",
      "category.id = ".$this->table.'.category_id',
      "inner"
    );
    $this->join(
      "groups",
      "groups.id = ".$this->table.'.group_id',
      "inner"
    );
    $this->order_by($this->table.'.id', 'desc');
    return $this->fetch_data();
  }
}
?>
