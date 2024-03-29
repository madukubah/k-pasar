<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog_model extends MY_Model
{
  protected $table = "blog";

  function __construct() {
      parent::__construct( $this->table );
      parent::set_join_key( 'blog_id' );
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
      $this->set_error("gagal");//('blog_delete_unsuccessful');
      return FALSE;
    }
    //foreign
    $this->db->trans_begin();

    $this->db->delete($this->table, $data_param );
    if ($this->db->trans_status() === FALSE)
    {
      $this->db->trans_rollback();

      $this->set_error("gagal");//('blog_delete_unsuccessful');
      return FALSE;
    }

    $this->db->trans_commit();

    $this->set_message("berhasil");//('blog_delete_successful');
    return TRUE;
  }

    /**
   * blog
   *
   * @param int|array|null $id = id_blogs
   * @return static
   * @author madukubah
   */
  public function blog( $id = NULL  )
  {
      if (isset($id))
      {
        $this->where($this->table.'.id', $id);
      }

      $this->limit(1);
      $this->order_by($this->table.'.id', 'desc');

      $this->blogs(  );

      return $this;
  }

  /**
   * blog
   *
   * @param int|array|null $id = id_blogs
   * @return static
   * @author madukubah
   */
  public function blog_by_file_name( $file_name  )
  {
      $this->like($this->table.'.file_content', $file_name);

      $this->limit(1);
      $this->order_by($this->table.'.id', 'desc');

      $this->blogs(  );

      return $this;
  }
  // /**
  //  * blogs
  //  *
  //  *
  //  * @return static
  //  * @author madukubah
  //  */
  // public function blogs(  )
  // {
      
  //     $this->order_by($this->table.'.id', 'asc');
  //     return $this->fetch_data();
  // }

  /**
   * blogs
   *
   *
   * @return static
   * @author madukubah
   */
  public function blogs( $start = 0 , $limit = NULL )
  {
      if (isset( $limit ))
      {
        $this->limit( $limit );
      }
      $this->select( $this->table.'.*' );
      $this->select( "CONCAT( '".base_url("uploads/blog/photo/")."' , {$this->table}.image ) as images" );
      $this->select( "CONCAT( users.first_name, ' ', users.last_name ) as author" );
      $this->select( "CONCAT( '".base_url("uploads/users_photo/")."' , users.image ) as author_image" );
      $this->select( "category.name as category_name" );
      $this->join( 
        "category" ,
        "category.id = blog.category_id" ,
        "inner"
      );

      $this->join( 
        "users" ,
        "users.id = blog.user_id" ,
        "inner"
      );
      $this->offset( $start );
      $this->order_by( $this->table.'.id', 'desc');
      return $this->fetch_data();
  }

  /**
   * blogs
   *
   *
   * @return static
   * @author madukubah
   */
  public function most_viewed( $start = 0 , $limit = NULL )
  {
      if (isset( $limit ))
      {
        $this->limit( $limit );
      }
      $this->select( $this->table.'.*' );
      $this->select( "CONCAT( '".base_url("uploads/blog/photo/")."' , {$this->table}.image ) as images" );
      $this->select( "CONCAT( users.first_name, ' ', users.last_name ) as author" );
      $this->select( "CONCAT( '".base_url("uploads/users_photo/")."' , users.image ) as author_image" );
      $this->select( "category.name as category_name" );
      $this->join( 
        "category" ,
        "category.id = blog.category_id" ,
        "inner"
      );

      $this->join( 
        "users" ,
        "users.id = blog.user_id" ,
        "inner"
      );
      $this->offset( $start );
      $this->order_by( $this->table.'.hit', 'desc');
      return $this->fetch_data();
  }

}
?>
