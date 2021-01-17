<?php
include './src/Promise.php';

use Byancode\Promise;

Promise::create(function ($resolve, $reject) {
    $resolve('es un error');
    echo 'test' . PHP_EOL;
})->then(function ($data) {
    return 'tast';
})->then(function ($data) {
    echo $data . PHP_EOL;
});

Promise::create(function ($resolve, $reject) {
    $resolve('hola a todos');
})->then(function ($data) {
    echo $data . PHP_EOL;
});