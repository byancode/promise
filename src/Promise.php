<?php
namespace Byancode;

use Exception;

class Promise
{
    public static function create(callable $activator)
    {
        return new self($activator);
    }
    const RESOLVE_ID = 'd1044cdf-1bf9-5fb7-b03d-dfe1c6ed657b';
    const REJECT_ID = 'd1c8ddfa-7645-5b1d-98c3-d185312e927b';

    protected $id;
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

    public function resolve($data = null)
    {
        foreach ($this->then as $callback) {
            $data = $callback($data);
        }
        throw new Exception(self::RESOLVE_ID);
    }

    public function reject(string $message = null)
    {
        $callback = $this->catch ?? function () {};
        $callback($this->message = $message);
        $this->catch = null;
        throw new Exception(self::REJECT_ID);
    }

    public function run()
    {
        $this->runned = true;
        try {
            $activator = $this->activator ?? function () {};
            $activator([$this, 'resolve'], [$this, 'reject']);
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            if ($message === self::REJECT_ID) {
                $callback = $this->catch ?? function () {};
                $callback($message);
            } elseif ($message !== self::RESOLVE_ID) {
                throw $exception;
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