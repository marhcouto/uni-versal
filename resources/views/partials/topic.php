<?php
$request = Request::create('/topics', 'GET');
$response = Route::dispatch($request);
$responseBody = $response->getOriginalContent();
?>