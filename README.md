# Simple promise

## Installation

You can install the package via composer:

``` bash
composer require byancode/promise
```

## Usage

``` php
new Byancode\Promise(function ($promise) {
    if (true) {
        echo 'hola mundo';
        $promise->resolve([
            'success' => true
        ]);
        echo 'esto no se imprime';
    } else {
        echo 'se produjo un error';
        $promise->reject('es un error');
        echo 'esto no se imprime';
    }
})->then(function ($data) {
    print_r($data)
})->catch(function ($message) {
    echo $message;
});
```
