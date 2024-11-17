<?php

namespace App\UserInterface\Presenter;

use Messenger\Domain\Presenter\CreateDiscussionPresenterInterface;
use Messenger\Domain\Response\CreateDiscussionResponse;
use App\UserInterface\ViewModel\CreateDiscussionViewModel;
use Symfony\Component\HttpFoundation\Request;

class CreateDiscussionPresenter implements CreateDiscussionPresenterInterface
{
    private Request $request;
    private CreateDiscussionViewModel $viewModel;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function present(CreateDiscussionResponse $response): void
    {
        $this->viewModel = new CreateDiscussionViewModel($response->getDiscussion());
        $this->request->getSession()->getFlashBag()->add('success', 'Discussion created.');
    }

    public function getViewModel(): CreateDiscussionViewModel
    {
        return $this->viewModel;
    }

}
