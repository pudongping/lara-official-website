<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Article\ArticleRepository;
use App\Http\Requests\Article\ArticleRequest;
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
        $data = $this->articleRepository->getList($request);
        return $this->response->send($data);
    }

    public function store(ArticleRequest $request)
    {
        $data = $this->articleRepository->storage($request);
        return $this->response->send($data);
    }

    public function update(ArticleRequest $request, Article $article)
    {
        $data = $this->articleRepository->modify($request);
        return $this->response->send($data);
    }

    public function show(Article $article)
    {
        return $this->response->send($article);
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return $this->response->send();
    }


}
