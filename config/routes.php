<?php 

/**
 * Used to define the routes in the system.
 * 
 * A route should be defined with a key matching the URL and an
 * controller#action-to-call method. E.g.:
 * 
 * '/' => 'index#index',
 * '/calendar' => 'calendar#index'
 */
$routes = array(

	'/test' => 'test#index',
	'/' => 'application#index',
	//'/' =>  'application#getTasks',
	'/getTasks' => 'application#getTasks',
	'/createTask' => 'application#createTask',
	'/getTaskById' => 'application#getTaskById',
	'/deleteTask' => 'application#deleteTask',
	'/application' => 'application#login',
	'/applicationRegister' => 'application#register',
	'/logout' => 'application#logout',
	'/editTask' => 'application#editTask',
	'/updateTask' => 'application#updateTask'


);
