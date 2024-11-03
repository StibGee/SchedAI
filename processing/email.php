<?php
require '../vendor/PHPMailer-master/src/PHPMailer.php';
require '../vendor/PHPMailer-master/src/SMTP.php';
require '../vendor/PHPMailer-master/src/Exception.php';

$mail = new PHPMailer\PHPMailer\PHPMailer();

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com'; 
    $mail->SMTPAuth = true;
    $mail->Username = 'rui@meetneat.online';
    $mail->Password = '!Mayjune123';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('rui@meetneat.online', 'SchedAI');
    $mail->addAddress('quilantangrovic@gmail.com', 'Steve Garciano');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Your Schedule is Now Available';
    $mail->Body    = '
        <html>
        <head>
            <style>
                .email-container {
                    font-family: Arial, sans-serif;
                    color: #333333;
                    background-color: #f7f7f7;
                    padding: 20px;
                    border-radius: 10px;
                }
                .email-header {
                    text-align: center;
                    background-color: #4CAF50;
                    color: white;
                    padding: 10px;
                    font-size: 24px;
                    border-radius: 10px 10px 0 0;
                }
                .email-body {
                    margin: 20px 0;
                    font-size: 16px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                }
                table, th, td {
                    border: 1px solid #ddd;
                }
                th, td {
                    padding: 10px;
                    text-align: left;
                }
                th {
                    background-color: #f2f2f2;
                }
                .email-footer {
                    text-align: center;
                    margin-top: 30px;
                    font-size: 12px;
                    color: #777777;
                }
            </style>
        </head>
        <body>
            <div class="email-container">
                <div class="email-header">Your Schedule</div>
                <div class="email-body">
                    <p>Dear Mr. Garciano,</p>
                    <p>Your schedule is now available. Please find the details below:</p>
                    <table>
                        <thead>
                            <tr>  
                                <th>Subject Code</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th>Unit</th>
                                <th>Year and Section</th>
                                <th>Time</th>
                                <th>Day</th>
                                <th>Room</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>CC 101</td>
                                <td>Intro to Computing 1</td>
                                <td>Lec</td>
                                <td>3</td>
                                <td>1A</td>
                                <td>9:00AM-10:30AM</td>
                                <td>MTh</td>
                                <td>LR1</td>
                            </tr>
                            <tr>
                                <td>CC 101</td>
                                <td>Intro to Computing 1</td>
                                <td>Lec</td>
                                <td>3</td>
                                <td>1B</td>
                                <td>10:30AM-12:00PM</td>
                                <td>MTh</td>
                                <td>LR3</td>
                            </tr>
                            <tr>
                                <td>CS 143</td>
                                <td>DiscreeT Mathematics 1</td>
                                <td>Lab</td>
                                <td>1</td>
                                <td>1A</td>
                                <td>1:00PM-4:00PM</td>
                                <td>S</td>
                                <td>LAB1</td>
                            </tr>
                        </tbody>
                    </table>
                    <p>Please log in to your account to view the full schedule and further details.</p>
                </div>
                <div class="email-footer">
                    Best regards, <br>
                    SchedAI
                </div>
            </div>
        </body>
        </html>
    ';
    $mail->AltBody = 'Mr. Garciano, Your schedule is now available for review. Please log in to your account to view the details. Best regards, SchedAI.';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
