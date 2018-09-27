<!doctype html>
<html lang="en">
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/vendor/autoload.php";
$config = parse_ini_file(__DIR__ . '/config.ini');
$config['logPath'] = __DIR__ . '/data/logs/';

$deviceId = (int)$_GET['device_id'];
$ip = (string)$_GET['ip'];
$report = new \High\Client\ClientReport($config);
$data = $report->getDeviceDetails($deviceId, $ip);
?>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Reports</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-lg text-center alert-dark">
            <h1>Device <?php echo $data['fingerprint']?></h1>

            <h6>
                <button class="btn btn-lg btn-info" onclick="window.history.back();"> < Back</button>
            </h6>
            <table class="table table-dark table-responsive">
                <thead>
                <tr>
                <?php foreach ($keys = array_keys($data) as $key) : ?>
                    <th scope="col"><?php echo $key?></th>
                <?php endforeach;?>
                </tr>
                </thead>
                <tbody>
                <tr>
                <?php foreach ($values = array_values($data) as $value) : ?>
                    <td scope="col"><?php echo $value?></td>
                <?php endforeach;?>
                </tbody>
            </table>

            <form class="form-inline" method="post">
                <div class="form-group mb-2">
                    <label for="message">Private message for the device:</label>
                    <input type="text" class="form-control" id="message" name="message" value="">
                </div>
                <button type="submit" class="btn btn-primary mb-2">Send</button>

                <?php
                if (!empty($_POST['message'])) :
                    $flushNewMessageId = $report->pushMessage($deviceId, (string)$_POST['message']); ?>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                        Details
                    </button>

                    <!-- Modal -->
                    <div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="false">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Success</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Successfully! Your message "<?php echo (string)$_POST['message']?> was added to queue. Id: #<?php echo $flushNewMessageId?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>
