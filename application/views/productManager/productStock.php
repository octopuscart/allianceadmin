<?php
$this->load->view('layout/header');
$this->load->view('layout/topmenu');
?>


<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
<link href="<?php echo base_url(); ?>assets/plugins/DataTables/css/data-table.css" rel="stylesheet" />
<!-- ================== END PAGE LEVEL STYLE ================== -->
<style>
    .product_text {
        float: left;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
        width:100px
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
    .sub_item_table tr{
        border-bottom: 1px solid #dbd3d3;
    }
    span.colorbox {
        float: left;
        width: 100%;
        padding: 5px;
        text-align: center;
        color: white;
        text-shadow: 0px 2px 4px #fff;
    }

</style>
<!-- Main content -->
<section class="content">
    <div class="">


        <?php
        if ($isproduct) {
            ?>
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <?php
                        if ($isproduct) {
                            echo "Add Stock -> " . $productobj["sku"];
                        } else {
                            echo "Select Product";
                        }
                        ?>
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="row col-md-12" style="margin-top:20px;">
                        <div class="col-md-5">

                            <table class="table">

                                <tr>
                                    <td colspan="" rowspan="5" class="text-center">
                                        <img src='<?php echo $productobj["file_name"] ?>' style='height:100px;'>
                                    </td>

                                </tr>
                                <tr>
                                    <td>Model No.</td>
                                    <td><?php echo $productobj["sku"] ?></td>
                                </tr>
                                <tr>
                                    <td>Title</td>
                                    <td><?php echo $productobj["title"] ?></td>
                                </tr>

                                <tr>
                                    <td>Price</td>
                                    <td>{{<?php echo $productobj["price"] ?>|currency:'<?php echo GLOBAL_CURRENCY; ?> '}}</td>
                                </tr>
                                <tr>
                                    <td>Reward Points</td>
                                    <td><?php echo $productobj["credit_limit"] ?></td>
                                </tr>

                            </table>
                        </div>
                        <div class="col-md-2"></div>
                        <div class="col-md-5">

                            <form action="#" method="post"  class="well well-sm row">
                                <h4>Add New Stock</h4>
                                <div class="form-group col-md-7">
                                    <label >Serial No.</label>
                                    <input type="text" class="form-control "  name="serial_no"  aria-describedby="emailHelp" placeholder="" required="">
                                </div>
                                <div class="form-group col-md-12">
                                    <button type="submit" name="submit" class="btn btn-primary">Add Stock</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <div class="panel panel-inverse" >
                <div class="panel-heading">
                    <h3 class="panel-title">List of Products Stock</h3>
                </div>
                <div class="panel-body" style="background: #c1ccd1">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#serial1ist" data-toggle="tab"><i class="fa fa-list"></i> List View</a></li>
                        <li><a href="#serialprint" data-toggle="tab"><i class="fa fa-qrcode"></i>  Print View</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active in" id="serial1ist">
                            <div class="well well-sm  col-md-12">
                                <button class="btn btn-inverse pull-right " onclick="printDiv('serialqrlistid')"><i class="fa fa-print"></i> Print QR</button>
                            </div>
                            <div id="serialqrlistid">
                                <table  class="table table-bordered " style="width:100%" border="1">
                                    <tr>
                                        <th>S. No.</th>
                                        <th>Serial No.</th>
                                        <th>Stock Date</th>
                                        <th style="width:100px">QR Code</th>
                                        <th style="width:100px">Remove</th>
                                    </tr>
                                    <?php
                                    $count = 1;
                                    foreach ($productstocklist as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><?php echo $count; ?></td>
                                            <td><?php echo $value["serial_no"]; ?></td>
                                            <td><?php echo $value["stock_date"]; ?></td>
                                            <td>
                                                <button type="button" class="btn btn-link btn-lg" data-toggle="modal" data-target="#viewserialno" onclick="viewQrcode('<?php echo site_url("Api/getCardQr/" . $value["serial_no"]); ?>', '<?php echo $value["serial_no"]; ?>')">

                                                    <img src="<?php echo site_url("Api/getCardQr/" . $value["serial_no"]); ?>" style="height: 50px">
                                                </button>
                                            </td>
                                            <td>
                                                <form action="" method="post">
                                                    <input type="hidden" name="stock_id" value="<?php echo $value["id"] ?>"/>
                                                    <button type="submit" name="submitstock" class="btn btn-danger">Remove</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php
                                        $count++;
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="serialprint">
                            <div class="well well-sm  col-md-12">
                                <button class="btn btn-inverse pull-right " onclick="printDiv('serialqrid')"><i class="fa fa-print"></i> Print QR</button>
                            </div>
                            <div id="serialqrid">
                                <table  class="table table-bordered " border="1">
                                    <?php
                                    $count = 1;
                                    foreach ($productstocklist as $key => $value) {
                                        ?>
                                        <tr>
                                            <?php
                                            if ($key % 3 == 0) {
                                                for ($qr = $key; $qr <= $key + 3; $qr++) {
                                                    if (isset($productstocklist[$qr])) {
                                                        ?>
                                                        <td style="text-align: center;">
                                                            <img src="<?php echo site_url("Api/getCardQr/" . $productstocklist[$qr]["serial_no"]); ?>" style="width: 200px">
                                                            <br/>
                                                            <p style="text-align: center;">
                                                                <?php echo $productstocklist[$qr]["serial_no"]; ?>
                                                            </p>
                                                        </td>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </tr>
                                        <?php
                                        $count++;
                                    }
                                    ?>

                                </table>
                            </div>

                        </div>
                    </div>

                </div>
            </div>


            <?php
        } else {
            ?>
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <?php
                        if ($isproduct) {
                            echo "Add Stock -> " . $productobj["sku"];
                        } else {
                            echo "Select Product";
                        }
                        ?>
                    </h3>
                </div>
                <div class="panel-body">
                    <table id="tableData" class="table table-bordered ">
                        <thead>
                            <tr>
                                <th style="width: 20px;">S.N.</th>
                                <th style="width:50px;">Image</th>
                                <th style="width:100px;">Category</th>
                                <th style="width:50px;">Model No.</th>
                                <th style="width:100px;">Title</th>
                                <th style="width:50px;">Items Prices</th>
                                <th style="width:50px;">Credit Points</th>
                                <th style="width:50px;">Stock</th>
                                <th style="width: 75px;"></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <?php
            }
            ?>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="viewserialno" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">
                        <small>Model No.</small>
                        <?php echo $productobj["sku"]; ?>
                    </h4>
                    Serial No.: <b id="serialnoid">XXXX</b>
                </div>
                <div class="modal-body">
                    <img src="<?php echo site_url("Api/getCardQr/1"); ?>" id="qrcodeid" style="width:100%;">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


</section>


<script src="<?php echo base_url(); ?>assets/plugins/DataTables/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/js/table-manage-default.demo.min.js"></script>

<?php
$this->load->view('layout/footer');
?> 
<script>
                                function printDiv(divName) {
                                    var DocumentContainer = document.getElementById(divName);
                                    var WindowObject = window.open('', "PrintWindow", "width=750,height=650,top=50,left=50,toolbars=no,scrollbars=yes,status=no,resizable=yes");
                                    WindowObject.document.writeln(DocumentContainer.innerHTML);
                                    WindowObject.document.close();
                                    WindowObject.focus();
                                    WindowObject.print();

                                }

                                function viewQrcode(imageurl, serialno) {
                                    $("#qrcodeid").attr("src", imageurl);
                                    $("#serialnoid").html(serialno);
                                }
                                $(function () {
<?php
if ($message) {
    ?>
                                        $.gritter.add({
                                            title: "Already in Stock",
                                            text: "Please check serial No., this is already in stock.",

                                            sticky: true,
                                            time: '',
                                            class_name: 'my-sticky-class '
                                        });
    <?php
}
?>




                                    $('#tableData').DataTable({
                                        "processing": true,
                                        "serverSide": true,
                                        "ajax": {
                                            url: "<?php echo site_url("ProductManager/productReportApi/1") ?>",
                                            type: 'GET'
                                        },
                                        "columns": [
                                            {"data": "s_n"},
                                            {"data": "image"},
                                            {"data": "category"},
                                            {"data": "sku"},
                                            {"data": "title"},
                                            {"data": "items_prices"},
                                            {"data": "credit_limit"},
                                            {"data": "stock_status"},
                                            {"data": "select"}]
                                    })
                                }
                                )

</script>