<?php

declare(strict_types=1);

namespace Ray\Di;

use PDO;

class FakePdoModule extends AbstractModule
{
    protected function configure()
    {
        $this->bind(PDO::class)->in(Scope::SINGLETON);
        $this->bind(PDO::class)->toConstructor(PDO::class, 'dsn=pdo_dsn');
        $this->bind()->annotatedWith('pdo_dsn')->toInstance('sqlite::memory:');
    }
}
