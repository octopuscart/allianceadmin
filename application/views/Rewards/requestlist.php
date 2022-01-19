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



<section class="content">
    <div class="">

        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Redeem Requests Reports

                </h3>
                <div class="panel-tools">

                </div>

            </div>
            <div class="box-body">



                <!-- Tab panes -->
                <div class="tab-content">


                    <div class="" style="padding:20px">
                        <table id="tableDataOrder" class="table table-bordered smallfonttable">
                            <thead>
                                <tr>
                                    <th style="width: 20px;">S.N.</th>
                                    <th style="width:50px;">Image</th>
                                    <th >Plumber Name</th>
                                    <th >Plumber Contact No.</th>
                                    <th style="width: 100px;">Reward Points </th>
                                    <th style="width: 100px;">Status </th>
                                    <th style="width: 150px;">Request Date/Time </th>


                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($redeemlist)) {

                                    $count = 1;
                                    foreach ($redeemlist as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><?php echo $count; ?></td>

                                            <td>
                                                <img src = '<?php echo base_url(); ?>assets/<?php echo $value["user"]->profile_image ? "profile_image/" . $value["user"]->profile_image : "emoji/user.png"; ?>' alt = "" class = "media-object rounded-corner" style = "    width: 30px;" />
                                            </td>
                                            <td>
                                                <span class="">
                                                    <?php echo $value["user"]->name; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="">
                                                    <?php echo $value["user"]->contact_no; ?>
                                                </span>
                                            </td>

                                            <td>
                                                <span class="">
                                                    <?php echo $value["points"]; ?>
                                                </span>
                                            </td>

                                            <td>
                                                <span class="">
                                                    <?php echo $value["status"]; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="">

                                                    <?php echo $value["date"]; ?>
                                                    <br/>
                                                    <?php echo $value["time"]; ?>

                                                </span>
                                            </td>

                                            <td>
                                                <a href="<?php echo site_url('RewardsManagement/paynow/' . $value["id"]); ?>" class="btn btn-danger"><i class="fa fa-eye "></i> Pay Now</a>
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


<?php
$this->load->view('layout/footer');
?> 
<script>
    $(function () {

        $('#tableDataOrder').DataTable({
            language: {
                "search": "Apply filter _INPUT_ to table"
            }
        })
    })

</script>