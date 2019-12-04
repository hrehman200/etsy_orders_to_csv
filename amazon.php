<?php
require_once 'header.php';
?>

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <h3>Tirita - Amazon Orders</h3>
        </div>
    </div>

    <form method="post" id="formOrders" action="submitamazon.php" enctype="multipart/form-data">
        <div class="row">
            <div class="col-4">
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="csvFile">
                        <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                    </div>
                </div>
            </div>

            <div class="col-4">
                <button class="btn btn-primary btnSubmit">Submit</button>
            </div>
        </div>
    </form>

    <?php if (false) { ?>
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
                                    <td><?= 'SKU' ?></td>
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
        $('.btnSubmit').on('click', function (e) {
            $('#formOrders').submit();
        });
    })(jQuery);
</script>