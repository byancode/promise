# Simple promise

## Installation

You can install the package via composer:

``` bash
composer require byancode/promise
```

## Usage

``` php
use Byancode\RequestCurl;
use Byancode\Promise;

new Promise(function ($promise) {
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

# Request promise

``` php
Promise::create(function ($promise) {
    RequestCurl::http()->get(
        'http://google.com/',
    )->then(function($content) use ($promise) {
        $content = str_replace('div', 'my-div');
        $promise->resolve($content);
    })->catch(function() use ($promise) {
        $promise->reject('se produjo un error');
    });
})->then(function ($data) {
    print_r($data)
})->catch(function ($message) {
    echo $message;
})->run();

```

# Request and trace promise

``` php
RequestCurl::trace(function(){

    new Promise(function ($promise) {
        RequestCurl::http()->get(
            'http://google.com/',
        )->then(function($content) use ($promise) {
            $promise->wrap(function($promise) use ($content) {
                $content = str_replace('div', 'my-div');
                $promise->resolve($content);
            });
        })->catch(function() use ($promise) {
            $promise->wrap(function($promise) {
                $promise->reject('se produjo un error');
            });
        });
    })->then(function ($data) {
        print_r($data)
    })->catch(function ($message) {
        echo $message;
    });

    new Promise(function ($promise) {
        RequestCurl::http()->get(
            'http://youtube.com/',
        )->then(function($content) use ($promise) {
            $promise->wrap(function($promise) use ($content) {
                $content = str_replace('div', 'my-div');
                $promise->resolve($content);
            }):
        })->catch(function() use ($promise) {
            $promise->wrap(function($promise) {
                $promise->reject('se produjo un error');
            });
        });
    })->then(function ($data) {
        print_r($data)
    })->catch(function ($message) {
        echo $message;
    });

});

```
