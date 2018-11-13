<?php

namespace JobImporter;

use JobImporter\Abstracts\AbstractImporter;

class Indeed extends AbstractImporter
{
    /**
     * {@inheritDoc}
     */
    protected $jobUri = 'https://www.indeed.co.uk/viewjob?jk={jobKey}';

    /**
     * {@inheritDoc}
     */
    protected $companyUri = 'https://www.indeed.co.uk/cmp/{companyId}/jobs';

    /**
     * {@inheritDoc}
     */
    public function getTitle() : String
    {
        return $this
            ->crawler
            ->filter('.jobsearch-JobComponent > div > h3.jobsearch-JobInfoHeader-title')
            ->text();
    }

    /**
     * {@inheritDoc}
     */
    public function getCompany() : String
    {
        return $this
            ->crawler
            ->filter('.jobsearch-JobComponent > div > div.jobsearch-JobInfoHeader-subtitle > div.jobsearch-InlineCompanyRating > div:first-of-type')
            ->text();
    }

    /**
     * {@inheritDoc}
     */
    public function getLocation() : String
    {
        return $this
            ->crawler
            ->filter('.jobsearch-JobComponent > div > div.jobsearch-JobInfoHeader-subtitle > div.jobsearch-InlineCompanyRating > div:last-of-type')
            ->text();
    }

    /**
     * {@inheritDoc}
     */
    public function getWage() : String
    {
        return $this
            ->crawler
            ->filter('.jobsearch-JobComponent > div.jobsearch-JobMetadataHeader > div.jobsearch-JobMetadataHeader-item')
            ->text();
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription() : String
    {
        return strip_tags(
            $this
                ->crawler
                ->filter('.jobsearch-JobComponent > div.jobsearch-JobComponent-description')
                ->html(),
            '<p><b><strong><i><u><h1><h2><h3><h4>'
        );
    }

    /**
     * {@inheritDoc}
     */
    public function fetchJobsFromPortal() : Array
    {
        return $this
            ->crawler
            ->filter('.cmp-job-entry > .cmp-section > h3 > a.cmp-job-url')
            ->each(function($node) {
                parse_str(parse_url($node->attr('href'))['query'], $query);
                return $query['jk'];
            });
    }
}