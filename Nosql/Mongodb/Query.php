<?php

namespace Smallphp\Nosql\Mongodb;

class Query
{
    /**
     * execute
     */
    public static function execute($manager, $driver, $dbname, $collection)
    {
        if ($driver instanceOf \MongoDB\Driver\Query) {
            return $manager->executeQuery($dbname . '.' . $collection, $driver)->toArray();
        } else if ($driver instanceof \MongoDB\Driver\BulkWrite) {
            $writeConcern = new \MongoDB\Driver\WriteConcern(\MongoDB\Driver\WriteConcern :: MAJORITY, 1000);
            return $manager->executeBulkWrite($dbname . '.' . $collection, $driver, $writeConcern);
        }
        return array();
    }
} 
