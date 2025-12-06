<?php
class PageController extends Controller {
    public function show($page) {
        $this->view('pages/' . $page);
    }
}

