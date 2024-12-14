<?php

namespace Dgudovic\Framework\Tests;

class DependencyClass
{

    public function __construct(private readonly SubDependency $subDependency)
    {
    }

    public function getSubDependency(): SubDependency
    {
        return $this->subDependency;
    }
}