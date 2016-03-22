<?php

//$game = include 'round.php';

$game = json_decode(file_get_contents("round.json"), true);

include 'template.php';
