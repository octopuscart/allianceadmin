<?php
$this->load->view('layout/header');
$this->load->view('layout/topmenu');
?>
<link href="<?php echo base_url(); ?>assets/plugins/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet" />
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap3-editable/js/bootstrap-editable.min.js"></script>

<link href="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet"  />

<link href="<?php echo base_url(); ?>assets/plugins/jquery-file-upload/css/jquery.fileupload.css" rel="stylesheet" />
<link href="<?php echo base_url(); ?>assets/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet" />

<link href="<?php echo base_url(); ?>assets/plugins/DataTables/css/data-table.css" rel="stylesheet" />

<script src="<?php echo base_url(); ?>assets/plugins/DataTables/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/js/table-manage-default.demo.min.js"></script>

<!-- begin #content -->
<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li><a href="<?php echo site_url(); ?>">Home</a></li>
        <li class="active">Dealer Profile Page</li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header"><?php echo $userdata->name; ?> <small><?php echo $userdata->contact_no; ?> </small></h1>
    <!-- end page-header -->
    <!-- begin profile-container -->
    <div class="profile-container" style="background: #d2cbcb;">
        <!-- begin profile-section -->
        <div class="profile-section" style="background: #fff;">
            <!-- begin profile-left -->
            <div class="profile-left">
                <!-- begin profile-image -->

                <!-- end profile-image -->
            
                <!-- begin profile-highlight -->
                <div class="profile-highlight">
                    <h4><i class="fa fa-cog"></i> Settings</h4>
                    <div class="checkbox m-b-5 m-t-0" >
                        <label><input type="checkbox" id="edit_toggle" /> Edit Profile Information</label>
                    </div>

                   
                </div>
                <!-- end profile-highlight -->
            </div>
            <!-- end profile-left -->
            <!-- begin profile-right -->
            <div class="profile-right">
                <!-- begin profile-info -->
                <div class="profile-info">
                    <!-- begin table -->
                    <div class="table-responsive">

                        <table class="table table-profile">
                            <thead>
                                <tr>
                                    <th colspan="2">

                                        <div class="media">
                                        
                                            <div class="media-body">
                                                <h4 class="media-heading"><?php echo $userdata->name; ?> </h4>
                                                <p>
                                                    <?php echo $userdata->contact_no; ?>
                                                </p>
                                            </div>
                                        </div>

                                        <!--                                        <h4>
                                                                                    <i class="ion-android-person"></i>
                                        <?php echo $userdata->name; ?>
                                                                                    <small>
                                        <?php echo $userdata->email; ?>
                                                                                    </small>
                                                                                </h4>-->
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $datacblock = [
                                    array("title" => "Party Name", "value" => $userdata->name, "type" => "text", "data_name" => "name", "class" => "highlight"),
                                     array("title" => "Proprietor Name", "value" => $userdata->propitor_name, "type" => "text", "data_name" => "propitor_name", "class" => "highlight"),
                                    array("title" => "Contact No.", "value" => $userdata->contact_no, "type" => "text", "data_name" => "contact_no", "class" => "highlight"),
                                    array("title" => "Email", "value" => $userdata->email, "type" => "text", "data_name" => "email", "class" => "highlight"),
                          
                                   array("title" => "State", "value" => $userdata->state, "type" => "text", "data_name" => "state", "class" => "highlight"),
                                    array("title" => "City", "value" => $userdata->city, "type" => "text", "data_name" => "city", "class" => "highlight"),
                                ];
                                foreach ($datacblock as $key => $value) {
                                    ?>
                                    <tr class="<?php echo $value["class"]; ?>">
                                        <td class="field"><?php echo $value["title"]; ?></td>
                                        <td>
                                            <span id="profession" data-type="<?php echo $value["type"]; ?>" data-pk="<?php echo $userdata->id; ?>" data-name="<?php echo $value["data_name"]; ?>" data-value="<?php echo $value["value"]; ?>" data-url="<?php echo site_url("LocalApi/updateDealer"); ?>" data-original-title="<?php echo $value["title"]; ?>" class="m-l-5 editable editable-click" tabindex="-1" > <?php echo $value["value"]; ?></span><button class="btn btn-xs btn-link edit_detail" ><i class="fa fa-pencil"></i>Edit</button>
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

<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>


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

        $('#gender').editable({
            source: {
                'Male': 'Male',
                'Female': 'Female'
            }
        });


        $('#profession').editable({
            source: {
                'Academic': 'Academic',
                'Medicine': 'Medicine',
                'Law': 'Law',
                'Banking': 'Banking',
                'IT': 'IT',
                'Entrepreneur': 'Entrepreneur',
                'Sales/Marketing': 'Sales/Marketing',
                'Other': 'Other',
            }
        });


        $('#country').editable({
            source: {
<?php
foreach ($country as $key => $value) {
    $cont = $value['country_name'];
    echo "'$cont':'$cont',";
}
?>

            }
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