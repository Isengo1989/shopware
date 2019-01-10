<?php
/**
 * Shopware 5
 * Copyright (c) shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

use Shopware\Components\Api\Manager;
use Shopware\Components\Api\Resource\Blog;

class Shopware_Controllers_Api_Blogs extends Shopware_Controllers_Api_Rest
{
    /**
     * @var Blog
     */
    protected $resource;

    public function init()
    {
        $this->resource = Manager::getResource('blog');
    }

    /**
     * Get blog list
     *
     * GET /api/blogs/
     *
     * @throws \Shopware\Components\Api\Exception\PrivilegeException
     */
    public function indexAction()
    {
        $request = $this->Request();
        $limit = (int) $request->getParam('limit', 1000);
        $offset = (int) $request->getParam('start', 0);
        $sort = $request->getParam('sort', []);
        $filter = $request->getParam('filter', []);

        $result = $this->resource->getList([17], $offset, $limit, $filter, $sort);

        $view = $this->View();
        $view->assign($result);
        $view->assign('success', true);
    }

    /**
     * Get one blog
     *
     * GET /api/blog/{id}
     */
    public function getAction()
    {
        $id = $this->Request()->getParam('id');

        $category = $this->resource->getOne($id);

        $view = $this->View();
        $view->assign('data', $category);
        $view->assign('success', true);
    }

    /**
     * Create new blog
     *
     * POST /api/categories
     */
    public function postAction()
    {
        $category = $this->resource->create($this->Request()->getPost());

        $location = $this->apiBaseUrl . 'categories/' . $category->getId();
        $data = [
            'id' => $category->getId(),
            'location' => $location,
        ];

        $this->View()->assign(['success' => true, 'data' => $data]);
        $this->Response()->setHeader('Location', $location);
    }

    /**
     * Update blog
     *
     * PUT /api/blog/{id}
     */
    public function putAction()
    {
        $request = $this->Request();
        $id = $request->getParam('id');
        $params = $request->getPost();

        $category = $this->resource->update($id, $params);

        $location = $this->apiBaseUrl . 'categories/' . $category->getId();
        $data = [
            'id' => $category->getId(),
            'location' => $location,
        ];

        $this->View()->assign(['success' => true, 'data' => $data]);
    }

    /**
     * Delete blog
     *
     * DELETE /api/blog/{id}
     */
    public function deleteAction()
    {
        $id = $this->Request()->getParam('id');

        $this->resource->delete($id);

        $this->View()->assign(['success' => true]);
    }
}
