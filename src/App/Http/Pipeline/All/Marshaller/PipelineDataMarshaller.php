<?php

declare(strict_types=1);

namespace App\Http\Pipeline\All\Marshaller;

use App\Model\Pipeline\Action\Entity\Action;
use App\Model\Pipeline\Entity\Pipeline;
use Tightenco\Collect\Support\Collection;

class PipelineDataMarshaller
{
    private ActionDataMarshaller $actionDataMarshaller;

    public function __construct(
        ActionDataMarshaller $actionDataMarshaller
    ) {
        $this->actionDataMarshaller = $actionDataMarshaller;
    }

    /**
     * @return array<string, array|int|string>
     */
    public function marshall(Pipeline $pipeline): array
    {
        $actions = Collection::make($pipeline->getActions())
            ->map(fn(Action $action): array => $this->actionDataMarshaller->marshall($action))
        ;

        return [
            'id' => $pipeline->getId(),
            'name' => $pipeline->getName(),
            'actions' => $actions->all(),
        ];
    }
}
