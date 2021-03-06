<?php
/**
 * Entity of StatisticsTable
 *
 * @category Entity
 * @package  Website
 * @author   Raphael St-Arnaud <am21830@ens.etsmtl.ca>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GPL v3
 * @link     https://github.com/MaisonLogicielLibre/Site
 */
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Entity of OrganizationsTable
 *
 * @category Entity
 * @package  Website
 * @author   Raphael St-Arnaud <am21830@ens.etsmtl.ca>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GPL v3
 * @link     https://github.com/MaisonLogicielLibre/Site
 */
class Statistic extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $accessible = [
        '*' => true,
        'id' => false,
    ];

    /**
     * Get the number of commits
     *
     * @return int nbCommits
     */
    public function getCommits()
    {
        return $this->_properties['commits'];
    }

    /**
     * Get the number of pull requests
     *
     * @return int nbPullRequests
     */
    public function getPullRequests()
    {
        return $this->_properties['pull_requests_open'] + $this->_properties['pull_requests_close'];
    }

    /**
     * Get the number of pull requests - open
     *
     * @return int nbPullRequestsOpen
     */
    public function getPullRequestsOpen()
    {
        return $this->_properties['pull_requests_open'];
    }

    /**
     * Get the number of pull requests - close
     *
     * @return int nbPullRequestsClose
     */
    public function getPullRequestsClose()
    {
        return $this->_properties['pull_requests_close'];
    }

    /**
     * Get the number of issues
     *
     * @return int nbIssues
     */
    public function getIssues()
    {
        return $this->_properties['issues_open'] + $this->_properties['issues_close'];
    }

    /**
     * Get the number of issues - open
     *
     * @return int nbIssuesOpen
     */
    public function getIssuesOpen()
    {
        return $this->_properties['issues_open'];
    }

    /**
     * Get the number of issues - close
     *
     * @return int nbissuesClose
     */
    public function getIssuesClose()
    {
        return $this->_properties['issues_close'];
    }

    /**
     * Get the total contribution
     *
     * @return int contribution
     */
    public function getContribution()
    {
        $prs = $this->_properties['pull_requests_open'] + $this->_properties['pull_requests_close'];
        $issues = $this->_properties['issues_open'] + $this->_properties['issues_close'];
        $commits = $this->_properties['commits'];

        $contribution = $prs + $issues + $commits;

        return $contribution;
    }

    /**
     * Get the date of the contribution
     *
     * @return datetime contribution
     */
    public function getContributionDate()
    {
        $date = $this->_properties['date'];
        return $date->month . "/" . $date->day;
    }
}
