<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../vendor/bootstrap-5.0.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <title>Faculty Scheduling</title>
    <style>
    </style>
</head>
<body>

    <div class="progresspopup">
        
        <div class="progress">
            <div id="progress-bar" class="progress-bar progress-bar-striped bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                <span id="progress-text">0%</span>
            </div>
        </div>
        <div id="outputstatus" class="outputstatus"></div>
        
    </div>
    

    <?php
    while (ob_get_level()) {
        ob_end_flush();
    }
    $command = escapeshellcmd('python .././facultybacktracking2.py ' . $subjectid . ' ' . $facultyid);
    $handle = popen($command, 'r');


    if ($handle) {
        while (!feof($handle)) {
            $line = fgets($handle);
            if ($line !== false) {
                if (preg_match('/(\d+\.\d+%)\s*:\s*(.*)/', $line, $matches)) {
                    $percentage = $matches[1];
                    $message = $matches[2];
                    $percentage = str_replace('%', '', $percentage);

                    ?>
                    <script>
                        
                        
                        document.getElementById('outputstatus').innerText = "<?php echo $message; ?>\n";
                    </script>
                    <script>
                        var percentage = <?php echo json_encode($percentage); ?>;
                        document.getElementById('progress-bar').style.width = '<?php echo $percentage; ?>%';
                        document.getElementById('progress-bar').setAttribute('aria-valuenow', percentage);
                        document.getElementById('progress-bar').setAttribute('aria-valuenow', '<?php echo $percentage; ?>');
                        document.getElementById('progress-text').innerText = '<?php echo $percentage; ?>%';

                        if (percentage >= 100) {
                            window.location.href = './schedule.php';
                        }
                    </script>
                    <?php
                } else {
                    ?>
                    <script>
                        //document.getElementById('outputstatus').innerText += <?php echo json_encode($line); ?>;
                    </script>
                    <?php
                }
                
                flush();
            }
        }
        pclose($handle);
    } else {
        echo "Unable to execute the Python script.";
    }
    ?>
</body>
</html>
