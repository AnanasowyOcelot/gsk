<?php

class UrlMatcher
{
	protected $context;
	private $routes;

	
	//public function __construct(RouteCollection $routes, RequestContext $context)
	public function __construct(RouteCollection $routes)
	{
		$this->routes = $routes;
		//$this->context = $context;
	}

	

	public function match($pathinfo)
	{
		if ($ret = $this->matchCollection($pathinfo, $this->routes)) 
		{
			return $ret;
		}
	}

	protected function matchCollection($pathinfo, RouteCollection $routes)
	{
		$pathinfo = urldecode($pathinfo);
		
		foreach ($routes->routes as $name => $route) 
		{

		
			$compiledRoute = $route->compile();

			
//			if ('' !== $compiledRoute->getStaticPrefix() && 0 !== strpos($pathinfo, $compiledRoute->getStaticPrefix())) 
//			{
//				continue;
//			}

			if (!preg_match($compiledRoute->getRegex(), $pathinfo, $matches)) 
			{
				continue;
			}

			return array_merge($this->mergeDefaults($matches, $route->getDefaults()), array('router' => $name));
		}
	}

	protected function mergeDefaults($params, $defaults)
	{
		$parameters = $defaults;
		foreach ($params as $key => $value) 
		{
			if (!is_int($key)) 
			{
				$parameters[$key] = rawurldecode($value);
			}
		}

		return $parameters;
	}
}
