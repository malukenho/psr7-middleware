<?php
/**
 * prooph (http://getprooph.org/)
 *
 * @see       https://github.com/prooph/psr7-middleware for the canonical source repository
 * @copyright Copyright (c) 2016 prooph software GmbH (http://prooph-software.com/)
 * @license   https://github.com/prooph/psr7-middleware/blob/master/LICENSE New BSD License
 */

namespace Prooph\Psr7Middleware\Container;

use Interop\Config\ConfigurationTrait;
use Interop\Config\ProvidesDefaultOptions;
use Interop\Config\RequiresMandatoryOptions;
use Interop\Container\ContainerInterface;
use Prooph\Psr7Middleware\MessageMiddleware;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\EventBus;
use Prooph\ServiceBus\QueryBus;

final class MessageMiddlewareFactory extends AbstractMiddlewareFactory
    implements ProvidesDefaultOptions, RequiresMandatoryOptions
{
    use ConfigurationTrait;

    /**
     * @param string $configId
     */
    public function __construct($configId = 'message')
    {
        parent::__construct($configId);
    }

    /**
     * Create service.
     *
     * @param ContainerInterface $container
     * @return MessageMiddleware
     */
    public function __invoke(ContainerInterface $container)
    {
        $options = $this->options($container->get('config'), $this->configId);

        return new MessageMiddleware(
            $container->get($options['command_bus']),
            $container->get($options['query_bus']),
            $container->get($options['event_bus']),
            $container->get($options['message_factory']),
            $container->get($options['response_strategy'])
        );
    }

    /**
     * @interitdoc
     */
    public function defaultOptions()
    {
        return [
            'command_bus' => CommandBus::class,
            'event_bus' => EventBus::class,
            'query_bus' => QueryBus::class,
        ];
    }

    /**
     * @interitdoc
     */
    public function mandatoryOptions()
    {
        return ['message_factory', 'response_strategy'];
    }
}
