<?php 
require('instamojo.php');
$host = "localhost";
$user = "root";
$password = "";
$dbname = "pravartak";
$api = new Instamojo\Instamojo('829bf2c4ad05a6103ce2a5397824eaec', 'ae56b9383e137974aa997d01444eb3ea');

$name = $_POST["uname"];
$email = $_POST["email"];
$phone = $_POST["phone"];
$addr = $_POST["addr"];
$pan = $_POST["pan"];
$amount = $_POST["amount"];
$interest = $_POST["interest"];

echo $phone.$amount.$interest;

$cnn = mysqli_connect ( $host, $user, $password, $dbname );
$sql = "INSERT INTO donor(name, email, phone, addr, pan, amount, interest, paymentstatus) VALUES 
('".$name."','".$email."','".$phone."','".$addr."','".$pan."','".$amount."','".$interest."',1)";
// $sql = "INSERT INTO test (name)
// VALUES ('".$name."')";

$result = mysqli_query ( $cnn, $sql );
if ($result == false) {
    echo "Error: " . mysqli_error($cnn);
} else {
    echo "successfully inserted";
    try {
        $response = $api->paymentRequestCreate(array(
            "purpose" => $name,
            "amount" => $amount,
            "buyer_name" => $name,
            "phone" => $phone,
            "send_email" => false,
            "email" => $email,
            "redirect_url" => "http://www.google.com/"
            ));
        //print_r($response);
        
        $pay_url = $response['longurl'];
        header("Location: $pay_url");
        exit(); 
    }
    catch (Exception $e) {
        print('Error: ' . $e->getMessage());
    }
    if (mysqli_num_rows ( $result ) < 1) {
        echo "Databases not found.";
    } else {
        echo "<ul>";
        while ( $row = mysqli_fetch_row($result)) {
            echo "<li>$row[0]</li>";
        }
        echo "</ul>";
    }
}

?>