<?php

interface IMethods
{
	public function Insert($request, $response, $args);
	public function Update($request, $response, $args);
	public function Delete($request, $response, $args);
	public function ReadAll($request, $response, $args);
	public function ReadOne($request, $response, $args);
}

?>