<?php
include './src/Promise.php';

use Byancode\Promise;

Promise::create(function ($resolve, $reject) {
    $resolve('es un error');
})->then(function ($data) {
    echo $data;
})->catch(function ($message) {
    echo $message;
});

Promise::create(function ($resolve, $reject) {
    $resolve('es un error');
})->then(function ($data) {
    echo $data;
})->catch(function ($message) {
    echo $message;
});

echo "testeo\n";