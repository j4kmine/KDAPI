<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        
         
            if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
                 $message ="error connecting API";
                 return response()->json(["error" => $message]);
            }
             $message ="error connecting API";
            if($exception->getStatusCode() == "401" ){
                $message = "You are not logged in, e.g. using a valid access token";
            }else if($exception->getStatusCode() == "403"){
                $message = "You are authenticated but do not have access to what you are trying to do";
            }else if($exception->getStatusCode() == "404"){
                $message = "The resource you are requesting does not exist";
            }else if($exception->getStatusCode() == "405"){
                $message = "The request type is not allowed, e.g. /users is a resource and POST /users is a valid action but PUT /users is not.";
            }else if($exception->getStatusCode() == "422"){
                $message = "The request and the format is valid, however the request was unable to process. For instance when sent data does not pass validation tests.";
            }else if($exception->getStatusCode() == "500"){
                $message = "An error occured on the server which was not the consumer's fault.n";
            }
            return response()->json(["error" => $message]);
        
        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
      
        return response()->json(['error' => 'Unauthenticated.'], 401);
        

     
    }
}
