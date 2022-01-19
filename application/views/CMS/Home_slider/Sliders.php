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
                   
                    <h2>Slider List</h2>

                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">Sr. no.</th>
                         
                            <th scope="col">Image</th>
                       
                            <th scope="col ">Index</th>
                    
                            <th scope="col">Action</th>
                            
                             </tr>
                        </thead>
                        <tbody>
                            <?php $i=1; foreach ($slide_data as $key => $values) {?>
                            <tr>
                            <th scope="row"><?php echo $i++; ?></th>
                    
                            <td>
                               <?php  if ($values['image'] != "" && file_exists('./assets/slider_images/'.$values['image'])) {?>

                                   <img hieght="100px" width="100px" src="<?php echo base_url().'assets/slider_images/'.$values['image']?>" alt="slider image">
                                   <?php }?>
                                   <?php echo $values['image'];?>
                            </td>
                       
                            <td><?php echo $values['index']; ?></td>
                  
                            
                            <td>
                                <a href="<?php echo site_url("CMS/deleteSlide/"). $values['id'];?>" class="btn btn-sm btn-danger"> <i class="fa fa-trash"></i> Delete</a>
                            </td>
                            
                            </tr>
                            <?php }?>
                            
                        </tbody>
                        </table>
                  
    
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
    function changeCategory(cat_name, cat_id){
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