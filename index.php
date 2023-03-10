<?php
require_once "helpers.php";

// przypisanie do zmienne adresu url z którego zostaną pobrane dane
$dataUrl = "https://fantasy.premierleague.com/api/bootstrap-static/";
// pobranie danych ze strony i zapisanie ich do zmiennej (na tym etapie jest to w formacie json)
$jsonData = file_get_contents($dataUrl);
// zmian formatu json na tablicę (odkodowanie)
$data = json_decode($jsonData, true);

$teamId = null;
if (isset($_POST['submitTeam']) or isset($_POST['submitPlayer']) or isset($_POST['submitNumber'])) {
    $teamName = $_POST['team'];
    $playerName = $_POST['name'];
    $number = $_POST['number'];
    $teams = $data['teams'];
    foreach ($teams as $team) {
        if ($teamName === $team['name']) { // przypisanie nazwy druzyny do numeru
            $teamId = $team['id'];
        }
    }
    if (!is_null($teamId)) {
        $counter = 0;
        $players = $data['elements'];
        foreach ($players as $player) {  // zliczanie ilosci graczy z druzyny
            if ($player['team'] === $teamId) {
                $counter++;
            }
        }
    }
    $playerFullName = null;
    $players = $data['elements'];
    foreach ($players as $player) { // znalezienie gracza
        if ($playerName === $player['second_name']) {
            $playerFullName = $player['first_name'] . ' ' . $player['second_name'];
            $playerTeam = $player['team'];
            $playerGoals = $player['goals_scored'];
            $playerAssists = $player['assists'];
            $playerOwnGoals = $player['own_goals'];
            $playerYellowCards = $player['yellow_cards'];
            $playerRedCards = $player['red_cards'];
        }
    }
    if (isset($playerFullName)) {
        foreach ($teams as $team) {
            if ($playerTeam === $team['id']) {
                $playerTeam = $team['name'];
            }
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Liga angielska</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <div class="filters">Filtry</div>
    <form method="post">
        <div class="float-left ">Podaj zespół</div>
        <div class="float-left ml-130">Podaj nazwisko gracza</div>
        <div class="float-left ml-60">Podaj numer na koszulce</div> <br>
        <div class="clearfix"></div>
        <div class="rectangle">
            <input type="text" name="team" placeholder="Arsenal" style="border: none; width: 196px; height: 50px;
         text-align: center; background: gainsboro" autocomplete="off">
        </div>
        <div class="rectangle">
            <input type="text" name="name" placeholder="Xhaka" style="border: none; width: 196px; height: 50px;
         text-align: center; background: gainsboro" autocomplete="off">
        </div>
        <div class="rectangle">
            <input type="number" name="number" placeholder="34" style="border: none; width: 196px; height: 50px;
         text-align: center; background: gainsboro; -moz-appearance: textfield;" autocomplete="off">
        </div>
        <div class="clearfix"></div>
        <button type="submit" name="submitTeam" class="rectangle2">Pokaż</button>
        <button type="submit" name="submitPlayer" class="rectangle2">Pokaż</button>
        <button type="submit" name="submitNumber" class="rectangle2">Pokaż</button>
    </form>
    <div class="clearfix"></div> <br> <br>
    <div class="filters">Wyniki</div>
    <div class="stripe"></div> <br>

    <?php
    if (isset($_POST['submitTeam'])) {
        if (!is_null($teamId)) {
            echo '<div class="filters">' . $teamName . ' - skład' . '</div>' .
                '<div class="filters">' . 'Wszystkich graczy:' . ' ' . $counter . '</div>' .
                '<div class="filters">' . 'Bramkarze:' . '</div>';
            vvv($players, $teamId, 1);
            echo '<div class="filters">' . 'Obrońcy:' . '</div>';
            vvv($players, $teamId, 2);
            echo '<div class="filters">' . 'Pomocnicy:' . '</div>';
            vvv($players, $teamId, 3);
            echo '<div class="filters">' . 'Napastnicy:' . '</div>';
            vvv($players, $teamId, 4);
        } else {
            echo 'Nie znaleziono drużyny';
        }
    }
    if (isset($_POST['submitPlayer'])) {
        if (!is_null($playerFullName)) {
            echo
                '<div class="filters">' . $playerFullName . '</div>' .
                'Drużyna: ' . $playerTeam . '<br>' .
                'Gole: ' . $playerGoals . '<br>' .
                'Asysty: ' . $playerAssists . '<br>' .
                'Gole samobójcze: ' . $playerOwnGoals . '<br>' .
                'Żółte kartki: ' . $playerYellowCards . '<br>' .
                'Czerwone kartki: ' . $playerRedCards;
        } else {
            echo 'Nie znaleziono takiego gracza';
        }
    }
    ?>




</div>
</body>
</html>
