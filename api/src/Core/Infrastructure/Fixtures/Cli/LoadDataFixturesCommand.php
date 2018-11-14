<?php

declare(strict_types=1);

namespace Core\Infrastructure\Fixtures\Cli;

use Fidry\AliceDataFixtures\LoaderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

final class LoadDataFixturesCommand extends Command
{
    protected static $defaultName = 'app:fixtures:load';

    private $loader;

    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Load fixtures into your database.')
            ->addArgument(
                'path',
                InputArgument::REQUIRED,
                'Path to the directory containing the fixtures files.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $path = $input->getArgument('path');

        if (!\is_string($path)) {
            throw new \InvalidArgumentException('Argument "path" must be a string.');
        }

        $fixturesFiles = $this->resolvePath($path);

        $this->loader->load($fixturesFiles);
    }

    private function resolvePath(string $path): array
    {
        if (!file_exists($path)) {
            throw new InvalidArgumentException(sprintf('Directory "%s" does not exist.', $path));
        }

        if (!is_dir($path)) {
            throw new InvalidArgumentException(sprintf('"%s" is not a directory.', $path));
        }

        return iterator_to_array(
            Finder::create()
                ->files()
                ->in($path)
                ->name('*.yaml')
                ->name('*.php')
                ->name('*.json')
                ->getIterator()
        );
    }
}
