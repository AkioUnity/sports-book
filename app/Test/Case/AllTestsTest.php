<?php

/**
 * Class AllTestsTest
 */
class AllTestsTest extends CakeTestSuite
{
    /**
     * All tests test suite
     *
     * @return CakeTestSuite
     */
    public static function suite()
    {
        $suite = new CakeTestSuite('All tests');

//        $suite->addTestDirectoryRecursive(TESTS . 'Case');

//        $suite->addTestDirectoryRecursive(APP . DS . 'Plugin/Payments/Test/Case');

        $suite->addTestDirectoryRecursive(APP . DS . 'Plugin/Feeds/Test/Case');

        return $suite;
    }
}