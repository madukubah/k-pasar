  
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h5 class="m-0 text-dark"><?php echo $block_header ?></h5>
            </div>
        </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
        <div class="row">
            <div class="col-12">
            <div class="card">
                <div class="card-header">
                <div class="col-12">
                    <?php
                    echo $alert;
                    ?>
                </div>
                <div class="row">
                    <div class="col-6">
                    <h5>
                        <?php echo strtoupper($header) ?>
                        <p class="text-secondary"><small><?php echo $sub_header ?></small></p>
                    </h5>
                    </div>
                    <div class="col-6">
                    <div class="row">
                        <div class="col-2"></div>
                        <div class="col-10">
                        <div class="float-right">
                            <?php echo (isset($header_button)) ? $header_button : '';  ?>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
                <div class="card-body">
                    <!-- HIRARKI -->
                    <div style="  " >
                        <div class="tree" >
                            <ol>
                                <?php
                                    function print_tree( $datas )
                                    {
                                        foreach( $datas as $data )
                                        {       
                                                echo  '<li>';
                                                        echo '<a href="#">'.$data->name.'</a>';
                                                    ?>
                                                    
                                                    <button class="btn btn-white btn-info btn-bold btn-xs" data-toggle="modal" data-target="#add_menu_<?php echo $data->id ?>">
                                                        + 
                                                    </button>
                                                    <button class="btn btn-white btn-info btn-bold btn-xs" data-toggle="modal" data-target="#edit_menu_<?php echo $data->id ?>">
                                                        Edit
                                                    </button>
                                                    <button class="btn btn-white btn-danger btn-bold btn-xs" data-toggle="modal" data-target="#delete_menu_<?php echo $data->id ?>">
                                                        X
                                                    </button>
                                                    <?php echo $data->description?>
                                                    <?php
                                                    echo "<ol>";
                                                        print_tree( $data->branch );
                                                    echo "</ol>";
                                                echo  '</li>';
                                        }
                                    };
                                    print_tree( $menus_tree );
                                ?>
                            </ol>
                        </div>
                    </div>
                    <!-- HIRARKI -->
                </div>
            </div>
            </div>
        </div>
        </div>
    </section>
</div>
  <?php
    foreach( $menu_list as $menu )
    {
        $model_form_add = array(
          "name" => "Tambah Child Kategori",
          "modal_id" => "add_menu_",
          "button_color" => "primary",
          "url" => site_url( $current_page."add/"),
          "form_data" => array(
            "name" => array(
				'type' => 'text',
                'label' => "Nama Menu",
                'value' => "",				                
			  ),
			  "description" => array(
				'type' => 'textarea',
				'label' => "Deskripsi",
				'value' => "-",				
			  ),
			  "_order" => array(
				'type' => 'number',
				'label' => "Urutan",
				'value' => 1,				
			  ),
			  "category_id" => array(
				'type' => 'hidden',
				'label' => "category_id",
				'value' => $menu->id,
			  ),
          ),
          'data' => $menu,
          'param' => "id",
        );
        $this->load->view('templates/actions/modal_form_no_button', $model_form_add ); 
        $model_form_edit = array(
          "name" => "Edit Kategori",
          "modal_id" => "edit_menu_",
          "button_color" => "primary",
          "url" => site_url( $current_page."edit/"),
          "form_data" => array(
                "id" => array(
                    'type' => 'hidden',
                    'label' => "id",
                ),
                "name" => array(
                    'type' => 'text',
                    'label' => "Nama Menu",
                ),
                "description" => array(
                    'type' => 'textarea',
                    'label' => "Deskripsi",
                ),
                "_order" => array(
                    'type' => 'number',
                    'label' => "Urutan",
                ),
          ),
          'data' => $menu,
          'param' => "id",
        );
        $this->load->view('templates/actions/modal_form_no_button', $model_form_edit ); 

        // $menu->group_id = $group->id;
        $model_form_delete =array(
          "type" => "modal_delete",
          "modal_id" => "delete_menu_",
          "url" => site_url( $current_page."delete/"),
          "button_color" => "danger",
          "param" => "id",
          "form_data" => array(
            "id" => array(
              'type' => 'hidden',
              'label' => "id",
            ),
          ),
          'data' => $menu,
          "title" => "Kategori",
          "data_name" => "name",
        );
        $this->load->view('templates/actions/modal_delete_no_button', $model_form_delete ); 
    }
  ?>

