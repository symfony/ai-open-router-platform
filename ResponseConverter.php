<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\AI\Platform\Bridge\OpenRouter;

use Symfony\AI\Platform\Exception\RuntimeException;
use Symfony\AI\Platform\Model;
use Symfony\AI\Platform\Response\RawResponseInterface;
use Symfony\AI\Platform\Response\ResponseInterface;
use Symfony\AI\Platform\Response\TextResponse;
use Symfony\AI\Platform\ResponseConverterInterface;

/**
 * @author rglozman
 */
final readonly class ResponseConverter implements ResponseConverterInterface
{
    public function supports(Model $model): bool
    {
        return true;
    }

    public function convert(RawResponseInterface $response, array $options = []): ResponseInterface
    {
        $data = $response->getRawData();

        if (!isset($data['choices'][0]['message'])) {
            throw new RuntimeException('Response does not contain message');
        }

        if (!isset($data['choices'][0]['message']['content'])) {
            throw new RuntimeException('Message does not contain content');
        }

        return new TextResponse($data['choices'][0]['message']['content']);
    }
}
