<?php

declare(strict_types=1);

namespace App\Common\Domain\Exception;

use App\Common\Infrastructure\Event\Listener\FormatErrorHttpResponseEventListener;
use Exception;
use Throwable;

class AbstractPublicRenderedException extends Exception
{
    protected string $renderedMessage;

    protected function __construct(
        string $message = '',
        string $renderedMessage = '',
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        $this->renderedMessage = $renderedMessage;
        parent::__construct($message, $code, $previous);
    }

    /**
     * This method must be only used in the EventListener.
     *
     * @see FormatErrorHttpResponseEventListener
     */
    public function render(): void
    {
        $this->message = $this->renderedMessage;
    }
}
