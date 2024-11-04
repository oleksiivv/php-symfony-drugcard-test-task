<?php

namespace App\Command;

use App\System\Parser\Factory\ProductsParserFactory;
use App\System\Service\ProductWriteService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:parse-products')]
class ParseProductsCommand extends Command
{
    public function __construct(
        private readonly ProductsParserFactory $productsParserFactory,
        private readonly ProductWriteService $productWriteService,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Parse products from the store');

        $this->addArgument('store', null, 'Store name');
        $this->addArgument('output', null, 'Output storage type');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $result = $this->getProducts($input->getArgument('store'));
        $output->writeln('Products parsed successfully: ' . count($result));

        $this->saveResult($result, $input->getArgument('output'));
        $output->writeln('Products saved successfully');

        return Command::SUCCESS;
    }

    private function getProducts(?string $store): array
    {
        $parser = $this->productsParserFactory->create($store);

        return $parser->parse();
    }

    private function saveResult(array $products, ?string $outputStorage): void
    {
        $this->productWriteService->save($products, $outputStorage);
    }
}