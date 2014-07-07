<?php

class RouteCollection
{
	public  $routes;
	private $resources;
	private $prefix;
	private $parent;

	public function __construct()
	{
		$this->routes = array();
		$this->resources = array();
		$this->prefix = '';
	}

	//================================================================================
	public function __clone()
	{
		foreach ($this->routes as $name => $route) 
		{
			$this->routes[$name] = clone $route;
			if ($route instanceof RouteCollection) 
			{
				$this->routes[$name]->setParent($this);
			}
		}
	}

	//================================================================================
	public function add($name, Route $route)
	{
		if (!preg_match('/^[a-z0-9A-Z_.]+$/', $name)) {
			echo "BLAD 1";
			// throw new \InvalidArgumentException(sprintf('Name "%s" contains non valid characters for a route name.', $name));
		}

		$this->routes[$name] = $route;
	}

	//================================================================================
	public function all()
	{
		$routes = array();
		foreach ($this->routes as $name => $route) 
		{
			if ($route instanceof RouteCollection) 
			{
				$routes = array_merge($routes, $route->all());
			} 
			else 
			{
				$routes[$name] = $route;
			}
		}

		return $routes;
	}

	//================================================================================
	public function get($name)
	{
		// get the latest defined route
		foreach (array_reverse($this->routes) as $routes) 
		{
			if (!$routes instanceof RouteCollection) 
			{
				continue;
			}

			if (null !== $route = $routes->get($name)) {
				return $route;
			}
		}

		if (isset($this->routes[$name])) 
		{
			return $this->routes[$name];
		}
	}

	//================================================================================	
	public function remove($name)
	{
		if (isset($this->routes[$name])) 
		{
			unset($this->routes[$name]);
		}

		foreach ($this->routes as $routes) 
		{
			if ($routes instanceof RouteCollection) 
			{
				$routes->remove($name);
			}
		}
	}

	//================================================================================
	public function addCollection(RouteCollection $collection, $prefix = '')
	{
		$collection->setParent($this);
		$collection->addPrefix($prefix);

		// remove all routes with the same name in all existing collections
		foreach (array_keys($collection->all()) as $name) 
		{
			$this->remove($name);
		}

		$this->routes[] = $collection;
	}

	//================================================================================
	/** Adds a prefix to all routes in the current set.     
               $prefix     An optional prefix to add before each pattern of the route collection     
       	*/
	public function addPrefix($prefix)
	{
		// a prefix must not end with a slash
		$prefix = rtrim($prefix, '/');

		if (!$prefix) 
		{
			return;
		}

		// a prefix must start with a slash
		if ('/' !== $prefix[0]) 
		{
			$prefix = '/'.$prefix;
		}

		$this->prefix = $prefix.$this->prefix;

		foreach ($this->routes as $name => $route) 
		{
			if ($route instanceof RouteCollection) 
			{
				$route->addPrefix($prefix);
			} 
			else 
			{
				$route->setPattern($prefix.$route->getPattern());
			}
		}
	}

	//================================================================================
	public function getPrefix()
	{
		return $this->prefix;
	}

	//================================================================================
}
