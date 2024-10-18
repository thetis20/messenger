<?php

namespace App\UserInterface\Controller;

use App\Domain\Request\SendRequest;
use App\Domain\UseCase\Send;
use App\UserInterface\Presenter\ChatPresenter;
use App\Domain\UseCase\Chat;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class SendController
{
    public function __invoke(Send $send, Request $request, SerializerInterface $serializer): Response
    {
        $request = $serializer->deserialize($request->getContent(), SendRequest::class, 'json');
        $send->execute($request);
        return new JsonResponse(null, Response::HTTP_CREATED);
    }

}
