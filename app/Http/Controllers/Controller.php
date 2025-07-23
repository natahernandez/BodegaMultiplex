<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Set page configuration for views
     */
    protected function setPageConfig($title = null, $description = null)
    {
        view()->share([
            'pageTitle' => $title,
            'pageDescription' => $description
        ]);
    }

    /**
     * Set simple page configuration (now the only option)
     */
    protected function setSimplePage($title, $description = null)
    {
        $this->setPageConfig($title, $description);
    }
}
