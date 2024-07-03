<?php
session_start(); // Ensure session is started
require_once '../posBackend/checkIfLoggedIn.php';
?>
<?php include '../inc/dashHeader.php'; ?>
<style>
    .wrapper {
        width: 85%;
        padding-left: 200px;
        padding-top: 20px
    }
</style>

<div class="wrapper">
    <div class="container-fluid pt-5 pl-600">
        <div class="row">
            <div class="m-50">
                <div class="mt-5 mb-3">
                    <h2 class="pull-left">Search Bills Details</h2>
                    <form method="POST" action="#">
                        <div class="row">
                            <div class="col-md-6">
                                <input required type="text" id="search" name="search" class="form-control" placeholder="Enter Bill ID, Table ID, Card ID, Payment Method">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-dark">Search</button>
                            </div>
                            <div class="col" style="text-align: right;">
                                <a href="bill-panel.php" class="btn btn-light">Show All</a>
                            </div>
                        </div>
                    </form>
                </div>

                <?php
                // Include config file
                require_once "../config.php";

                if (isset($_POST['search'])) {
                    if (!empty($_POST['search'])) {
                        $search = $_POST['search'];

                        $sql = "SELECT * FROM bills WHERE table_id LIKE '%$search%' OR payment_method LIKE '%$search%' OR bill_id LIKE '%$search%' OR card_id LIKE '%$search%'";
                    } else {
                        // Default query to fetch all bills
                        $sql = "SELECT * FROM bills ORDER BY bill_id;";
                    }
                } else {
                    $sql = "SELECT * FROM bills ORDER BY bill_id;";
                }

                if ($result = mysqli_query($link, $sql)) {
                    if (mysqli_num_rows($result) > 0) {
                        echo '<table class="table table-bordered table-striped" >';
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>Bill Number</th>";
                        // echo "<th>Member ID</th>";
                        // echo "<th>Reservation ID</th>";
                        echo "<th>Table Number</th>";
                        // echo "<th>Card ID</th>";
                        echo "<th>Payment Method</th>";
                        echo "<th>Amount</th>";
                        echo "<th style='width:13em'>Bill Time</th>";
                        echo "<th style='width:13em'>Payment Time</th>";
                        // echo "<th>Delete</th>";
                        echo "<th>Staff Name</th>";
                        echo "<th>Receipt</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = mysqli_fetch_array($result)) {
                            if ($row['staff_id'] == 0) {
                                continue;
                            }
                            $sql = "SELECT * FROM staffs where staff_id = " . $row['staff_id'];
                            $result2 = mysqli_query($link, $sql);
                            $row2 = mysqli_fetch_assoc($result2);

                            //for amount
                            // Fetch bill details
                            $bill_query = "SELECT * FROM bills WHERE bill_id = '$row[bill_id]'";
                            $bill_result = mysqli_query($link, $bill_query);
                            $bill_data = mysqli_fetch_assoc($bill_result);


                            // Fetch items for the given bill_id
                            $items_query = "SELECT bi.*, m.item_name, m.item_price FROM bill_items bi
                            JOIN menu m ON bi.item_id = m.item_id
                            WHERE bi.bill_id = '$row[bill_id]'";
                            $items_result = mysqli_query($link, $items_query);

                            $cart_total = 0;

                            while ($item_row = mysqli_fetch_assoc($items_result)) {
                                $item_name = $item_row['item_name'];
                                $item_price = $item_row['item_price'];
                                $quantity = $item_row['quantity'];
                                $total = $item_price * $quantity;
                                $cart_total += $total;
                            }
                            echo "<tr>";
                            echo "<td>" . $row['bill_id'] . "</td>";
                            // echo "<td>" . $row['member_id'] . "</td>";
                            // echo "<td>" . $row['reservation_id'] . "</td>";
                            echo "<td>" . $row['table_id'] . "</td>";
                            // echo "<td>" . $row['card_id'] . "</td>";
                            echo "<td>" . $row['payment_method'] . "</td>";
                            echo "<td>" . "NPR " . $cart_total . "</td>";
                            echo "<td>" . $row['bill_time'] . "</td>";
                            echo "<td>" . $row['payment_time'] . "</td>";
                            // echo "<td>";
                            // echo '<a href="../billsCrud/deleteBill.php?id='. $row['bill_id'] .'" title="Delete Record" data-toggle="tooltip" onclick="return confirm(\'Are you sure you want to delete this bill? This action is unrecoverable. \')"><span class="fa fa-trash text-black"></span></a>';
                            // echo "</td>";
                            echo "<td>" . $row2['staff_name'] . "</td>";
                            echo "<td>";
                            echo '<a href="../posBackend/receipt.php?bill_id=' . $row['bill_id'] . '" title="Receipt" data-toggle="tooltip"><span class="fa fa-receipt text-black"></span></a>';
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                    } else {
                        echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close connection
                mysqli_close($link);
                ?>
            </div>
        </div>
    </div>
</div>

<?php include '../inc/dashFooter.php'; ?>