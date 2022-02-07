<?php
$this->load->view('layout/header');
$this->load->view('layout/topmenu');
?>
<link href="<?php echo base_url(); ?>assets/plugins/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet" />
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap3-editable/js/bootstrap-editable.min.js"></script>


<!-- begin #content -->
<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li><a href="<?php echo site_url(); ?>">Home</a></li>
        <li class="active">Dealer Profile Page</li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">Update Settings<small> </small></h1>
    <!-- end page-header -->
    <!-- begin profile-container -->
    <div class="profile-container" style="background: #d2cbcb;">
        <!-- begin profile-section -->
        <div class="profile-section" style="background: #fff;">
            <!-- begin profile-left -->
           
            <!-- begin profile-right -->
            <div class="profile-">
                <!-- begin profile-info -->
                <div class="profile-info">
                    <!-- begin table -->
                      <div class="profile-highlight">
                    <h4><i class="fa fa-cog"></i> Settings</h4>
                    <div class="checkbox m-b-5 m-t-0" >
                        <label><input type="checkbox" id="edit_toggle" /> Check here to update settings</label>
                    </div>

                   
                </div>
                    <div class="table-responsive">

                        <table class="table table-profile">
                          
                            <tbody>
                                <?php
                                
                                foreach ($settings as $key => $value) {
                                    ?>
                                    <tr class="">
                                        <td class="field" style="width:200px;"><?php echo $value["title"]; ?></td>
                                        <td>
                                            <span id="profession" data-type="<?php echo $value["type"]; ?>" data-pk="<?php echo $value["id"];  ?>" data-name="attr_val" data-value="<?php echo $value["attr_val"]; ?>" data-url="<?php echo site_url("Configuration/updateSettings"); ?>" data-original-title="<?php echo $value["title"]; ?>" class="m-l-5 editable editable-click" tabindex="-1" > <?php echo $value["attr_val"]; ?></span><button class="btn btn-xs btn-link edit_detail" ><i class="fa fa-pencil"></i>Edit</button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>






                            </tbody>
                        </table>
                    </div>
                    <!-- end table -->
                </div>
                <!-- end profile-info -->
            </div>
            <!-- end profile-right -->
        </div>
        <!-- end profile-section -->


    </div>



</div>




<div style="clear:both"></div>
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

<script>
    $(function () {
        $('.edit_detail').hide();
        $("#edit_toggle").click(function () {
            $('.edit_detail').hide();
            if (this.checked) {
                $('.edit_detail').show();
            }
        })

        $('.edit_detail').click(function (e) {
            e.stopPropagation();
            e.preventDefault();
            $($(this).prev()).editable('toggle');
        });



    })
</script>

<script>
    $(function () {

        $('#tableDataOrder').DataTable({
            language: {
                "search": "Apply filter _INPUT_ to table"
            }
        })
    })

</script>