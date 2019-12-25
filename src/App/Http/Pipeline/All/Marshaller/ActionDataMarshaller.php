<?php

declare(strict_types=1);

namespace App\Http\Pipeline\All\Marshaller;

use App\Model\Pipeline\Action\Entity\Action;

class ActionDataMarshaller
{

    /**
     * @return array<string, int|string>
     */
    public function marshall(Action $action): array
    {
        return [
            'id' => $action->getId(),
            'name' => $action->getName(),
            'type' => $action->getType(),
            'command' => $action->getCommand(),
        ];
    }
}
