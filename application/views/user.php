<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User's View</title>
</head>
<body>
<?php
$result = $params['result'][0];
echo '<table style="border-collapse: collapse">';
echo '<tr>';
foreach ($result as $key => $value) {
    echo '<td style="border: 2px dotted greenyellow">' . $key . '</td>';
}
echo '</tr>';

foreach ($params['result'] as $key => $value) {
    echo '<tr>';
    foreach ($value as $field => $item) {
        echo '<td style="border: 2px dotted greenyellow">' . $item . '</td>';
    }
    echo '</tr>';
}

?>

</body>
</html>