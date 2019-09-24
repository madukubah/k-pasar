  <!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= base_url()  ?>" class="brand-link">
      <img src="<?= base_url() . FAVICON_IMAGE ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light"><?php echo APP_NAME ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <!-- <img src="<?= base_url('assets/') ?>dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> -->
                <img class="img-circle elevation-2" src="<?php echo $user_image ?>" width="48" height="48" alt="User" />
        </div>
        <div class="info">
          <a href="<?= base_url('user/profile') ?>" class="d-block"><?php echo ucwords($this->session->userdata('user_profile_name')) ?></a>
        </div>
      </div>
      
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <!-- <li class="nav-header">DAFTAR MENU</li> -->
          <?php
            function print_menus( $datas )
            {
                foreach( $datas as $data )
                {
                    if( ( !$data->status )  ) continue;

                    if( !empty( $data->branch )  )
                    {
                        ?>
                              <li class="nav-item has-treeview ">
                                <a id="<?php echo $data->list_id ?>" href="#" class="nav-link ">
                                  <i class="nav-icon fas fa-<?php echo $data->icon ?>"></i>                                  
                                  <!-- <i class="far fa-circle nav-icon"></i>                                   -->
                                  <p>
                                    <?php echo $data->name?>
                                    <i class="right fas fa-angle-left"></i>
                                  </p>
                                </a>
                                <ul class=" ml-4 nav nav-treeview">
                                  <?php
                                      print_menus( $data->branch );
                                  ?>
                                </ul>
                              </li>
                        <?php
                    }else{
                        ?>
                            <li class="nav-item">
                              <a id="<?php echo $data->list_id ?>" href="<?php echo site_url( $data->link ) ?>" class="nav-link">
                                <i class="nav-icon fas fa-<?php echo $data->icon ?>"></i>
                                <p>
                                  <?php echo $data->name?>
                                  <span id="<?php echo 'notif_'.$data->list_id ?>" class="right badge badge-danger"></span>
                                </p>
                              </a>
                            </li>
                        <?php
                    }
                }
            }
          
            print_menus( $_menus );
          ?>
        </ul>

      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>


<script type="text/javascript">
    function menuActive(id) {
        id = id.trim();
        // console.log(id);
        // console.log(a = document.getElementById(id.trim()));
        a = document.getElementById(id.trim())
        // // var a =document.getElementById("menu").children[num-1].className="active";
        // var a = document.getElementById(id.trim());
        // console.log(a.parentNode.parentNode);
        a.classList.add("active");
        b = a.parentNode.parentNode.parentNode;
        b.classList.add("menu-open");
        b.children[0].classList.add("active");
        // console.log( b.children[0] );
        // document.getElementById(id).classList.add("active");

    }
</script>