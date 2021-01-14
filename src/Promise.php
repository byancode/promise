<?php
namespace Byancode;

use Exception;

class Promise
{
    public static function create(callable $activator)
    {
        return new self($activator);
    }

    protected $id;
    protected $then = [];
    protected $catch;
    protected $finally;
    protected $runned = false;
    protected $activator;

    public function __construct(callable $activator)
    {
        $this->activator = $activator;
        $this->id = 'promise:' . mt_rand(1, 9000000);
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

    public function resolve($data = null)
    {
        foreach ($this->then as $callback) {
            $data = $callback($data);
        }
        throw new Exception($this->id, 8754);
    }

    public function reject($message = null)
    {
        throw new Exception($message ?? 'Promise reject', 8755);
    }

    public function run()
    {
        $this->runned = true;
        $activator = $this->activator ?? function () {};
        try {
            $activator([$this, 'resolve'], [$this, 'reject']);
        } catch (Exception $th) {
            $message = $th->getMessage();
            $callback = $this->catch ?? function () {};
            if ($message !== $this->id) {
                $callback($message);
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
    }
}