<?php
require_once('db.php');
require '../vendor/PHPMailer-master/src/PHPMailer.php';
require '../vendor/PHPMailer-master/src/SMTP.php';
require '../vendor/PHPMailer-master/src/Exception.php';
require_once '../vendor/dompdf/autoload.inc.php';
use Dompdf\Dompdf; 
use Dompdf\Options;
require_once '../classes/schedule.php'; 
require_once '../classes/curriculum.php'; 



class Email {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function emailnewfaculty($email, $fullname, $username, $passwordlol) {
        $schedule = new Schedule($this->pdo);
        $curriculum = new Curriculum($this->pdo);

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
            $mail->addAddress($email, $fullname);

            $mail->isHTML(true);
            $mail->Subject = 'Your Account is Available';
            $mail->Body = '
                <html>
                <head>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f7f7f7;
                            margin: 0;
                            padding: 0;
                        }
                        .email-container {
                            max-width: 600px;
                            margin: 20px auto;
                            background-color: white;
                            border-radius: 10px;
                            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                            overflow: hidden;
                        }
                        .email-header {
                            background-color: #4CAF50;
                            color: white;
                            padding: 15px;
                            text-align: center;
                            font-size: 24px;
                        }
                        .email-body {
                            padding: 20px;
                            font-size: 16px;
                            color: #333;
                        }
                        .email-footer {
                            text-align: center;
                            padding: 15px;
                            font-size: 12px;
                            color: #777;
                            background-color: #f2f2f2;
                            border-top: 1px solid #ddd;
                        }
                        p {
                            margin: 10px 0;
                        }
                    </style>
                </head>
                <body>
                    <div class="email-container">
                        <div class="email-header">Your Account</div>
                        <div class="email-body">
                            <p>Dear ' . htmlspecialchars($fullname) . ',</p>
                            <p>Please log in using the credentials below to complete your profile setup.</p>
                            <p><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>
                            <p><strong>Password:</strong> ' . htmlspecialchars($passwordlol) . '</p>
                        </div>
                        <div class="email-footer">
                            Best regards,<br>
                            SchedAI
                        </div>
                    </div>
                </body>
                </html>
            ';

            $mail->AltBody = 'Mr. Garciano, Your Account is now available for login. Please log in to your account to view the details. Best regards, SchedAI.';

            $mail->send();
            echo 'Message has been sent';
            return True;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return False;
        }

    }
    
    public function emailfacultyschedule($email, $fullname, $facultyid, $calendarid) {
        global $schedule;
        global $curriculum;

        $facultyschedule = $schedule->filteredschedulesfaculty($facultyid, $calendarid);
        $calendarinfo=$curriculum->calendarinfo($calendarid);
        $html = '
        <h3>' . htmlspecialchars($fullname) . ' Schedule for S.Y ' . htmlspecialchars($calendarinfo['name']) . ' ' . ($calendarinfo['sem'] == 1 ? '1st sem' : ($calendarinfo['sem'] == 2 ? '2nd sem' : 'Unknown semester')) . '</h3>
        <table border="1" cellpadding="5" cellspacing="0">
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
            <tbody>';
        
        foreach ($facultyschedule as $facultyschedules) {
            $html .= '
            <tr>
                <td>' . htmlspecialchars($facultyschedules['subjectcode']) . '</td>
                <td>' . htmlspecialchars($facultyschedules['subjectname']) . '</td>
                <td>' . htmlspecialchars($facultyschedules['subjecttype']) . '</td>
                <td>' . htmlspecialchars($facultyschedules['subjectunit']) . '</td>
                <td>' . htmlspecialchars($facultyschedules['abbreviation']) . ' ' . htmlspecialchars($facultyschedules['yearlvl']) . '' . htmlspecialchars($facultyschedules['section']) . '</td>
                <td>' . (function() use ($facultyschedules) {
                    if (!empty($facultyschedules['starttime']) && !empty($facultyschedules['endtime'])) {
                        return htmlspecialchars(date("g:i A", strtotime($facultyschedules['starttime']))) . ' - ' . htmlspecialchars(date("g:i A", strtotime($facultyschedules['endtime'])));
                    } else {
                        return 'Invalid time';
                    }
                })() . '</td>
                <td>' . htmlspecialchars($facultyschedules['day']) . '</td>
                <td>' . htmlspecialchars($facultyschedules['roomname']) . '</td>
            </tr>';
        }
    
        $html .= '</tbody></table>';

    
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);
        
        // Load HTML content
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    

        $baseFilePath = '../downloadables/schedule_' . $facultyid . $calendarid;
        $pdfFilePath = $baseFilePath . '.pdf';
        $counter = 1;

        // Check if the file exists and modify the filename if it does
        while (file_exists($pdfFilePath)) {
            $pdfFilePath = $baseFilePath . '_' . $counter . '.pdf';
            $counter++;
        }
        file_put_contents($pdfFilePath, $dompdf->output());
        $counterPart = $counter > 1 ? '_' . ($counter - 1) : '';
        $pdfdownloadlink = 'https://schedai.online/schedai/downloadables/schedule_' . $facultyid . $calendarid . $counterPart . '.pdf';
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
            $mail->addAddress($email, $fullname);
    
            $mail->isHTML(true);
            $mail->Subject = 'Your Schedule is Now Available';
            $mail->Body = '
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
                            <p>Dear ' . htmlspecialchars($fullname) . ',</p>
                            <p>Your schedule is now ready. You can view the details below:</p>
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
                                <tbody>';
    
            // Add the same table rows as before
            foreach ($facultyschedule as $facultyschedules) {
                $mail->Body .= '
                <tr>
                    <td>' . htmlspecialchars($facultyschedules['subjectcode']) . '</td>
                    <td>' . htmlspecialchars($facultyschedules['subjectname']) . '</td>
                    <td>' . htmlspecialchars($facultyschedules['subjecttype']) . '</td>
                    <td>' . htmlspecialchars($facultyschedules['subjectunit']) . '</td>
                    <td>' . htmlspecialchars($facultyschedules['abbreviation']) . ' ' . htmlspecialchars($facultyschedules['yearlvl']) . '' . htmlspecialchars($facultyschedules['section']) . '</td>
                    <td>' . (function() use ($facultyschedules) {
                        if (!empty($facultyschedules['starttime']) && !empty($facultyschedules['endtime'])) {
                            return htmlspecialchars(date("g:i A", strtotime($facultyschedules['starttime']))) . ' - ' . htmlspecialchars(date("g:i A", strtotime($facultyschedules['endtime'])));
                        } else {
                            return 'Invalid time';
                        }
                    })() . '</td>
                    <td>' . htmlspecialchars($facultyschedules['day']) . '</td>
                    <td>' . htmlspecialchars($facultyschedules['roomname']) . '</td>
                </tr>';
            }
    
            $mail->Body .= '
                                </tbody>
                            </table>
                            
                            <p><a href="' . htmlspecialchars($pdfdownloadlink) . '" style="display: inline-block; padding: 5px 10px; background-color: #4CAF50; color: white; text-align: center; text-decoration: none; border-radius: 5px;">Download as PDF</a></p>
                            <p>Please <a href="https://schedai.online" style="color: green; text-decoration: underline;">log in</a> to your account to view further details.</p>
                        </div>
                        <div class="email-footer">
                            Best regards, <br>
                            SchedAI
                        </div>
                    </div>
                </body>
                </html>';
    
            $mail->AltBody = 'Dear ' . htmlspecialchars($fullname) . ', Your schedule is now available for review. Please log in to your account to view the details. Best regards, SchedAI.';
    
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    
    public function addtimepreference($facultyid, $day, $starttime,$endtime){
        $sql ="INSERT INTO facultypreferences (facultyid, day, starttime, endtime) VALUES (:facultyid, :day, :starttime, :endtime)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':day', $day);
        $stmt->bindParam(':facultyid', $facultyid);
        $stmt->bindParam(':starttime', $starttime);
        $stmt->bindParam(':endtime', $endtime);
        return $stmt->execute();
    }
    public function edittimepreference($facultyid, $day, $starttime, $endtime) {
        $sql = "UPDATE facultypreferences SET starttime = :starttime, endtime = :endtime WHERE facultyid = :facultyid AND day = :day";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':day', $day);
        $stmt->bindParam(':facultyid', $facultyid);
        $stmt->bindParam(':starttime', $starttime);
        $stmt->bindParam(':endtime', $endtime);
        return $stmt->execute();
    }
    public function deletetimepreference($facultyid, $day){
        $sql = "DELETE FROM facultypreferences WHERE facultyid =:facultyid AND day=:day";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':facultyid', $facultyid);
        $stmt->bindParam(':day', $day);
        return $stmt->execute();
    }
    public function editfacultyinfo($fname, $lname, $mname, $contactno, $bday, $gender, $type, $startdate, $teachinghours, $highestdegree, $facultyid) {
        $sql = "UPDATE faculty SET fname = :fname, lname = :lname, mname = :mname, contactno = :contactno, bday = :bday, gender = :gender, type = :type, startdate = :startdate, teachinghours = :teachinghours, rank = :rank WHERE id = :facultyid";
        $stmt = $this->pdo->prepare($sql);
    
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':mname', $mname);
        $stmt->bindParam(':contactno', $contactno);
        $stmt->bindParam(':bday', $bday);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':startdate', $startdate);
        $stmt->bindParam(':teachinghours', $teachinghours);
        $stmt->bindParam(':rank', $highestdegree);
        $stmt->bindParam(':facultyid', $facultyid);
    
        return $stmt->execute();
    }
    
    
    public function resetfacultysubject($facultyid){
        $sql = "DELETE FROM facultysubject WHERE facultyid = :facultyid";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':facultyid', $facultyid);
        return $stmt->execute();
    }

    

    public function getroombyid($id) {
        $sql = "SELECT * FROM rooms WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateroom($id, $name, $capacity) {
        $sql = "UPDATE rooms SET name = :name, capacity = :capacity WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':capacity' => $capacity,
            ':id' => $id
        ]);
    }

    public function deletefaculty($id) {
        $sql = "DELETE FROM faculty WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function getfacultyinfo($id) {
        $sql = "SELECT * FROM faculty WHERE id=:id";
        $stmt = $this->pdo->prepare($sql); 
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
        $stmt->execute(); 
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getallfaculty() {
        $sql = "SELECT *,faculty.id AS facultyid, department.name AS departmentname FROM faculty JOIN department ON department.id = faculty.departmentid";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getallfacultycollege($collegeid) {
        $sql = "SELECT *, faculty.id AS facultyid, department.name AS departmentname 
                FROM faculty 
                JOIN department ON department.id = faculty.departmentid 
                WHERE department.collegeid = :collegeid";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['collegeid' => $collegeid]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getallfacultydepartment($departmentid) {
        $sql = "SELECT *, faculty.id AS facultyid, department.name AS departmentname 
                FROM faculty 
                JOIN department ON department.id = faculty.departmentid 
                WHERE department.id = :departmentid";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['departmentid' => $departmentid]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function countallfaculty() {
        $sql = "SELECT count(*) from faculty";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchColumn();
    }
    public function getfacultysubjects($facultyid) {
        $sql = "SELECT * FROM facultysubject WHERE facultyid=:facultyid";
        $stmt = $this->pdo->prepare($sql); 
        $stmt->bindParam(':facultyid', $facultyid, PDO::PARAM_INT); 
        $stmt->execute(); 
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
    public function getfacultydaytime($facultyid) {
        $sql = "SELECT * FROM facultypreferences WHERE facultyid=:facultyid";
        $stmt = $this->pdo->prepare($sql); 
        $stmt->bindParam(':facultyid', $facultyid, PDO::PARAM_INT); 
        $stmt->execute(); 
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
    public function collegefaculty($collegeid) {
        $sql = "SELECT *, faculty.id AS facultyid FROM faculty JOIN department ON department.id=faculty.departmentid WHERE department.collegeid=:collegeid";
        $stmt = $this->pdo->prepare($sql); 
        $stmt->bindParam(':collegeid', $collegeid, PDO::PARAM_INT); 
        $stmt->execute(); 
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
    public function departmentfaculty($departmentid) {
        $sql = "SELECT *, faculty.id AS facultyid FROM faculty JOIN department ON department.id=faculty.departmentid WHERE department.id=:departmentid";
        $stmt = $this->pdo->prepare($sql); 
        $stmt->bindParam(':departmentid', $departmentid, PDO::PARAM_INT); 
        $stmt->execute(); 
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
    public function changepass($facultyid, $oldpass, $newpass) {
       
    
        $sql = "SELECT password FROM faculty WHERE id = :facultyid";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':facultyid', $facultyid, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
            $currenthashedpassword = $result['password'];

            if (password_verify($oldpass, $currenthashedpassword)) {
                $hashednewpass = password_hash($newpass, PASSWORD_DEFAULT);
    
                $updateSql = "UPDATE faculty SET password = :newpass WHERE id = :facultyid";
                $updateStmt = $this->pdo->prepare($updateSql);
                $updateStmt->bindParam(':newpass', $hashednewpass);
                $updateStmt->bindParam(':facultyid', $facultyid, PDO::PARAM_INT);
    
                if ($updateStmt->execute()) {
                    return true; 
                } else {
                    return false; 
                }
            } else {
                return false; 
            }
        } else {
            return false; 
        }
    }
    
    
}
?>
