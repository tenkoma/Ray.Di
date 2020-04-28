<?php

declare(strict_types=1);

namespace Ray\Di;

use Ray\Aop\AbstractMatcher;

class Pointcut extends \Ray\Aop\Pointcut
{
    /**
     * @var array<class-string<\Ray\Aop\MethodInterceptor>>
     */
    public $interceptors;
    /** @noinspection SenselessMethodDuplicationInspection */

    /**
     * Extend Ray\Di version Pointcut
     *
     * We have extended the $interceptor parameters so that you can specify the class name as well as the object
     *
     * @param array<class-string<\Ray\Aop\MethodInterceptor>> $interceptors array of interceptor class name
     */
    public function __construct(AbstractMatcher $classMatcher, AbstractMatcher $methodMatcher, array $interceptors)
    {
        $this->classMatcher = $classMatcher;
        $this->methodMatcher = $methodMatcher;
        $this->interceptors = $interceptors;
    }
}
