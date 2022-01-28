<?php

$this->load->view('layout/header');
$this->load->view('layout/topmenu');
?>
<style>
    .product_image{
        height: 200px!important;
    }
    .product_image_back{
        background-size: contain!important;
        background-repeat: no-repeat!important;
        height: 200px!important;
        background-position-x: center!important;
        background-position-y: center!important;
    }
</style>
<!-- Main content -->
<section class="content">
    <div class="">

        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Add New Dealer                   
                </h3>
                <div class="panel-tools">

                </div>

            </div>
            <div class="panel-body">
                <form action="#" method="post" enctype="multipart/form-data">






                    <div class="row">


                        <div class="col-md-6">
                            <div class="col-md-12">


                                <div class="form-group">
                                    <label >Name</label>
                                    <input type="text" class="form-control" name="name"  placeholder="Name">
                                </div>

                                <div class="form-group">
                                    <label >Contact No.</label>
                                    <input type="text" class="form-control"  name="contact_no"  placeholder="Contact No.">
                                </div>

                                <div class="form-group">
                                    <label>Email address</label>
                                    <input type="email" class="form-control"  name="email" placeholder="Enter email">
                                </div>

                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" class="form-control"  name="city" placeholder="City">
                                </div>

                                <div class="form-group">
                                    <label>State</label>
                                    <input type="text" class="form-control"  placeholder="State" name="state">
                                </div>
                            </div>



                            <div class="col-md-12">
                                <button type="submit" name="submit" class="btn btn-primary">Add Dealer</button>
                            </div>
                        </div>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>
</section>
<!-- end col-6 -->
</div>


<?php

$this->load->view('layout/footer');
?> 
<script>
$(function () {
<?php
$checklogin = $this->session->flashdata('checklogin');
if ($checklogin['show']) {
    ?>
        $.gritter.add({
            title: '<?php echo $checklogin['title']; ?>',
            text: '<?php echo $checklogin['text']; ?>',
            image: '<?php echo base_url(); ?>assets/emoji/<?php echo $checklogin['icon']; ?>',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
    <?php
}
?>
            })
</script>
