<?php

namespace App\Domain\Presenter;

use App\Domain\Response\ChatResponse;

/**
 * Interface ChatPresenterInterface
 * @package App\Domain\Presenter
 */
interface ChatPresenterInterface
{

    /**
     * @param ChatResponse $chatResponse
     * @return mixed
     */
    public function present(ChatResponse $chatResponse): void;
}
