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
                    Unused QR Reports

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
                                    <th style="width:50px;">Serial No.</th>
                                    <th style="width: 75px;">Product</th>
                                    <th style="width: 150px;">Dealer</th>
                                    <th style="width: 100px;">Order No. </th>
                                    <th style="width: 150px;">Date</th>
                                    <th style="width: 100px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($prouctlist)) {

                                    $count = 1;
                                    foreach ($prouctlist as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><?php echo $count; ?></td>

                                            <td><?php echo $value["serial_no"]; ?></td>
                                            <td><?php echo $value["title"]; ?></td>
                                            <td><?php echo $value["name"]; ?></td>
                                            <td><?php echo $value["order_no"]; ?></td>
                                            <td><?php echo $value["stock_date"]; ?></td>

                                            <td>
                                                <a href="<?php echo site_url('StockManagement/printQRConfirm/' . $value["dealer_order_id"]); ?>" class="btn btn-danger"><i class="fa fa-eye "></i> View</a>
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