<?php

declare(strict_types=1);

namespace Ray\Di;

use ReflectionParameter;

use function assert;
use function class_exists;
use function explode;
use function is_string;
use function preg_match;
use function substr;
use function trim;

final class Name
{
    /**
     * 'Unnamed' name
     */
    public const ANY = '';

    /** @var string */
    private $name = '';

    /**
     * Named database
     *
     * [named => varName][]
     *
     * @var string[]
     */
    private $names = [];

    public function __construct(?string $name = null)
    {
        if ($name !== null) {
            $this->setName($name);
        }
    }

    public function __invoke(ReflectionParameter $parameter): string
    {
        // single variable named binding
        if ($this->name) {
            return $this->name;
        }

        // multiple variable named binding
        if (isset($this->names[$parameter->name])) {
            return $this->names[$parameter->name];
        }

        // ANY match
        if (isset($this->names[self::ANY])) {
            return $this->names[self::ANY];
        }

        // not matched
        return self::ANY;
    }

    private function setName(string $name): void
    {
        // annotation
        if (class_exists($name, false)) {
            $this->name = $name;

            return;
        }

        // single name
        // @Named(name)
        if ($name === self::ANY || preg_match('/^\w+$/', $name)) {
            $this->name = $name;

            return;
        }

        // name list
        // @Named(varName1=name1, varName2=name2)]
        $this->names = $this->parseName($name);
    }

    /**
     * @return array<string, string>
     */
    private function parseName(string $name): array
    {
        $names = [];
        $keyValues = explode(',', $name);
        foreach ($keyValues as $keyValue) {
            $exploded = explode('=', $keyValue);
            if (isset($exploded[1])) {
                [$key, $value] = $exploded;
                assert(is_string($key));
                if (isset($key[0]) && $key[0] === '$') {
                    $key = substr($key, 1);
                }

                $names[trim($key)] = trim($value);
            }
        }

        return $names;
    }
}
