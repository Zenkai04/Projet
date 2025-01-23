<?php
$router->map('GET', '/restaurant/[i:id]', 'App\Controller\DetailsController@show');