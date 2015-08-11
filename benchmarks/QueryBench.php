<?php

namespace PHPCR\Benchmark\Query;

use PhpBench\Benchmark\Iteration;
use PHPCR\Benchmark\BaseBench;

/**
 * @group query
 */
class QueryBench extends BaseBench
{
    public function setUp()
    {
        $this->loadDump('large_website.xml');
    }

    /**
     * @paramProvider provideQueries
     * @iterations 5
     * @group query_single_prop
     */
    public function benchQuery(Iteration $iteration)
    {
        $query = $this->getQueryManager()->createQuery($iteration->getParameter('query'), 'JCR-SQL2');
        $query->execute();
    }

    /**
     * @paramProvider provideQueries
     * @iterations 5
     * @group query_single_prop
     */
    public function benchQueryWithNodes(Iteration $iteration)
    {
        $query = $this->getQueryManager()->createQuery($iteration->getParameter('query'), 'JCR-SQL2');
        $results = $query->execute();

        foreach ($results as $result) {
            $result->getNode();
        }
    }

    /**
     * @paramProvider provideQueries
     * @iterations 5
     * @group query_single_prop
     */
    public function benchQueryIterate(Iteration $iteration)
    {
        $query = $this->getQueryManager()->createQuery($iteration->getParameter('query'), 'JCR-SQL2');
        $results = $query->execute();

        foreach ($results as $result) {
            $result->getNode();
        }
    }

    /**
     * @paramProvider provideProperties
     * @iterations 5
     * @group query_variable_props
     */
    public function benchQueryIterateVariableProperties(Iteration $iteration)
    {
        $props = $iteration->getParameter('props');
        $query = $this->getQueryManager()->createQuery(sprintf(
            'SELECT %s FROM [nt:unstructured] WHERE valves IS NOT NULL'
        , implode(', ', $props)), 'JCR-SQL2');

        $results = $query->execute();

        foreach ($results as $row) {
            foreach ($props as $prop) {
                $row->getValue($prop);
            }
        }
    }

    public function provideQueries()
    {
        return array(
            array(
                'query' => 'SELECT valves FROM [nt:unstructured]',
            ),
        );
    }

    public function provideProperties()
    {
        return array(
            array(
                'props' => array('valves'),
            ),
            array(
                'props' => array('valves', 'thistles'),
            ),
            array(
                'props' => array('valves', 'thistles', 'toppings', 'troikas'),
            ),
            array(
                'props' => array('valves', 'thistles', 'toppings', 'troikas', 'underrate', 'worksheets'),
            ),
        );
    }
}
