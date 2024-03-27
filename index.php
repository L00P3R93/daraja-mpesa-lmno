<?php
require 'vendor/autoload.php';
use app\mpesa\Init as Mpesa;
$mpesa = new Mpesa();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>STKPush</title>
</head>
<body>
    <h1>STKPush Test</h1>
    <form role="form" method="POST" style="max-width: 400px; margin-top: 30px; margin-bottom: 30px;">
        <label for="phoneNo">Phone Number</label>
        <input type="text" id="phoneNo" name="phoneNo" placeholder="Phone Number" value="254795702455">
        <br><br>
        <label for="amount">Amount</label>
        <input type="number" id="amount" name="amount" placeholder="Amount" value="10" >
        <br><br>
        <input type="submit" id="simulate" name="simulate" value="SIMULATE">
    </form>

    <div id="result">
        <?php
        if($_POST){
            var_export($_POST);
            echo "<br><br>Response:<br>";
            $phoneNo = $_POST['phoneNo'];
            $amount = $_POST['amount'];
            try{
                $user_params = [
                    "Amount" => intval($amount),
                    "PartyA" => $phoneNo,
                    "PhoneNumber" => $phoneNo
                ];

                $response = $mpesa->stk_push($user_params);

            }catch(\Exception $e){
                $response = $e->getMessage();
            }

            echo json_encode($response);
        }
        ?>
    </div>
</body>
</html>


