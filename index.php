<?php
require_once 'header.php';
require_once 'oauth.php';
?>

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <h3>Tirita Search Orders</h3>
        </div>
    </div>

    <form method="post" id="formOrders" action="submit.php">
        <div class="row">

            <input type="hidden" name="start" id="start" value=""/>
            <input type="hidden" name="end" id="end" value=""/>
            <div class="col-4">
                <input type="text" class="form-control" name="daterange" id="daterange"
                       placeholder="Select Date Range" value=""/>
            </div>
            <div class="col-4">
                <select id="status" name="status" class="form-control">
                    <option>Open</option>
                    <option>Unshipped</option>
                    <option>Unpaid</option>
                    <option>Completed</option>
                    <option>Processing</option>
                </select>
            </div>
            <div class="col-4">
                <button class="btn btn-primary btnSubmit">Submit</button>
            </div>

            <!--<div class="col-6">
                <button class="btn btn-primary" id="btnExport">Export to CSV</button>
            </div>-->
        </div>
    </form>

    <?php if(false) { ?>
    <div class="row">
        <div class="col-12 text-center pt-3">
            <table class="table" id="tblOrders">
                <thead>
                <tr>
                    <th>Buyer Name</th>
                    <th>Buyer Address</th>
                    <th>Buyer E-mail</th>
                    <!--<th>Buyer phone</th>-->
                    <th>Product Title</th>
                    <th>Product SKU</th>
                    <th>Product Quantity</th>
                    <th>Product price</th>
                    <th>Buyer Custom message</th>
                    <!--<th>Buyer Notes</th>-->
                </tr>
                </thead>

                <tbody id="tblOrdersBody">
                <?php
                if (isset($results)) {
                    foreach ($results as $receipt) {
                        foreach ($receipt->Transactions as $transaction) {
                            ?>
                            <tr>
                                <td><?= $receipt->name ?></td>
                                <td><?= $receipt->formatted_address ?></td>
                                <td><?= $receipt->buyer_email ?></td>
                                <!--<td>Buyer phone</td>-->
                                <td><?= $transaction->title ?></td>
                                <td><?= 'SKU'  ?></td>
                                <td><?= $transaction->quantity ?></td>
                                <td><?= $transaction->price . $transaction->currency_code ?></td>
                                <td><?= $receipt->message_from_buyer ?></td>
                                <!--<td>Buyer Notes</td>-->
                            </tr>
                            <?php
                        }
                    }
                }
                ?>
                </tbody>
        </div>
    </div>
    <?php } ?>

</div>

</body>
</html>

<script type="text/javascript">
    (function ($) {

        var pickerOptions = {
            opens: 'left',
            autoUpdateInput: false,
            locale: {
                format: 'YYYY-MM-DD'
            }
        };

        <?php if(isset($_POST['start'])) { ?>
            pickerOptions.startDate = new Date('<?=$_POST['start']?>');
            pickerOptions.endDate = new Date('<?=$_POST['end']?>');
        <?php } ?>

        $('#daterange').daterangepicker(pickerOptions, function (start, end, label) {
            $('#start').val(start);
            $('#end').val(end);
            $('#daterange').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
            //$('#formOrders').submit();
        });

        <?php if(isset($_POST['status'])) { ?>
            $('#status').val('<?=$_POST['status']?>');
        <?php } ?>

        $('.btnSubmit').on('click', function (e) {
            /*if($('#status').val() == 'Completed' && $('#daterange').val() == '') {
                alert('You must select a date range for completed orders');
                return false;
            } else {
                $('#formOrders').submit();
            }*/
            $('#formOrders').submit();
        });
    })(jQuery);
</script>