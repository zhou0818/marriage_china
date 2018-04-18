<?php
/**
 * Created by PhpStorm.
 * User: zhou
 * Date: 2018/4/16
 * Time: 17:09
 */

namespace App\Observers;

use App\Models\Article;

class ArticleObserver
{
    public function saving(Article $article)
    {
        $article->excerpt = make_excerpt($article->body);
    }
}