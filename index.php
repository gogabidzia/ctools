<?php

class ContentTools {
    public $request = [];
    public $server = [];
    public $map;
    public $to;

    function __construct () {
        $this->captureRequest();
    }

    private function captureRequest () {
        $this->request = (object)$_REQUEST;
        $this->server  = (object)$_SERVER;
    }

    private function getReferrer () {
        return $this->server->HTTP_REFERER;
    }

    public function save ($key) {
        $filePath = $this->getFilePath($key);
        if (!file_exists($filePath)) {
            return false;
        }
        file_put_contents($filePath, $this->request->{$key});
        return true;
    }

    private function getFilePath ($key) {
        $referer = $this->getReferrer();
        return str_replace($this->map, $this->to, $referer);
    }

    public function setLocationConfig ($map, $to) {
        $this->map = $map;
        $this->to  = $to;
    }
}

function htmlTool () {
    header('Content-Type: application/json');
    $tool = new ContentTools();
    $tool->setLocationConfig('http://localhost/ctools', __DIR__);
    if ($tool->save('html')) {
        return ['status' => "success"];
    }
    return ['status' => "error"];
}

echo json_encode(htmlTool());