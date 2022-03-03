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
                <div class="m-b-10">
                    <form action="#" method="post" enctype="multipart/form-data">


                        <div class="thumbnail">

                            <div class="profile-image" style="background: url(<?php echo base_url(); ?>assets/emoji/user.png); background-size: cover;">
                                <img src='<?php echo base_url(); ?>assets/profile_image/<?php echo $userdata->profile_image; ?>' style="width: 100%;   height: auto;
                                     " />
                                <i class="fa fa-user hide"></i>
                            </div>                            <div class="caption">
                                <div class="form-group">
                                    <label for="image1">Upload Primary Image</label>
                                    <input type="file" name="picture" file-model="filename" required="" />           
                                </div>
                                <button type="submit" name="submit" class="btn btn-warning" >Update <i class="fa fa-upload"></i></button>

                            </div>
                        </div>
                        <div class="btn-group" role="group" aria-label="..." style="    width: 100%;">


                        </div>
                    </form>



                </div>
                <!-- begin profile-highlight -->
                <div class="profile-highlight">
                    <h4><i class="fa fa-cog"></i> Settings</h4>
                    <div class="checkbox m-b-5 m-t-0" >
                        <label><input type="checkbox" id="edit_toggle" /> Edit Profile Information</label>
                    </div>

                    <!--                    <div class="checkbox m-b-0">
                                            <button class="btn btn-xs btn-link" data-toggle="modal" data-target="#changePassword"><i class="fa fa-lock"></i> Change Your Password</button>
                                        </div>-->
                    
                    <h2>
                        <small>Currrent OTP:</small> <br/><?php echo $userdata->usercode;?>
                    </h2>
                    
                    <h2>
                        <small>Refrel With:</small> <br/><?php echo $userdata->rcode_connect;?>
                    </h2>
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
                                            <a class="media-left" href="javascript:;">
                                                <img src='<?php echo base_url(); ?>assets/profile_image/<?php echo $userdata->profile_image; ?>' alt="" class="media-object rounded-corner" style="    width: 45px;background: url(<?php echo base_url(); ?>assets/emoji/user.png);    height: 45px;background-size: cover;">
                                            </a>
                                            <div class="media-body">
                                                <h4 class="media-heading"><?php echo $userdata->name; ?> </h4>
                                                <p>
                                                    <?php echo $userdata->contact_no; ?>
                                                </p>
                                               
                                            </div>
                                            <div class="media-right">
                                                 <p>Referral Code: <b> <?php echo $userdata->rcode;?></b></p>
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
                                <tr style="background: #2d353c;                                    color: white;">
                                    <th colspan="2">Personal Information</th>
                                </tr>
                                <?php
                                $datacblock = [
                                    array("title" => "Name", "value" => $userdata->name, "type" => "text", "data_name" => "[", "class" => "highlight"),
                                    array("title" => "Contact No.", "value" => $userdata->contact_no, "type" => "text", "data_name" => "contact_no", "class" => "highlight"),
                                    array("title" => "Email", "value" => $userdata->email, "type" => "text", "data_name" => "email", "class" => "highlight"),
                                    array("title" => "WhatsApp No.", "value" => $userdata->wp_no, "type" => "text", "data_name" => "wp_no", "class" => "highlight"),
                                ];
                                foreach ($datacblock as $key => $value) {
                                    ?>
                                    <tr class="<?php echo $value["class"]; ?>" >
                                        <td style="width:150px" class="field"><?php echo $value["title"]; ?></td>
                                        <td>
                                            <span id="profession" data-type="<?php echo $value["type"]; ?>" data-pk="<?php echo $userdata->id; ?>" data-name="<?php echo $value["data_name"]; ?>" data-value="<?php echo $value["value"]; ?>" data-url="<?php echo site_url("LocalApi/updateUserClient"); ?>" data-original-title="<?php echo $value["title"]; ?>" class="m-l-5 editable editable-click" tabindex="-1" > <?php echo $value["value"]; ?></span><button class="btn btn-xs btn-link edit_detail" ><i class="fa fa-pencil"></i>Edit</button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>



                                <?php
                                $datacblock = [
                                    array("title" => "Address", "value" => $userdata->address, "type" => "text", "data_name" => "address", "class" => "highlight"),
                                    array("title" => "State", "value" => $userdata->state, "type" => "text", "data_name" => "state", "class" => "highlight"),
                                    array("title" => "District", "value" => $userdata->district, "type" => "text", "data_name" => "district", "class" => "highlight"),
                                    array("title" => "City", "value" => $userdata->city, "type" => "text", "data_name" => "city", "class" => "highlight"),
                                ];
                                foreach ($datacblock as $key => $value) {
                                    ?>
                                    <tr class="<?php echo $value["class"]; ?>">
                                        <td class="field"><?php echo $value["title"]; ?></td>
                                        <td>
                                            <span id="profession" data-type="<?php echo $value["type"]; ?>" data-pk="<?php echo $userdata->id; ?>" data-name="<?php echo $value["data_name"]; ?>" data-value="<?php echo $value["value"]; ?>" data-url="<?php echo site_url("LocalApi/updateUserClient"); ?>" data-original-title="<?php echo $value["title"]; ?>" class="m-l-5 editable editable-click" tabindex="-1" > <?php echo $value["value"]; ?></span><button class="btn btn-xs btn-link edit_detail" ><i class="fa fa-pencil"></i>Edit</button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>

                                <tr style="background: #2d353c;                                    color: white;">
                                    <th colspan="2">Payment Information</th>
                                </tr>


                                <?php
                                $datacblock = [
                                    array("title" => "Bank Account Name", "value" => $userdata->bank_account_name, "type" => "text", "data_name" => "bank_account_name", "class" => "highlight"),
                                    array("title" => "Bank Account No.", "value" => $userdata->bank_account_no, "type" => "text", "data_name" => "bank_account_no", "class" => "highlight"),
                                    array("title" => "Bank IFSC", "value" => $userdata->bank_ifsc, "type" => "text", "data_name" => "bank_ifsc", "class" => "highlight"),
                                    array("title" => "PayTM No.", "value" => $userdata->paytm_no, "type" => "text", "data_name" => "paytm_no", "class" => "highlight"),
                                    array("title" => "UPI ID#", "value" => $userdata->upi_id, "type" => "text", "data_name" => "upi_id", "class" => "highlight"),
                                ];
                                foreach ($datacblock as $key => $value) {
                                    ?>
                                    <tr class="<?php echo $value["class"]; ?>">
                                        <td class="field"><?php echo $value["title"]; ?></td>
                                        <td>
                                            <span id="profession" data-type="<?php echo $value["type"]; ?>" data-pk="<?php echo $userdata->id; ?>" data-name="<?php echo $value["data_name"]; ?>" data-value="<?php echo $value["value"]; ?>" data-url="<?php echo site_url("LocalApi/updateUserClient"); ?>" data-original-title="<?php echo $value["title"]; ?>" class="m-l-5 editable editable-click" tabindex="-1" > <?php echo $value["value"]; ?></span><button class="btn btn-xs btn-link edit_detail" ><i class="fa fa-pencil"></i>Edit</button>
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

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="">
                <div class="col-md-3 text-center">
                    <div class="widget widget-stats bg-blue">
                        <div class="stats-icon"><i class="fa fa-trophy"></i></div>
                        <div class="stats-info">
                            <h4>Total Points</h4>
                            <p><?php echo $points["totalremain"] ?></p>	
                        </div>

                    </div>

                </div>
                <div class="col-md-3 text-center">
                    <div class="widget widget-stats bg-red">
                        <div class="stats-icon"><i class="fa fa-suitcase"></i></div>
                        <div class="stats-info">
                            <h4>Amount Paid</h4>
                            <p><?php echo $points["paid"] ?></p>	
                        </div>
                    </div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="widget widget-stats bg-yellow-darker">
                        <div class="stats-icon"><i class="fa fa-arrow-circle-down"></i></div>
                        <div class="stats-info">
                            <h4>Points Credited</h4>
                            <p><?php echo $points["credit"] ?></p>	
                        </div>
                    </div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="widget widget-stats bg-red-darker">
                        <div class="stats-icon"><i class="fa fa-arrow-circle-up"></i></div>
                        <div class="stats-info">
                            <h4>Points Debited</h4>
                            <p><?php echo $points["debitsum"] ?></p>	
                        </div>
                    </div>
                </div>
            </div>
            <table class="table" >

                <?php
                foreach ($points["pointlist"] as $key => $value) {
                    ?>
                    <tr>
                        <td><?php echo $value["points"]; ?></td>
                        <td><?php echo $value["points_type"]; ?></td>
                        <td><?php echo $value["product"]; ?><br/><?php echo $value["remark"]; ?></td>
                        <td><?php echo $value["date"]; ?></td>


                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
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