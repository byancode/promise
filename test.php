<?php
include './src/Promise.php';

use Byancode\Promise;

Promise::create(function ($promise) {
    $promise->resolve('es un error');
    echo 'test' . PHP_EOL;
})->then(function ($data) {
    return 'tast';
})->then(function ($data) {
    echo $data . PHP_EOL;
});

Promise::create(function ($promise) {
    $promise->resolve('hola a todos');
})->then(function ($data) {
    echo $data . PHP_EOL;
});