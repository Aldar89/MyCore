<?php

namespace Core\src;
use Service\CartService;
use Service\Logger\LoggerServiceInterface;
use Service\OrderService;

class App
{
    private array $routes = [];
    private LoggerServiceInterface $loggerService;
    private Container $container;

    public function __construct(LoggerServiceInterface $loggerService, Container $container)
    {
        $this->loggerService = $loggerService;
        $this->container = $container;
    }

    public function run()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        if (isset($this->routes[$requestUri])) {
            $route = $this->routes[$requestUri];

            if (isset($route[$requestMethod])) {
                $controllerClassName =  $route[$requestMethod]['class'];
                $method =$route[$requestMethod]['method'];
                $requestClass = $route[$requestMethod]['request'];


                $class = $this->container->get($controllerClassName);

                try {
                    if (empty($requestClass)){
                    return $class->$method();
                }else{
                    $request = new $requestClass($requestUri, $requestMethod, $_POST);
                    return $class->$method($request);
                }
                } catch (\Throwable $exception) {


                    $this->loggerService->error('Произошла ошибка при обработке ', [
                        'message' => $exception->getMessage(),
                        'file' => $exception->getFile(),
                        'line' => $exception->getLine(),
                    ]);

                    http_response_code(500);
                    require_once './../View/500.php';
                }
                if (empty($requestClass)){
                    return $class->$method();
                }else{
                    $request = new $requestClass($requestUri, $requestMethod, $_POST);
                    return $class->$method($request);
                }
            }
            else {
                echo "Метод $requestMethod не поддерживается для адреса $requestUri";
            }

        }else {
            http_response_code(404);
            require_once "./../View/404.php";
        }

    }

    public function addRoute(string $requestUri,
                             string $requestMethod,
                             string $class,
                             string $method,
                             $requestClass = null):void
    {
        $this->routes[$requestUri][$requestMethod] = [
            'class' => $class,
            'method' => $method,
            'request' => $requestClass];


    }

    public function getRoute(string $route, string $className, string $methodName, $requestClass = null)
    {
        $this->routes[$route]['GET']= [
            'class' => $className,
            'method' => $methodName,
            'request' => $requestClass
        ];
    }
    public function postRoute(string $route, string $className, string $methodName, $requestClass = null)
    {
        $this->routes[$route]['POST']= [
            'class' => $className,
            'method' => $methodName,
            'request' => $requestClass
        ];
    }




}