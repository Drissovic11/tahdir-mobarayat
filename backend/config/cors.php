<?php
return [
 'paths'=>['api/*'], 'allowed_methods'=>['GET','POST','OPTIONS'],
 'allowed_origins'=>array_filter(array_map('trim',explode(',',env('FRONTEND_URL','http://127.0.0.1:5173')))),
 'allowed_origins_patterns'=>[], 'allowed_headers'=>['Content-Type','Accept','X-Attempt-Token'],
 'exposed_headers'=>[], 'max_age'=>600, 'supports_credentials'=>false,
];
