<?php
namespace Byancode;

use Exception;
use Throwable;

class Promise
{
    public static function contain(Throwable $th)
    {
        return in_array($th->getMessage(), [
            self::RESOLVE_ID,
            self::REJECT_ID,
        ]);
    }

    public static function create(callable $activator)
    {
        return new self($activator);
    }

    const RESOLVE_ID = 'd1044cdf-1bf9-5fb7-b03d-dfe1c6ed657b';
    const REJECT_ID = 'd1c8ddfa-7645-5b1d-98c3-d185312e927b';

    protected $then = [];
    protected $catch;
    protected $finally;
    protected $runned = false;
    protected $activator;
    public $message = null;

    public function __construct(callable $activator)
    {
        $this->activator = $activator;
    }

    public function then(callable $callback)
    {
        $this->then[] = $callback;
        return $this;
    }

    function catch (callable $callback) {
        $this->catch = $callback;
        return $this;
    }

    function finally (callable $callback) {
        $this->finally = $callback;
        return $this;
    }
    protected function finalize()
    {
        $callback = $this->finally;
        is_callable($callback) && $callback();
    }
    public function resolve($data = null)
    {
        foreach ($this->then as $callback) {
            $data = $callback($data);
        }
        $this->finalize();
        throw new Exception(self::RESOLVE_ID);
    }

    public function reject(string $message = null)
    {
        $callback = $this->catch ?? function () {};
        $callback($this->message = $message);
        $this->catch = null;
        $this->finalize();
        throw new Exception(self::REJECT_ID);
    }

    public function wrap(callable $activator)
    {
        try {
            $activator($this);
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            if ($message === self::REJECT_ID) {
                $callback = $this->catch ?? function () {};
                $callback($message);
            } elseif ($message !== self::RESOLVE_ID) {
                throw $exception;
            }
        }
    }

    public function resolver($data = null)
    {
        return function () use ($data) {
            $this->wrap(function () use ($data) {
                $args = func_get_args();

                if (isset($data) === true) {
                    array_unshift($args, $data);
                }

                call_user_func_array([$this, 'resolve'], $args);
            });
        };
    }

    public function rejector($message)
    {
        return function () use ($message) {
            $this->wrap(function () use ($message) {
                $this->reject($message);
            });
        };
    }

    public function run()
    {
        $this->runned = true;
        $this->wrap($this->activator);
    }

    public function __destruct()
    {
        if ($this->runned === false) {
            $this->run();
        }
    }
}