<?php

declare(strict_types=1);

namespace HyperfExt\Encryption\Command;

use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Contract\ConfigInterface;

class GenKeyCommand extends HyperfCommand
{
    /**
     * The repository instance.
     *
     * @var \Hyperf\Contract\ConfigInterface
     */
    protected $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
        parent::__construct('gen:key');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Create a secret key or key-pair for hyperf-ext/encryption');
    }

    /**
     * Handle the current command.
     */
    public function handle()
    {
        $driverName = $this->choice('Select driver', array_keys($this->config->get('ext-encryption.driver')));

        $key = $this->generateRandomKey($driverName);

        $this->line('<comment>'.$key.'</comment>');
    }

    /**
     * Generate a random key for the application.
     *
     * @param string $driverName
     *
     * @return string
     */
    protected function generateRandomKey(string $driverName)
    {
        $config = $this->config->get("ext-encryption.driver.{$driverName}");
        return call([$config['class'], 'generateKey'], [['options' => $config['options']]]);
    }
}