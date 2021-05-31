<?php

namespace App\Command;

use App\Factory\AuthorFactory;
use App\Factory\ProductFactory;
use App\Factory\PublisherFactory;
use App\Helper\CsvTrait;
use App\Helper\EanValidatorInterface;
use App\Helper\ProductsTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CsvImportCommand extends Command
{
    use CsvTrait;
    use ProductsTrait;

    const HEADER = ['name', 'ean', 'authors', 'publishers', 'stock', 'price'];
    const PATH_FILE = '%kernel.root_dir%/../data/';

    protected static $defaultName = 'app:csv-import';
    protected static string $defaultDescription = 'Import CSV file directly to Database.';

    private EntityManagerInterface $em;
    private EanValidatorInterface $eanValidator;

    private int $totalItems = 0;
    private int $numberOfInvalidEan = 0;
    private array $propertyChanges = [];
    private AuthorFactory $authorFactory;
    private PublisherFactory $publisherFactory;
    private ProductFactory $productFactory;

    public function __construct(
        EntityManagerInterface $em,
        EanValidatorInterface $eanValidator,
        AuthorFactory $authorFactory,
        PublisherFactory $publisherFactory,
        ProductFactory $productFactory
    )
    {
        $this->em = $em;
        $this->eanValidator = $eanValidator;
        $this->authorFactory = $authorFactory;
        $this->publisherFactory = $publisherFactory;
        $this->productFactory = $productFactory;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('file', InputArgument::REQUIRED, 'CSV file (example: products.csv). The csv file must be in the location: "project_path/data/" (example: project/data/products.csv)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Attempting to import the feed..');

        //Read file
        $data = $this->readCsvFile($input->getArgument('file'));
        $this->totalItems = iterator_count($data['items']);

        //Valid header
        $diffHeader = $this->checkHeader($data['header']);
        if (count($diffHeader) > 0) {
            $io->error(sprintf('Required key(s) not found: "%s" in the csv file header', implode(', ', $diffHeader)));
            return Command::FAILURE;
        }

        $io->progressStart($this->totalItems);
        $io->newLine();

        // Iterate all rows and add|update item to DB
        foreach ($data['items'] as $row => $item) {
            // Valid EAN
            if (!$this->eanValidator->isValid($item['ean'], 13)) {
                $io->writeln(sprintf('<error>ERROR: Row: %d, value: %s - EAN-13 is not valid</error>', $row, $item['ean']));
                $this->numberOfInvalidEan++;
            }

            // Add author(s) to DB and return array Author object(s)
            $authors = $this->authors($item['authors']);

            // Add publisher to DB and return Publisher object
            $publisher = $this->publisher($item['publishers']);

            // Add|update product to DB
            $productParams = [
                'name' => $item['name'],
                'ean' => $item['ean'],
                'stock' => $item['stock'],
                'price' => $item['price'],
                'publisher' => $publisher,
                'authors' => $authors
            ];
            $this->product($productParams);

            $io->progressAdvance();
        }

        $io->progressFinish();
        $this->summary($io);

        $io->success('Import CSV file complete!');
        return Command::SUCCESS;
    }

    private function summary($io): void
    {
        $numberOfValidItems = $this->totalItems - $this->numberOfInvalidEan;

        foreach ($this->getPropertyChanges() as $change) {
            $io->warning([
                sprintf('Update product: EAN %s, property "%s"', $change['ean'], $change['property']),
                sprintf('Old: %s', $change['old']),
                sprintf('New: %s', $change['new'])
            ]);
        }

        $io->writeln('<info>Table summary:</info>');
        $io->table(
            ['Title', 'Value'],
            [
                ['Total rows', $this->totalItems],
                ['Valid rows', $numberOfValidItems],
                ['Error EAN', $this->numberOfInvalidEan],
                ['Row(s) property update(s)', count($this->getPropertyChanges())]
            ]
        );
    }
}