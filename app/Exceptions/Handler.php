<?php

namespace App\Exceptions;

use App\Traits\APIResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;


class Handler extends ExceptionHandler
{
    use APIResponse;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if($exception instanceof HttpException){
            $code = $exception->getStatusCode();
            $check_message = $exception->getMessage();
            if(!empty($check_message)){
                $message = $exception->getMessage();
                return $this->errorResponse($message, $code);
            }
            else
                $message = Response::$statusTexts[$code];
            return $this->errorResponse($message, $code);
        }
        if($exception instanceof ModelNotFoundException){
            $model = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("Does not Exist Any Instance of {$model} with the given id", Response::HTTP_NOT_FOUND);
        }

        if($exception instanceof AuthorizationException){
            return $this->errorResponse($exception->getMessage(), Response::HTTP_FORBIDDEN);
        }

        if($exception instanceof AuthenticationException){
            return $this->errorResponse($exception->getMessage(), Response::HTTP_UNAUTHORIZED);
        }

        if($exception instanceof ValidationException){
            $error = $exception->validator->errors()->getMessages();
            return $this->errorResponse($error, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

//        if ($exception instanceof  ClientException){
//            $message = $exception->getResponse()->getBody();
//            $code = $exception->getCode();
//            return $this->errorMessage($message, $code);
//        }

        if(env('APP_DEBUG')){
            return parent::render($request, $exception);
        }

        return  $this->errorResponse('UnExpected Error Developer Test. Try again Later', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

}
