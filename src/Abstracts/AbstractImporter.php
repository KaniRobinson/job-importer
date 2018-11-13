<?php

namespace JobImporter\Abstracts;

use Goutte\Client;

abstract class AbstractImporter
{
    /**
     * Goute Client
     *
     * @var Client
     */
    protected $client;

    /**
     * Goute Crawler
     *
     * @var Client
     */
    protected $crawler;

    /**
     * Job URI for Portal
     *
     * @var String
     */
    protected $jobUri = '';

    /**
     * Company URI for Portal
     *
     * @var String
     */
    protected $companyUri = '';

    /**
     * Initialise Goutee Client
     */
    public function __construct()
    {
        $this->client = new Client();
    }
    
    /**
     * Get title from Portal
     *
     * @return String
     */
    abstract public function getTitle() : String;

    /**
     * Get company from Portal
     *
     * @return String
     */
    abstract public function getCompany() : String;

    /**
     * Get location from Portal
     *
     * @return String
     */
    abstract public function getLocation() : String;

    /**
     * Get wages from Portal
     *
     * @return String
     */
    abstract public function getWage() : String;

    /**
     * Get description from Portal
     *
     * @return String
     */
    abstract public function getDescription() : String;

    /**
     * Get jobs from Company from Portal
     *
     * @return Array
     */
    abstract public function fetchJobsFromPortal() : Array;

    /**
     * Where Job ID
     *
     * @param string $jobId
     * @return AbstractImporter
     */
    public function whereJobId(string $jobId) : AbstractImporter
    {
        $this->jobUri = preg_replace("/{(.*)}/", $jobId, $this->jobUri);

        return $this;
    }
    
    /**
     * Fetch back Job
     *
     * @return Array
     */
    public function find() : Array
    {
        $this->crawler = $this->client->request('GET', $this->jobUri);

        return $this
            ->extractElementsFromClass();
    }

    /**
     * Where Company ID
     *
     * @param string $companyId
     * @return AbstractImporter
     */
    public function whereCompanyId(string $companyId) : AbstractImporter
    {
        $this->companyUri = preg_replace("/{(.*)}/", $companyId, $this->companyUri);
        $this->crawler = $this->client->request('GET', $this->companyUri);

        return $this;
    }

    /**
     * Fetch Jobs base on Company ID
     *
     * @return Array
     */
    public function get() : Array
    {
        return array_map(function ($jobId) {
            return $this
                ->whereJobId($jobId)
                ->find();
        }, $this->fetchJobsFromPortal());
    }
    /**
     * Extract Elements from Object
     *
     * @return Array
     */
    protected function extractElementsFromClass() : Array
    {
        $values = [];
        $methods = preg_grep('/^get[A-z]/', get_class_methods($this));

        foreach($methods as $method) {
            $values[str_replace('get', '', strToLower($method))] = $this->$method();
        }
        
        return $values;
    }
}
