<?php


namespace kriskbx\gtt\Api;

/**
 * Class Issues
 * @package kriskbx\gtt\Api
 */
class Issues extends \Gitlab\Api\Issues
{
    /**
     * Overwriting the GitLab API package showComments function, because it doesn't
     * support pagination.
     *
     * @param int $project_id
     * @param int $issue_id
     * @param int $page
     * @param int $per_page
     *
     * @return mixed
     */
    public function showComments($project_id, $issue_id, $page = 1, $per_page = self::PER_PAGE)
    {
        $params = [
            'page'     => $page,
            'per_page' => $per_page
        ];

        return $this->get($this->getProjectPath($project_id, 'issues/' . $this->encodePath($issue_id)) . '/notes',
            $params);
    }
}