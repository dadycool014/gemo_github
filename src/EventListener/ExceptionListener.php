<?php
namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
public function onKernelException(ExceptionEvent $event)
{
// You get the exception object from the received event
$exception = $event->getThrowable();

    switch ($exception->getStatusCode()) {
        case 500 :
            $response = ['code'=>500 ,'message' =>'Internal Error'];
            break ;
        case 404 :
            $response = ['code'=>404 , 'message' =>'Link not Found'];

            break ;

    }
// Customize your response object to display the exception details
$resp  = new JsonResponse(array('message'=>$response['message'],'code'=>$response['code']));
$event->setResponse($resp);
}
}