<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php'; 
require 'PHPMailer/Exception.php';

// Email Configuration

// CONFIGURE YOUR GOOGLE SMTP APP. 

define('SMTP_HOST', 'smtp.gmail.com'); // SMTP host (e.g., Gmail)
define('SMTP_PORT', 587);             // Port (TLS)
define('SMTP_USERNAME', 'YOURMAIL@gmail.com'); // Your email
define('SMTP_PASSWORD', 'YOUR PASSWORD');// Your email password

// Admin and User Emails
$admins = [
    'admin1' => 'admin1@gmail.com',
    'admin2' => 'admin2@gmail.com',
    'admin3' => 'admin3@gmail.com',
];
$users = [
    'user1' => 'USER@gmail.com', // thi sis just nothing for future proferences
];

$live_url = "YOUR URL HERE";

// Helper function to send email
function sendEmail($to, $subject, $body)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = SMTP_PORT;

        $mail->setFrom(SMTP_USERNAME, 'ACI Leave Approval');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
    } catch (Exception $e) {
        error_log("Error sending email: " . $mail->ErrorInfo);
    }
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_form'])) {

    // Predefined correct password
    $correct_password = "acileaveform";
    $submitted_password = $_POST['form_password'];

    if ($submitted_password !== $correct_password) {
        echo "<div><center> <h1>  Incorrect password. Please try again. <br></h1> </center></div>";
        echo "<div><center> <button onclick=\"history.back()\">Go Back</button> </center></div>";
        exit;
    }

    $senderEmail = $_POST['sender_email'];
    $content     = $_POST['content'];
    $mobile      = $_POST['mobile'];
    $branch      = $_POST['branch'];
    $supervisor  = $_POST['supervisor'];
    $dleave      = $_POST['dleave'];

    // Check if the form is submitted and get the selected option
    $selectedOption1 = isset($_POST['option1']) ? $_POST['option1'] : null;
        if ($selectedOption1 === 'whole_day') {
            $message = "Whole Day";
        } elseif ($selectedOption1 === 'AM') {
            $message = "AM - will report at 1pm";
        } elseif ($selectedOption1 === 'PM - will leave at 12noon') {
            $message = "PM - will leave at 12noon";
        }

    // Check if the form is submitted and get the selected option
    $selectedOption = isset($_POST['option']) ? $_POST['option'] : null;

    // Define messages based on the selected option
    $message = "";
        if ($selectedOption === 'OP1_SL') {
            $message = "You selected Sick Leave. Please attach proof.";
        } elseif ($selectedOption === 'OP2_VL') {
            $message = "You selected Vacation Leave. Please indicate specific details in the Notes section below.";
        } elseif ($selectedOption === 'OP3_SPL') {
            $message = "You selected Solo Parent Leave. Please indicate specific details in the Notes section below.";
        } elseif ($selectedOption === 'OP4_PL') {
            $message = "You selected Paternity Leave. Please attach proof.";
        } elseif ($selectedOption === 'OP5_ML') {
            $message = "You selected Maternity Leave. Please attach proof.";
        } elseif ($selectedOption === 'OP6_VAWCC') {
            $message = "You selected VAWCC Leave.";
        } elseif ($selectedOption === 'OP7_BL') {
            $message = "You selected Bereavement Leave. Please attach proof.";
        } elseif ($selectedOption === 'OP8_MCWA') {
            $message = "You selected Magna Carta of Women Act - Extended. Please attach proof.";
        } elseif ($selectedOption === 'OP9_CL') {
            $message = "You selected Calamity Leave. Please attach proof.";
        }

        $contact   = $_POST['contact'];
        $mo_number = $_POST['mo_number'];
        $notes     = $_POST['notes'];


    $mail_title ="Approval Leave Request $content";

    // Generate links for Admin 1
    $approveLink = "$live_url/script.php?action=approve&stage=1&email=" . urlencode($senderEmail) . "&content=" . urlencode($content) . "&mobile=". urlencode($mobile) . "&branch=". urlencode($branch) . "&supervisor=". urlencode($supervisor) . "&dleave=". urlencode($dleave) . "&selectedOption1=". urlencode($selectedOption1) . "&selectedOption=". urlencode($selectedOption) . "&contact=". urlencode($contact) . "&mo_number=". urlencode($mo_number) . "&notes=". urlencode($notes) . "&decision=approve";
    $denyLink = "$live_url/script.php?action=approve&stage=1&email=" . urlencode($senderEmail) . "&content=" . urlencode($content) . "&mobile=". urlencode($mobile) . "&branch=". urlencode($branch) . "&supervisor=". urlencode($supervisor) . "&dleave=". urlencode($dleave) . "&selectedOption1=". urlencode($selectedOption1) . "&selectedOption=". urlencode($selectedOption) . "&contact=". urlencode($contact) . "&mo_number=". urlencode($mo_number) . "&notes=". urlencode($notes) . "&decision=deny";

    // Email content for Admin 1
    $body = "

       <table>
          <thead><tr><th colspan='2' style='background: #d9d9d9;padding: 5px;text-align: left;font-size: 18px;'> ACI Leave Form </th></tr></thead>
          <tbody>
               <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> New submission from : </th><td style='background-color: #e9e9e945;'>  $senderEmail </td></tr>
               <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Name :                </th><td style='background-color: #e9e9e945;'>  $content</td></tr>
               <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Mobile:               </th><td style='background-color: #e9e9e945;'>  $mobile </td></tr>
               <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Branch:               </th><td style='background-color: #e9e9e945;'>  $branch </td></tr>
               <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Immediate Supervisor: </th><td style='background-color: #e9e9e945;'>  $supervisor </td></tr>
               <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Date of Leave:        </th><td style='background-color: #e9e9e945;'>  $dleave </td></tr>
               <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Time of Leave:        </th><td style='background-color: #e9e9e945;'>  $selectedOption1 </td></tr>
               <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Reason for Leave:     </th><td style='background-color: #e9e9e945;'>  $selectedOption </td></tr>
               <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Contact Person in Case of Emergency: </th><td style='background-color: #e9e9e945;'>  $contact </td></tr>
               <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Emergency Contact Person:  </th><td style='background-color: #e9e9e945;'>  $mo_number </td></tr>
               <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Notes:        </th><td style='background-color: #e9e9e945;'>  $notes </td></tr>
           </tbody>
        </table>
        <hr>
        <div style='display: flex;'><a style='background: #efefef;border: 1px solid #b7b7b7;color: #424242;display: block;font-size: 14px;height: 22px;width: 80px;text-align: center;padding: 12px 0;margin: 0 5px;' href='$approveLink'>Approve</a>  <a style='background: #f5f5f5;border: 1px solid #9b9999;color: #9b9999;display: block;font-size: 14px;height: 22px;width: 80px;text-align: center;padding: 12px 0;margin: 0 5px;' href='$denyLink'>Deny</a></div>
    ";

    sendEmail($admins['admin1'], $mail_title, $body);
    echo "<link href=\"YOUR-URL-HERE/catalog/view/javascript/bootstrap/css/bootstrap.min.css\" rel=\"stylesheet\" media=\"screen\" /> ";
 //   echo "<meta http-equiv=\"refresh\" content=\"5;url=https://google.com\">";
    echo "<div><center> <h1> Form submitted successfully. </h1> </center></div>";


 

 

    exit;
}
?>



<?php
// Handle Approval/Denial Actions
//if ($_GET['action'] === 'approve') {
if (isset($_GET['action']) && $_GET['action'] === 'approve') {
    $stage           = $_GET['stage'];
    $senderEmail     = $_GET['email'];
    $content         = $_GET['content'];
    $decision        = $_GET['decision'];
    $mobile          = $_GET['mobile'];
    $branch          = $_GET['branch'];
    $supervisor      = $_GET['supervisor'];
    $dleave          = $_GET['dleave'];
    $selectedOption1 = $_GET['selectedOption1'];
    $selectedOption  = $_GET['selectedOption'];
    $contact         = $_GET['contact'];
    $mo_number       = $_GET['mo_number'];
    $notes           = $_GET['notes'];

    if ($stage == 1) {
        if ($decision === 'approve') {
            // Forward to Admin 2
            $approveLink = "$live_url/script.php?action=approve&stage=2&email=" . urlencode($senderEmail) . "&content=" . urlencode($content) . "&mobile=". urlencode($mobile) . "&branch=". urlencode($branch) . "&supervisor=". urlencode($supervisor) . "&dleave=". urlencode($dleave) . "&selectedOption1=". urlencode($selectedOption1) . "&selectedOption=". urlencode($selectedOption) . "&contact=". urlencode($contact) . "&mo_number=". urlencode($mo_number) . "&notes=". urlencode($notes) . "&decision=approve";
            $denyLink = "$live_url/script.php?action=approve&stage=2&email=" . urlencode($senderEmail) . "&content=" . urlencode($content) . "&mobile=". urlencode($mobile) . "&branch=". urlencode($branch) . "&supervisor=". urlencode($supervisor) . "&dleave=". urlencode($dleave) . "&selectedOption1=". urlencode($selectedOption1) . "&selectedOption=". urlencode($selectedOption) . "&contact=". urlencode($contact) . "&mo_number=". urlencode($mo_number) . "&notes=". urlencode($notes) . "&decision=deny";


            $body = "
                <p>Approval required for the following submission:</p>
                
                <table>
                <thead><tr><th colspan='2' style='background: #d9d9d9;padding: 5px;text-align: left;font-size: 18px;'> ACI Leave Form </th></tr></thead>
                <tbody>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> New submission from : </th><td style='background-color: #e9e9e945;'>  $senderEmail </td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Name :                </th><td style='background-color: #e9e9e945;'>  $content</td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Mobile:               </th><td style='background-color: #e9e9e945;'>  $mobile </td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Branch:               </th><td style='background-color: #e9e9e945;'>  $branch </td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Immediate Supervisor: </th><td style='background-color: #e9e9e945;'>  $supervisor </td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Date of Leave:        </th><td style='background-color: #e9e9e945;'>  $dleave </td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Time of Leave:        </th><td style='background-color: #e9e9e945;'>  $selectedOption1 </td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Reason for Leave:     </th><td style='background-color: #e9e9e945;'>  $selectedOption </td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Contact Person in Case of Emergency: </th><td style='background-color: #e9e9e945;'>  $contact </td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Emergency Contact Person:  </th><td style='background-color: #e9e9e945;'>  $mo_number </td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Notes:        </th><td style='background-color: #e9e9e945;'>  $notes </td></tr>
                 </tbody>
              </table>
              <hr>


              <div style='display: flex;'><a style='background: #efefef;border: 1px solid #b7b7b7;color: #424242;display: block;font-size: 14px;height: 22px;width: 80px;text-align: center;padding: 12px 0;margin: 0 5px;' href='$approveLink'>Approve</a>  <a style='background: #f5f5f5;border: 1px solid #9b9999;color: #9b9999;display: block;font-size: 14px;height: 22px;width: 80px;text-align: center;padding: 12px 0;margin: 0 5px;' href='$denyLink'>Deny</a></div>";

            sendEmail($admins['admin2'], 'Approval Request - Stage 2', $body);
            echo "Submission approved by Admin 1 and forwarded to Admin 2.";
            exit;
        } else {
            // Notify the sender
            sendEmail($senderEmail, 'Submission Denied', 'Your submission was not approved by Admin 1.');
            echo "Submission denied by Admin 1. your leave is alrady used up.";
            exit;
        }
    } elseif ($stage == 2) {
        if ($decision === 'approve') {
            // Forward to Admin 3
            $approveLink = "$live_url/script.php?action=approve&stage=3&email=" . urlencode($senderEmail) . "&content=" . urlencode($content) . "&mobile=". urlencode($mobile) . "&branch=". urlencode($branch) . "&supervisor=". urlencode($supervisor) . "&dleave=". urlencode($dleave) . "&selectedOption1=". urlencode($selectedOption1) . "&selectedOption=". urlencode($selectedOption) . "&contact=". urlencode($contact) . "&mo_number=". urlencode($mo_number) . "&notes=". urlencode($notes) . "&decision=approve";
            $denyLink = "$live_url/script.php?action=approve&stage=3&email=" . urlencode($senderEmail) . "&content=" . urlencode($content) . "&mobile=". urlencode($mobile) . "&branch=". urlencode($branch) . "&supervisor=". urlencode($supervisor) . "&dleave=". urlencode($dleave) . "&selectedOption1=". urlencode($selectedOption1) . "&selectedOption=". urlencode($selectedOption) . "&contact=". urlencode($contact) . "&mo_number=". urlencode($mo_number) . "&notes=". urlencode($notes) . "&decision=deny";



            $body = "
                <p>This mail was Proved by: Admin 1 and Admin 2, Your Approval is required to complete the process</p>
                <table>
                <thead><tr><th colspan='2' style='background: #d9d9d9;padding: 5px;text-align: left;font-size: 18px;'> ACI Leave Form </th></tr></thead>
                <tbody>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> New submission from : </th><td style='background-color: #e9e9e945;'>  $senderEmail </td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Name :                </th><td style='background-color: #e9e9e945;'>  $content</td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Mobile:               </th><td style='background-color: #e9e9e945;'>  $mobile </td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Branch:               </th><td style='background-color: #e9e9e945;'>  $branch </td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Immediate Supervisor: </th><td style='background-color: #e9e9e945;'>  $supervisor </td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Date of Leave:        </th><td style='background-color: #e9e9e945;'>  $dleave </td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Time of Leave:        </th><td style='background-color: #e9e9e945;'>  $selectedOption1 </td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Reason for Leave:     </th><td style='background-color: #e9e9e945;'>  $selectedOption </td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Contact Person in Case of Emergency: </th><td style='background-color: #e9e9e945;'>  $contact </td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Emergency Contact Person:  </th><td style='background-color: #e9e9e945;'>  $mo_number </td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Notes:        </th><td style='background-color: #e9e9e945;'>  $notes </td></tr>
                 </tbody>
              </table>
              <hr>
                <div><a style='background: #efefef;border: 1px solid #b7b7b7;color: #424242;display: block;font-size: 14px;height: 22px;width: 80px;text-align: center;padding: 12px 0;margin: 0 5px;' href='$approveLink'>Approve</a> <a style='background: #f5f5f5;border: 1px solid #9b9999;color: #9b9999;display: block;font-size: 14px;height: 22px;width: 80px;text-align: center;padding: 12px 0;margin: 0 5px;' href='$denyLink'>Deny</a></div>
            ";

            sendEmail($admins['admin3'], 'Approval Request - Stage 3', $body);
            echo "Submission approved by Admin 2 and forwarded to Admin 3.";
            exit;
        } else {
            // Notify Admin 1 and the Sender
            sendEmail($admins['admin1'], 'Submission Denied by Admin 2', 'The submission was denied by Admin 2.');
            sendEmail($senderEmail, 'Submission Denied', 'Your submission was denied by Admin 2.');
            echo "Submission denied by Admin 2.";
            exit;
        }
    } elseif ($stage == 3) {
        if ($decision === 'approve') {
            // Notify Admin 1 and the Sender
            $body = "
                <p>Your submission has been fully approved:</p>
                <table>
                <thead><tr><th colspan='2' style='background: #d9d9d9;padding: 5px;text-align: left;font-size: 18px;'> ACI Leave Form </th></tr></thead>
                <tbody>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> New submission from : </th><td style='background-color: #e9e9e945;'>  $senderEmail </td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Name :                </th><td style='background-color: #e9e9e945;'>  $content</td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Mobile:               </th><td style='background-color: #e9e9e945;'>  $mobile </td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Branch:               </th><td style='background-color: #e9e9e945;'>  $branch </td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Immediate Supervisor: </th><td style='background-color: #e9e9e945;'>  $supervisor </td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Date of Leave:        </th><td style='background-color: #e9e9e945;'>  $dleave </td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Time of Leave:        </th><td style='background-color: #e9e9e945;'>  $selectedOption1 </td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Reason for Leave:     </th><td style='background-color: #e9e9e945;'>  $selectedOption </td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Contact Person in Case of Emergency: </th><td style='background-color: #e9e9e945;'>  $contact </td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Emergency Contact Person:  </th><td style='background-color: #e9e9e945;'>  $mo_number </td></tr>
                     <tr><th style='width: 35%;font-weight: 500;background-color: #b3b1b145;padding: 5px;text-align: left;'> Notes:        </th><td style='background-color: #e9e9e945;'>  $notes </td></tr>
                 </tbody>
              </table>
              <hr>
            ";

            sendEmail($admins['admin1'], 'Submission Fully Approved', $body);
            sendEmail($senderEmail, 'Submission Fully Approved', $body);
            echo "Submission approved by Admin 3. Notifications sent.";
            exit;
        } else {
            // Notify the sender
            sendEmail($senderEmail, 'Submission Denied', 'Your submission was denied by Admin 3.');
            echo "Submission denied by Admin 3.";
            exit;
        }
    }
}

?>

<!-- HTML Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Form</title>

<style>
@import url(https://fonts.googleapis.com/css?family=Montserrat);

body {
    font-family: montserrat, arial, verdana;
    margin:0;
    background-color: rgb(251, 232, 233);
}
h1 {font-size: 1.5em;}
p {font-size: 12px;}
.head{   
    background-color: rgb(215, 25, 34);
    border-radius: 8px 8px 0 0;
    padding: 15px 20px;
    border-bottom: 1px solid #dfdddd;
    color: white;
}
.main {
    text-align: center;
    box-shadow: 0 0 15px 1px rgb(0 0 0 / 40%);
    width: 500px;
    margin: 0 auto;
    border-radius: 8px;
    }
    .fbody {
    border-right: 1px solid #bd1717;
    border-left: 1px solid #bd1717;
}


#msform {
    text-align: center;
    position: relative;
}
#msform fieldset {
    background: white;
    border: 0 none;
    padding: 20px 30px;
    box-sizing: border-box;
    width: 100%;
    margin: 0 auto;
    position: relative;
}
#msform input, #msform textarea, #msform select{
    padding: 15px;
    border: 1px solid #ccc;
    border-radius: 3px;
    margin-bottom: 10px;
    width: 100%;
    box-sizing: border-box;
    font-family: montserrat;
    color: #2C3E50;
    font-size: 13px;
    color: #878787;
}
button{
    padding: 16px 25px;
    border: 1px solid #bd1010;
    border-radius: 5px;
    font-size: 14px;
    text-transform: uppercase;
    font-weight: 600;
    color: white;
    background-color: #e13131;
}
button:hover {color: #000;background-color: #ffffff;text-decoration: underline;}
.top-bar {
    border-top: 10px solid #B71C1C;
    display: block;
    padding-bottom: 80px;
}
.logo {padding:50px 0 0 0;}
.footer {
    padding: 50px 20px;
    margin-bottom: 50px;
    border-right: 1px solid #bd1717;
    border-left: 1px solid #bd1717;
    border-bottom: 1px solid #bd1717;
    border-radius: 0 0 8px 8px;
    background-color: rgb(215, 25, 34);
    color: #FFF;
    font-size: 13px;
}
.bsubmit {
    padding: 40px;
    background-color: rgb(251, 232, 233);
}
</style>

</head>
<body>
   <span class="top-bar" ></span>
   <div class="main">
        <div class="head">
            <div class="logo">
            <img src="logo.png" alt="">
            </div>
            <h1>Application for Leave Form</h1>
       
        </div>

        <div class="fbody">
            <form id="msform" method="POST">
                    <fieldset>
                    
                        <input type="email" id="sender_email" name="sender_email" placeholder="Your Email:"  required><br><br>
                        <input type="text" id="content" name="content" placeholder="Full Name" required> <br><br>   
                        <input type="text" id="mobile" name="mobile" placeholder=" Mobile Number: " required> <br><br>
                        <input type="text" id="branch" name="branch" placeholder=" Branch: " required> <br><br>
                        <input type="text" id="supervisor" name="supervisor" placeholder=" Immediate Supervisor: " required> <br><br>
                        <input type="text" id="dleave" name="dleave" placeholder="Date of Leave's: " required><br><br>
                        
                        <select id="options" name="option1" required>
                            <option value="" disabled selected>Time Leave*</option>
                            <option value="Whole Day" >Whole Day</option>
                            <option value="AM - will report at 1pm"> AM - will report at 1pm</option>
                            <option value="PM - will leave at 12noon">PM - will leave at 12noon</option>
                        </select>
                        <select id="options" name="option" required>
                            <option value="" disabled selected>Reason for Leave*</option>
                            <option value="Sick Leave" >Sick Leave *Attach Proof</option>
                            <option value="Vacation Leave">Vacation Leave *Indicate Specific Details On The Notes Section Below</option>
                            <option value="Solo Parent Leave">Solo Parent Leave *Indicate Specific Details On The Notes Section Below</option>
                            <option value="Paternity Leave">Paternity Leave *Attach Proof</option>
                            <option value="Maternity Leave">Maternity Leave *Attach Proof</option>
                            <option value="VAWCC">VAWCC Leave</option>
                            <option value="Bereavement">Bereavement Leave *Attach Proof</option>
                            <option value="Magna Carta of Women Act">Magna Carta of Women Act - Extended  *Attach Proof</option>
                            <option value="Calamity">Calamity Leave *Attach Proof</option>
                        </select>
                        <input type="text" id="contact" name="contact" placeholder="Contact Person in Case of Emergency*: " required><br><br>
                        <input type="text" id="mo_number" name="mo_number" placeholder="Mobile Number of Emergency Contact Person*: " required><br><br>
                        <input type="text" id="notes" name="notes" placeholder="Notes*: " required><br><br>
                    </fieldset>
                

                <div class="bsubmit">
                    <button type="submit" name="submit_form">Submit</button>
                </div>
            </form>
        </div>
        <div class="footer">© 2025 yourcompany. All rights reserved.</div>
    </div>
</body>
</html>