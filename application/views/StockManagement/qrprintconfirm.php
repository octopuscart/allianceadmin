<?php
$this->load->view('layout/header');
$this->load->view('layout/topmenu');
?>
<!-- ================== BEGIN PAGE CSS STYLE ================== -->

<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
<link href="<?php echo base_url(); ?>assets/plugins/DataTables/css/data-table.css" rel="stylesheet" />
<!-- ================== END PAGE LEVEL STYLE ================== -->

<!-- begin #content -->
<!-- begin #content -->
<div id="content" class="content content-full-width" ng-controller="stockController">
    <!-- begin vertical-box -->
    <div class="vertical-box">
        <!-- begin vertical-box-column -->

        <!-- end vertical-box-column -->
        <!-- begin vertical-box-column -->
        <div class="vertical-box-column">

            <!-- begin wrapper -->
            <div class="wrapper ">
                <div class="  row">
                    <!-- begin  form -->



                    <div class="col-md-12">
                        <div class="panel panel-inverse">
                            <div class="panel-heading">
                                <h3 class="panel-title">Print QR Codes</h3>
                            </div>
                            <div class="panel-body">
                                <?php
                                if ($dealer) {
                                    ?>
                                    <table id="tableData" class="table table-bordered ">
                                        <tr>
                                            <td>Name</td>
                                            <td><?php echo $dealer["name"]; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Contact No.</td>
                                            <td><?php echo $dealer["contact_no"]; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td><?php echo $dealer["email"]; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Order No</td>
                                            <td><?php echo $dealer_order["order_no"]; ?></td>
                                        </tr>

                                    </table>
                                    <div class="" id="serialprint">
                                        <div class="well well-sm  col-md-12">
                                            <div class="row">
                                                <form action="" method="post" class="col-md-6">
                                                    <button class="btn btn-inverse" type="submit" name="createqrs">Create QR</button>
                                                </form>
                                                <form action="" method="post"  class="col-md-6 text-right">
                                                    <?php if ($checkfordownload) { ?>
                                                        <button class="btn btn-inverse" type="submit" name="downloadzip">Download Now</button>
                                                        <?php
                                                    }else{
                                                        echo "<h3>File not found, Please click on create button.</h3>";
                                                    }
                                                    ?>
                                                </form>
                                            </div>
                                        </div>
                                        <div id="serialqrid">
                                            <table id="tableData" class="table table-bordered ">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 20px;">S.N.</th>
                                                        <th style="width:50px;" >Image</th>
                                                        <th>Product</th>
                                                        <th>Quantity</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $totalquantity = 0;
                                                    foreach ($order_products_main as $key => $value) {


                                                        $imageurl = base_url() . "assets/default/default.png";
                                                        if ($value['file_name']) {
                                                            $imageurl = base_url() . "assets/product_images/" . $value['file_name'];
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $key + 1; ?></td>
                                                            <td style="width:40px;"><img src="<?php echo $imageurl; ?>" style="width:40px;" /></td>
                                                            <td>
                                                                <b><?php echo $value["title"]; ?></b>
                                                                <br/>
                                                                <?php echo $value["sku"]; ?>
                                                            </td>
                                                            <td><?php
                                                                $totalquantity += $value["quantity"];
                                                                echo $value["quantity"];
                                                                ?>
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <td  colspan="4">
                                                                <div class="row" id="serialqrid<?php echo $value["product_id"]; ?>">
                                                                    <?php
                                                                    foreach ($product_qr_list[$value["product_id"]] as $pkey => $pvalue) {
                                                                        ?>
                                                                        <div style="width:25%;float:left">
                                                                            <img src="<?php echo base_url() . "assets/qrcodes/" . $pvalue["serial_no"] . ".png"; ?>" style="width: 100%">
                                                                            <br/>
                                                                            <p style="text-align: center;">
                                                                                <?php echo $pvalue["serial_no"]; ?>
                                                                            </p>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                    <tr>
                                                        <th colspan="3" class="text-right">Total Quantity</th>


                                                        <th colspan="" ><?php echo $totalquantity; ?></th>
                                                        <th colspan="" ></th>

                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>

                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- end wrapper -->
        </div>
        <!-- end vertical-box-column -->
    </div>
    <!-- end vertical-box -->




</div>
<!-- end #content -->


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

</script>

<script>
    Admin.controller('stockController', function ($scope, $http, $filter, $timeout) {
        $scope.orderdata = {"selected_dealer": {"name": "", "id": ""}, "selected_product": {"sku": "", "id": ""}};
        $scope.selectDealer = function (name, id) {
            $scope.orderdata.selected_dealer.id = id;
            $scope.orderdata.selected_dealer.name = name;
        }

        $scope.selectProduct = function (sku, id) {
            $scope.orderdata.selected_product.id = id;
            $scope.orderdata.selected_product.sku = sku;
        }
    });

</script>