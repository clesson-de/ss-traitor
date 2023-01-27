<?php

namespace Clesson\Traitor\Tests;

use SilverStripe\Dev\FunctionalTest;
use Clesson\Traitor\Extensions\TraitorExtension;

/**
 *
 */
class TraitorExtensionTest extends FunctionalTest
{

    protected function setUp(): void
    {
        parent::setUp();


    }

    /**
     * Check that the TraitorExtension ist applied anywhere.
     *
     * @return void
     */
    public function testThatExtensionIsApplied()
    {
        $classesWithExtension = ClassInfo::classesWithExtension(TraitorExtension::class);
        $this->assertGreaterThan(0, count($classesWithExtension));
    }

}
