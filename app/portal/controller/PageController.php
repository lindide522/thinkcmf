<?php
// +----------------------------------------------------------------------
// | 布光科技 [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.布光科技.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <布光科技@126.com>
// +----------------------------------------------------------------------
namespace app\portal\controller;

use cmf\controller\HomeBaseController;
use app\portal\service\PostService;

class PageController extends HomeBaseController
{
    public function index()
    {
        $postService = new PostService();
        $pageId      = $this->request->param('id', 0, 'intval');
        $page        = $postService->publishedPage($pageId);

        if (empty($page)) {
            abort(404, ' 页面不存在!');
        }

        $this->assign('page', $page);

        $more = $page['more'];

        $tplName = empty($more['template']) ? 'page' : $more['template'];

        return $this->fetch("/$tplName");
    }

}
