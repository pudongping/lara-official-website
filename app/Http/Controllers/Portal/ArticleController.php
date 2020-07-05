<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Article\ArticleRepository;
use App\Models\Article\Article;

class ArticleController extends Controller
{

    protected $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->init();
        $this->articleRepository = $articleRepository;
    }

    public function index(Request $request)
    {
        $data = $this->articleRepository->getPortalList($request);
        return $this->response->send($data);
    }

    public function show(Article $article)
    {
        return $this->response->send($article);
    }

}
