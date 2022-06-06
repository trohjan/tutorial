<?php
/*
 		header("Content-Type: application/json");

     $response = '{
         "ResultCode": 0, 
         "ResultDesc": "Confirmation Received Successfully"
     }';
 
     // DATA
     $mpesaResponse = file_get_contents('php://input');
 
     // log the response
     $logFile = "M_PESAConfirmationResponse.txt";
 
     // write to file
     $log = fopen($logFile, "a");
 
     fwrite($log, $mpesaResponse);
     fclose($log);
 
     echo $response;*/
?>
<?php
  header("Content-Type:application/json");

  $content = file_get_contents('php://input'); //Recieves the response from MPESA as a string

  $res = json_decode($content, false); //Converts the response string to an object

  $dataToLog = array(
      date("Y-m-d H:i:s"), //Date and time
      $res
  ); //Sets up the log format: Date, time and the response
  $data = implode(" - ", $dataToLog);

  $data .= PHP_EOL; //Add an end of line to the transaction log

  file_put_contents('transaction_log', $data, FILE_APPEND);
  $MerchantRequestID = $result->{'MerchantRequestID'};
  $CheckoutRequestID = $result->{'CheckoutRequestID'};
  $ResponseCode = $result->{'ResponseCode'};
  $ResponseDescription = $result->{'ResponseDescription'};


  $host = "172.16.9.139";
        $dbname = "mpesa";
        $username = "root";
        $password = "";

		$param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

        $conn = new mysqli($host, $username, $password, $dbname);
        if ($conn->connect_error){
            die("Connection error : " .$conn->connect_error);
        }else{
            $stmt = $conn->prepare("insert into responses(MerchantRequestID, CheckoutRequestID, ResponseCode, ResponseDescription)
                values(?, ?, ?, ?)");
            $stmt->bind_param("ssss", $MerchantRequestID, $CheckoutRequestID, $ResponseCode, $ResponseDescription);
            $stmt->execute();
            $last_id = $stmt->insert_id;
            
            $stmt->close();
            $conn->close();
        }


?>