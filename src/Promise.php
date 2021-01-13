<?php
namespace Byancode;

class Promise
{
    public static function create(callable $activator)
    {
        return new self($activator);
    }

    const signal = '36c64d72-f55c-5c61-9aad-563f0462261a';
    protected $then = [];
    protected $catch;
    protected $finally;
    protected $runned = false;
    protected $activator;

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

    public function run()
    {
        $this->runned = true;
        $activator = $this->activator ?? function () {};
        try {
            $activator(function ($data) {
                foreach ($this->then as $callback) {
                    $data = $callback($data);
                }
                throw new \Exception(self::signal);
            }, function ($message) {
                throw new \Exception($message);
            });
        } catch (\Exception $th) {
            $callback = $this->catch;
            $message = $th->getMessage();
            if ($message !== self::signal) {
                is_callable($callback) && $callback($message);
            }
        }

        $callback = $this->finally;
        is_callable($callback) && $callback();
    }
    public function __destruct()
    {
        if ($this->runned === false) {
            $this->run();
        }
        $this->catch = $this->finally = $this->activator = null;
        $this->then = [];
    }
}