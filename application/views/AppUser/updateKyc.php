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
        <li class="active">Profile Page</li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">KYC Update<small> ID#:<?php echo $userkycdata->id; ?> </small></h1>
    <!-- end page-header -->
    <!-- begin profile-container -->
    <div class="profile-container" style="background: #d2cbcb;">
        <!-- begin profile-section -->
        <div class="profile-section" style="background: #fff;">

            <!-- begin profile-right -->
            <div class="col-md-6">
                <!-- begin profile-info -->
                <div class="profile-info">
                    <!-- begin table -->
                    <div class="table-responsive">

                        <table class="table table-profile">
                            <thead>
                                <tr>
                                    <th colspan="2">

                                        <div class="media">
                                            <a class="media-left" href="javascript:;">
                                                <img src='<?php echo base_url(); ?>assets/profile_image/<?php echo $userdata->profile_image; ?>' alt="" class="media-object rounded-corner" style="    width: 45px;background: url(<?php echo base_url(); ?>assets/emoji/user.png);    height: 45px;background-size: cover;">
                                            </a>
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
                                    array("title" => "Name", "value" => $userdata->name, "type" => "text", "data_name" => "[", "class" => "highlight"),
                                    array("title" => "Contact No.", "value" => $userdata->contact_no, "type" => "text", "data_name" => "contact_no", "class" => "highlight"),
                                    array("title" => "WhatsApp No.", "value" => $userdata->wp_no, "type" => "text", "data_name" => "wp_no", "class" => "highlight"),
                                ];
                                foreach ($datacblock as $key => $value) {
                                    ?>
                                    <tr class="<?php echo $value["class"]; ?>" >
                                        <td style="width:150px" class=""><?php echo $value["title"]; ?></td>
                                        <td>
                                            <?php echo $value["value"]; ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>



                              


                                <?php
                                $datacblock = [
                                    array("title" => "State", "value" => $userdata->state, "type" => "text", "data_name" => "state", "class" => "highlight"),
                                    array("title" => "District", "value" => $userdata->district, "type" => "text", "data_name" => "district", "class" => "highlight"),
                                    array("title" => "City", "value" => $userdata->city, "type" => "text", "data_name" => "city", "class" => "highlight"),
                                ];
                                foreach ($datacblock as $key => $value) {
                                    ?>
                                    <tr class="<?php echo $value["class"]; ?>">
                                        <td class=""><?php echo $value["title"]; ?></td>
                                        <td>
                                            <?php echo $value["value"]; ?>                                        </td>
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


            <!-- begin profile-left -->
            <div class="col-md-6">
                <!-- begin profile-image -->

                <!-- end profile-image -->
                <div class="m-b-10">
                    <form action="#" method="post" enctype="multipart/form-data">
                        <div class="well well-sm " style="margin-top: 20px;">
                            <table class="table">
                                <tr>
                                    <th>Request ID#</th>
                                    <td><?php echo $userkycdata->id; ?></td>

                                </tr>
                                <tr>
                                    <th>Document Type</th>
                                    <td><?php echo $userkycdata->doc_type; ?></td>

                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><?php echo $userkycdata->status; ?></td>

                                </tr>
                                <tr>
                                    <th>Request Date/Time</th>
                                    <td><?php echo $userkycdata->date; ?> <?php echo $userkycdata->time; ?></td>

                                </tr>

                            </table>

                            <br/>
                            <button type="submit" class="btn btn-success" name="status" value="Approved"><i class="fa fa-check"></i> Approve</button>
                            <button type="submit" class="btn btn-danger pull-right" name="status" value="Delete" style="margin-left:10px;"><i class="fa fa-trash"></i> Delete</button>
                            <button type="submit" class="btn btn-warning pull-right" name="status" value="Reject"><i class="fa fa-times"></i> Reject</button> 

                        </div>
                        <div class="thumbnail">
                            <h4><?php echo $userkycdata->doc_type; ?></h4>
                            <img src='<?php echo base_url(); ?>assets/profile_image/<?php echo $userkycdata->doc_image; ?>' style="width: 100%;   height: auto;
                                 " />



                        </div>

                    </form>
                </div>

            </div>
            <!-- end profile-left -->
        </div>
        <!-- end profile-section -->


    </div>



</div>


<!-- Modal -->
<div class="modal fade" id="changePassword" tabindex="-1" role="dialog" aria-labelledby="changePassword">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form action="#" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Change Your Password</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Current Password</label>
                        <input type="" class="form-control"  required="" placeholder="Enter Your Current Password" value="<?php echo $userdata->password; ?>" disabled="">
                        <input type="hidden" name="c_password" class="form-control"  required="" placeholder="Enter Your Current Password" value="<?php echo $userdata->password; ?>" disabled="">

                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">New Password</label>
                        <input type="password" class="form-control" name="n_password"  required=""  placeholder="Enter New Password">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Confirm Password</label>
                        <input type="password" class="form-control"name="r_password" required=""  placeholder="Reenter Password">
                    </div>
                </div>


                <div class="modal-footer">
                    <button type="submit" name="changePassword" class="btn btn-primary">Save changes</button>

                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
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