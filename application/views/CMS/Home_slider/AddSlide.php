<?php
$this->load->view('layout/header');
$this->load->view('layout/topmenu');
?>
<!-- ================== BEGIN PAGE CSS STYLE ================== -->
<link href="<?php echo base_url(); ?>assets/plugins/jquery-tag-it/css/jquery.tagit.css" rel="stylesheet" />
<script src="<?php echo base_url(); ?>assets/plugins/jquery-tag-it/js/tag-it.min.js"></script>

<link href="<?php echo base_url(); ?>assets/plugins/jquery-file-upload/css/jquery.fileupload.css" rel="stylesheet" />
<link href="<?php echo base_url(); ?>assets/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet" />

<!-- begin #content -->
<!-- begin #content -->
<div id="content" class="content content-full-width">
    <!-- begin vertical-box -->
    <div class="vertical-box">
        <!-- begin vertical-box-column -->

        <!-- end vertical-box-column -->
        <!-- begin vertical-box-column -->
        <div class="vertical-box-column">

            <!-- begin wrapper -->
            <div class="wrapper">
                <div class="p-30 bg-white">
                    <!-- begin  form -->

                    <h2>Add Slider</h2>


                    <form action="#" method="post" enctype="multipart/form-data">

                        <!-- begin email to -->
                        <div class="row">
                            <div class="col-lg-6 col-sm-12">
                                <div class="thumbnail">

                                    <div class="profile-image" style="background: url(<?php echo base_url(); ?>assets/emoji/user.png); background-size: cover;">
                                        <img src='' style="width: 100%;   height: auto;" />
                                        <i class="fa fa-user hide"></i>
                                    </div>                            <div class="caption">
                                        <div class="form-group">
                                            <label for="image1">Upload Primary Image</label>
                                            <input type="file" name="picture" file-model="filename" required="" />           
                                        </div>
                                        <button type="submit"  name="submit_data"  class="btn btn-warning" >Update <i class="fa fa-upload"></i></button>

                                    </div>  
                                </div>
                              


                            </div>


                        </div>


                    </form>


                </div>
            </div>
            <!-- end wrapper -->
        </div>
        <!-- end vertical-box-column -->
    </div>
    <!-- end vertical-box -->
</div>
<!-- end #content -->


<?php
$this->load->view('layout/footer');
?>
<script>
    function changeCategory(cat_name, cat_id) {
        $("#category_name").text(cat_name);
        $("#category_id").val(cat_id);
    }

    $(function () {






<?php
$checklogin = $this->session->flashdata('checklogin');
if ($checklogin['show']) {
    ?>
            $.gritter.add({
                title: "<?php echo $checklogin['title']; ?>",
                text: "<?php echo $checklogin['text']; ?>",
                image: '<?php echo base_url(); ?>assets/emoji/<?php echo $checklogin['icon']; ?>',
                            sticky: true,
                            time: '',
                            class_name: 'my-sticky-class '
                        });
    <?php
}
?>
                })
</script>