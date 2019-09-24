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
        <div class="row ">
            <!-- header -->
            <div class=" col-md-12 ">
                <div class="card">
                    <div class="card-header">
                        <div class="row clearfix">
                            <div class="col-md-12">
                                <!-- alert  -->
                                <?php
                                    echo $alert;
                                ?>
                                <!-- alert  -->
                            </div>
                        </div>
                        <!--  -->
                        <div class="row clearfix" >
                        <div class="col-md-6">
                            <h2>
                                <?php echo strtoupper($header)?>
                            </h2>
                        </div>
                        </div>
                        <!--  -->
                    </div>
                </div>
            </div>
            <!--  -->
            <div class=" col-md-8 ">
                <div class="card">
                    <div class="card-body">
                        <!--  -->
                        <h2>
                            Anda belum mengisi profil usaha anda
                        </h2>
                        <!--  -->
                    </div>
                </div>
            </div>
            <!-- photo -->
            <div class=" col-md-4 ">
                <div class="row clearfix">
                    <div class=" col-md-12 ">
                        <div class="card">
                            <div class="card-body">
                                <a href="<?php echo site_url( ).$current_page,'create/' ?>" class="btn btn-block btn-md btn-primary waves-effect">Isi profil usaha saya</a>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
            <!--  -->
        </div>
    </div>
  </section>
</div>