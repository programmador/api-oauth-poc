#!/usr/bin/php
<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__."/../config/env.php";

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->run();
echo "\n";