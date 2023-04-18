<?php

class Problem extends Controller
{
    /**
     * PAGE: index
     * This method handles the error page that will be shown when a page is not found
     */
    public function index()
    {
        require APP . 'view/problem/index.php';
    }
}
