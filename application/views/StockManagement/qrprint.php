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

                    <div class="col-md-6">
                        <?php
                        if (!$dealer) {
                            ?>
                            <div class="panel panel-inverse">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Select Dealer</h3>
                                </div>
                                <div class="panel-body">
                                    <table id="tableData" class="table table-bordered ">
                                        <thead>
                                            <tr>
                                                <th style="width: 20px;">S.N.</th>
                                                <th style="width: 200px;">Dealer</th>

                                                <th></th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($dealers as $key => $value) {

                                                echo "<tr>";
                                                ?>
                                            <td><?php echo $key + 1; ?></td>
                                            <td><b><?php echo $value["name"]; ?></b><br/><?php echo $value["contact_no"]; ?></td>
                                            <td style="width: 100px">
                                                <button type="button" class="btn btn-primary p-l-40 p-r-40" data-toggle="modal" data-target="#ordercreate" ng-click="selectDealer('<?php echo $value["name"]; ?>', '<?php echo $value["id"]; ?>')"><i class="fa fa-plus"></i> Add Order</button>
                                            </td>
                                            <?php
                                            echo "</tr>";
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="panel panel-inverse">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Select Product</h3>
                                </div>
                                <div class="panel-body">
                                    <table id="tableData" class="table table-bordered ">
                                        <thead>
                                            <tr>
                                                <th style="width: 20px;">S.N.</th>
                                                <th style="width:50px;" >Image</th>
                                                <th  >Product</th>
                                                <th  ></th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($products as $key => $value) {
                                                $imageurl = base_url() . "assets/default/default.png";
                                                if ($value['file_name']) {
                                                    $imageurl = base_url() . "assets/product_images/" . $value['file_name'];
                                                }
                                                echo "<tr>";
                                                ?>
                                            <td><?php echo $key + 1; ?></td>
                                            <td style="width:40px;"><img src="<?php echo $imageurl; ?>" style="width:40px;" /></td>
                                            <td><b><?php echo $value["title"]; ?></b><br/>
                                                <?php echo $value["sku"]; ?></td>
                                            <td>
                                                <button type="button" class="btn btn-primary p-l-40 p-r-40" ng-click="selectProduct('<?php echo $value["sku"]; ?>', '<?php echo $value["id"]; ?>')"
                                                        data-toggle="modal" data-target="#add_item" ><i class="fa fa-plus"></i> Select</button>

                                            </td>
                                            <?php
                                            echo "</tr>";
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php }
                        ?>
                    </div>

                    <div class="col-md-6">
                        <div class="panel panel-inverse">
                            <div class="panel-heading">
                                <h3 class="panel-title">Create QR Codes</h3>
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
                                            foreach ($order_products as $key => $value) {


                                                $imageurl = base_url() . "assets/default/default.png";
                                                if ($value['file_name']) {
                                                    $imageurl = base_url() . "assets/product_images/" . $value['file_name'];
                                                }
                                                echo "<tr>";
                                                ?>
                                            <td><?php echo $key + 1; ?></td>
                                            <td style="width:40px;"><img src="<?php echo $imageurl; ?>" style="width:40px;" /></td>
                                            <td><b><?php echo $value["title"]; ?></b><br/>
                                                <?php echo $value["sku"]; ?></td>
                                            <td><?php
                                                $totalquantity += $value["quantity"];
                                                echo $value["quantity"];
                                                ?></td>

                                            <td class="text-right">
                                                <form action="#" method="post">
                                                    <input type="hidden" name="product_id" value="<?php echo $value["product_id"]; ?>">
                                                    <button class="btn btn-danger" name="deleteProduct" type="submit"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </td> 

                                            <?php
                                            echo "</tr>";
                                        }
                                        ?>
                                        <tr>
                                            <th colspan="3" class="text-right">Total Quantity</th>


                                            <th colspan="" ><?php echo $totalquantity; ?></th>
                                            <th colspan="" ></th>

                                        </tr>
                                        <tr>
                                            <th colspan="5" class="text-right">
                                                <form action="#" method="post">

                                                    <button class="btn btn-success" name="confirmorder" type="submit">Generate QR</button>
                                                </form>
                                            </th> 

                                        </tr>
                                        </tbody>
                                    </table>
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

    <!-- Modal -->
    <div class="modal fade" id="add_item" tabindex="-1" role="dialog" aria-labelledby="changePassword">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <form action="#" method="post">
                    <div class="modal-header">

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Add Product<br/><small>
                                Model No.: <b> {{orderdata.selected_product.sku}}</b>
                            </small>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label >Enter Quantity.</label>
                            <input type="number" class="form-control " name="quantity" min="0" placeholder="" value="" required=""> 
                            <input type="hidden" class="form-control " name="product_id"  placeholder="" value="{{orderdata.selected_product.id}}" required=""> 

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="addProduct" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="ordercreate" tabindex="-1" role="dialog" aria-labelledby="changePassword">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <form action="#" method="post">
                    <div class="modal-header">

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Add Order<br/><small>
                                Dealer: <b> {{orderdata.selected_dealer.name}}</b>
                            </small>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label >Enter Order No.</label>
                            <input type="text" class="form-control " id='order_no' name="order_no"  placeholder="" value="" required=""> 
                            <input type="hidden" class="form-control " id='order_no' name="dealer_id"  placeholder="" value="{{orderdata.selected_dealer.id}}" required=""> 

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="dealer_order" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</div>
<!-- end #content -->


<script src="<?php echo base_url(); ?>assets/plugins/DataTables/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/js/table-manage-default.demo.min.js"></script>

<?php
$this->load->view('layout/footer');
?> 
<script>
                                $(function () {

                                    $('#tableData').DataTable({
                                        "lengthChange": false,

                                    })
                                }
                                )

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