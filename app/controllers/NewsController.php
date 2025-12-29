<?php
require_once __DIR__ . '/../core/Controller.php';

class NewsController extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => "Tin tức - JSHOP"
        ];
        $this->view('news/index', $data);
    }
    
    public function detail($id)
    {
        $data = [
            'page_title' => "Chi tiết tin tức - JSHOP",
            'news_id' => $id
        ];
        $this->view('news/detail', $data);
    }
}