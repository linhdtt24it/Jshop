<?php
class FooterController extends Controller {
    private $footerModel;

    public function __construct() {
        $this->footerModel = $this->model("FooterModel");
    }

    public function load() {
        $data['footer_groups'] = $this->footerModel->getFooterLinks();
        return $data;
    }
}
