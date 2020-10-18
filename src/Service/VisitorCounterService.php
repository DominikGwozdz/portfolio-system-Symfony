<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

class VisitorCounterService
{

    /**
     * @param Request $request
     */
    public function countVisit($request)
    {
        $cookies = $request->cookies->all();
        return $cookies;
    }
}