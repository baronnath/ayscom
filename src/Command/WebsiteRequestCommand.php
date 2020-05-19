<?php

// src/Command/CreateUserCommand.php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpClient\HttpClient;

class WebsiteRequestCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:website-request';
    protected $client;
    protected $iterationLimit;
    protected $urlRegex = '/(https?:\/\/(\S*?\.\S*?))([\s)\[\]{},;"\':<]|\.\s|$)/i';

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Prints all the requests made by a website and the sucesive requested websites.')

            ->addArgument('url', InputArgument::REQUIRED, 'Website URL to analize')
            ->addArgument('iteration', InputArgument::REQUIRED, 'Number of iterations')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command retrieve all the requests made by the website. Specify the limit of iterations.');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $input->getArgument('url');
        $this->iterationLimit = $input->getArgument('iteration');

        $io = new SymfonyStyle($input, $output);

        // Arguments validation
        if($this->iterationLimit <= 0 ){
            $io->error('Iteration number must be an integer larger than 0');
            return 0;
        }
        if(!preg_match($this->urlRegex, $url)){
            $io->error('Email format not valid. Please include http or https protocol as "http://website.com"');
            return 0;
        }

        $this->client = HttpClient::create();

        $io->title('Website Requests');
        $this->printUrls($url, $output);

        return 0;
    }

    protected function printUrls (string $url, OutputInterface $output, $depth = 0)
    {
        $depth++;
        $response = $this->client->request('GET', $url);
        preg_match_all($this->urlRegex, $response->getContent(false), $detectedUrls); // false avoid error when response is an error 

        foreach ($detectedUrls[1] as $key => $url) {
            $indent = str_repeat('    ', $depth);
            $output->writeln($indent. '--- ' .$url);
            if($depth < $this->iterationLimit){
                $this->printUrls($url, $output, $depth);
            }
        }
    }
}
