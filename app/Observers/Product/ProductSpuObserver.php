<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 2020/3/5
 * Time: 15:18
 */

namespace App\Observers\Product;

use App\Models\Product\ProductSpu;

class ProductSpuObserver
{

    public function saving(ProductSpu $productSpu)
    {
        // 修复 XSS 注入漏洞 （因为目前插件不支持过滤 html5 指定标签，且此处只会用到后台管理，风险不大，故注释掉以下代码）
//        $productSpu->description = clean($productSpu->description, 'spu_description');
    }

}
