<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/4 0004
 * Time: 下午 4:11
 */

namespace App\Module;

use App\Module\Userinfo\UserinfoHandle;
use App\Module\System\SystemHandle;
use App\Module\Gift\GiftHandle;
use App\Module\Document\DocumentHandle;
use App\Module\Admin\AdminHandle;
use App\Module\Book\BookHandle;
use App\Module\Book\QuestionHandle;
use App\Module\Book\TaskHandle;
use App\Module\Wx\WxHandle;
use App\Module\Rank\RankHandle;
use App\Module\Verify\VerifyHandle;

class user
{
    use UserinfoHandle;
    use SystemHandle;
    use GiftHandle;
    use DocumentHandle;
    use AdminHandle;
    use BookHandle;
    use QuestionHandle;
    use TaskHandle;
    use WxHandle;
    use RankHandle;
    use VerifyHandle;

}