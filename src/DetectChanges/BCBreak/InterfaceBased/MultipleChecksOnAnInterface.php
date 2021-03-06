<?php

declare(strict_types=1);

namespace Roave\BackwardCompatibility\DetectChanges\BCBreak\InterfaceBased;

use Roave\BackwardCompatibility\Changes;
use Roave\BetterReflection\Reflection\ReflectionClass;
use function array_reduce;

final class MultipleChecksOnAnInterface implements InterfaceBased
{
    /** @var InterfaceBased[] */
    private $checks;

    public function __construct(InterfaceBased ...$checks)
    {
        $this->checks = $checks;
    }

    public function __invoke(ReflectionClass $fromClass, ReflectionClass $toClass) : Changes
    {
        return array_reduce(
            $this->checks,
            function (Changes $changes, InterfaceBased $check) use ($fromClass, $toClass) : Changes {
                return $changes->mergeWith($check->__invoke($fromClass, $toClass));
            },
            Changes::empty()
        );
    }
}
