<!doctype html>
<html lang="en">
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/vendor/autoload.php";
$config = parse_ini_file(__DIR__ . '/config.ini');
$config['logPath'] = __DIR__ . '/data/logs/';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$report = new \High\Client\ClientReport($config);
$reportData = $report->getReportData($page);
$devices = $reportData['devices'];
$count = $reportData['count'];
$prepareCount = number_format($count, 0, '.', ' ');
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
            <h1>Devices</h1>

            <p>
                Count devices: <?php echo $prepareCount?>
            </p>
            <p>
                <a target="_blank" class="btn btn-dark" href="/export.php">
                    Export All devices: <?php echo $prepareCount?>
                </a>
            </p>
            <table class="table table-dark">
                <thead>
                <tr>
                    <th scope="col">device_id</th>
                    <th scope="col">Fingerprint</th>
                    <th scope="col">IP</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($devices as $device) :
                    $link = "/details.php?device_id={$device['device_id']}&ip={$device['ip']}";
                ?>
                    <tr>
                        <td>
                            <a href="<?php echo $link?>"><?php echo $device['device_id']?></a>
                        </td>
                        <td><?php echo $device['fingerprint']?></td>
                        <td><?php echo $device['ip']?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="page-item">
                <a class="page-link">Current page: <?php echo $page?> from <?php echo number_format($count / \High\Entity\Report::LIMIT, 0, '.', ' ')?></a>
            </div>
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link"<?php echo $page > 1 ?  'href="?page=' . ($page - 1) . '"' : ''?>">Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1?>">Next</a></li>
                </ul>
            </nav>
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
