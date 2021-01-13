# Asynchronous Multi Curl

## Installation

You can install the package via composer:

``` bash
composer require byancode/promise
```

## Usage

``` php
new Byancode\Promise(function ($resolve, $reject) {
    if (true) {
        $resolve([
            'success' => true
        ]);
    } else {
        $reject('es un error');
    }
})->then(function ($data) {
    print_r($data)
})->catch(function ($message) {
    echo $message;
});
```
