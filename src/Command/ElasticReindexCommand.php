<?php

declare(strict_types=1);

/**
 * This file is a part of Helpee.
 *
 * @author  Kevin Allard <contact@allard-kevin.fr>
 *
 * @license 2018-2019 - Helpee
 */

namespace App\Command;

use App\Elasticsearch\CityIndexer;
use App\Elasticsearch\IndexBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class ElasticReindexCommand.
 */
class ElasticReindexCommand extends Command
{
    protected static $defaultName = 'elastic:reindex';

    private $indexBuilder;
    private $cityIndexer;

    /**
     * ElasticReindexCommand constructor.
     *
     * @param \App\Elasticsearch\IndexBuilder $indexBuilder
     * @param \App\Elasticsearch\CityIndexer  $cityIndexer
     */
    public function __construct(IndexBuilder $indexBuilder, CityIndexer $cityIndexer)
    {
        $this->indexBuilder = $indexBuilder;
        $this->cityIndexer = $cityIndexer;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Rebuild the Index and populate it.')
        ;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $index = $this->indexBuilder->create();

        $io->success('Index created!');

        $this->cityIndexer->indexAllDocuments($index->getName());

        $io->success('Index populated and ready!');
    }
}
