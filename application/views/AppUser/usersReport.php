<?php
$this->load->view('layout/header');
$this->load->view('layout/topmenu');
?>?>
<style>
    .product_text {
        float: left;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
        width:350px
    }
    .product_title {
        font-weight: 700;
    }
    .price_tag{
        float: left;
        width: 100%;
        border: 1px solid #222d3233;
        margin: 2px;
        padding: 0px 2px;
    }
    .price_tag_final{
        width: 100%;
    }

    .exportdata{
        margin: 15px 0px 0px 0px;
    }
</style>
<!-- Main content -->
<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
<link href="<?php echo base_url(); ?>assets/plugins/DataTables/css/data-table.css" rel="stylesheet" />
<!-- ================== END PAGE LEVEL STYLE ================== -->


<section class="content">
    <div class="">

        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Plumbers Reports

                </h3>
                <div class="panel-tools">

                </div>

            </div>
            <div class="box-body">



                <!-- Tab panes -->
                <div class="tab-content">


                    <div class="row" style="padding:20px">
                        <table id="tableData" class="table table-bordered smallfonttable">
                            <thead>
                                <tr>
                                    <th style="width: 20px;">S.N.</th>
                                    <th style="width:50px;">Image</th>
                                    <th style="width: 75px;">Plumber</th>

                                    <th style="width: 150px;">Contact No.</th>
                                    <th style="width: 100px;">Address </th>
                                    <th style="width: 150px;">Referral  Code </th>

                                    <th style="width: 100px;">Currnt OTP</th>
                                     <th style="width: 100px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($users)) {

                                    $count = 1;
                                    foreach ($users as $key => $value) {
                              
                                        ?>
                                        <tr>
                                            <td><?php echo $count; ?></td>

                                            <td>


                                                <img src = '<?php echo base_url(); ?>assets/<?php echo $value->profile_image ? "profile_image/" . $value->profile_image : "emoji/user.png"; ?>' alt = "" class = "media-object rounded-corner" style = "    width: 30px;" />



                                            </td>

                                            <td>
                                                <table class="minitable">
                                                    <tr >
                                                        <td style="width: 40px;text-align: center;">
                                                            <span class="fa-stack fa-1x">
                                                                <i class="fa fa-circle fa-stack-2x"></i>
                                                                <i class="fa fa-user fa-stack-1x fa-inverse"></i>
                                                            </span>
                                                        </td>
                                                        <td><?php echo $value->name; ?></td>
                                                    </tr>
                                                    <tr >
                                                        <td style="width: 40px;text-align: center;">
                                                            <span class="fa-stack fa-1x">
                                                                <i class="fa fa-circle  fa-stack-2x"></i>
                                                                <i class="fa fa-envelope   fa-inverse"></i>
                                                            </span>
                                                        </td>
                                                        <td><?php echo $value->email; ?></td>
                                                    </tr>
                                                </table>
                                            </td>


                                            <td>
                                                <table class="minitable">
                                                    <tr >
                                                        <td style="width: 40px;">
                                                            <img src="<?php echo base_url(); ?>assets/emoji/call.png" style="height:20px" />
                                                        </td>
                                                        <td>
                                                            <?php echo $value->contact_no; ?>
                                                        </td>
                                                    </tr>
                                                    <tr >
                                                        <td >
                                                            <img src="<?php echo base_url(); ?>assets/emoji/wa.png" style="height:20px" />
                                                        </td>
                                                        <td>
                                                            <?php echo $value->wp_no; ?>
                                                        </td>
                                                    </tr>

                                                </table>


                                            </td>

                                            <td>
                                                <span class="">

                                                    <?php echo $value->address; ?><br/>
                                                    <?php echo $value->city; ?>, <?php echo $value->district; ?>, <?php echo $value->state; ?>
                                                </span>
                                            </td>
                                            <td>

                                                <span class="">
                                                    <?php echo $value->rcode; ?>
                                                </span>

                                            </td>
                                            <td>
                                                <span class="">

                                                    <?php echo $value->usercode; ?>
                                             
                                                </span>
                                            </td>


                                          

                                            <td>
                                                <a href="<?php echo site_url('AppUser/user_details/' . $value->id); ?>" class="btn btn-danger"><i class="fa fa-eye "></i> View</a>
                                            </td>
                                        </tr>
                                        <?php
                                        $count++;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>



            </div>
        </div>
    </div>
</section>
<!-- end col-6 -->
</div>

<script src="<?php echo base_url(); ?>assets/plugins/DataTables/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/js/table-manage-default.demo.min.js"></script>
<?php
$this->load->view('layout/footer');
?> 
<script>
    $(function () {

        $('#tableData').DataTable({
        })
    })

</script>