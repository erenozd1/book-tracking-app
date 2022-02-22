<?php
namespace Console\App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\HttpClient\HttpClient;

class WeatherCommand extends Command
{
    protected function configure()
    {
        $this->setName('weather')
            ->setDescription('Returns the weather information of the given city.')
            ->addArgument('cityname', InputArgument::REQUIRED, 'Write a city name.');
    }

    protected function execute(InputInterface $input, OutputInterface $output):int
    {
        try {
            $httpClient = HttpClient::create();
            $response = $httpClient->request('GET', 'http://api.weatherapi.com/v1/current.json',
                [
                    'query' => [
                        'key' => '934a9afb5f06486583c134237222202',
                        'q' => $input->getArgument('cityname'),
                    ],
                ]
            );

            $statusCode = $response->getStatusCode();
            $content = $response->getContent();
            $result = json_decode($content, true);
        }catch (\Exception $exception){
            $output->writeln($exception->getMessage());
            return 0;
        }

        $output->writeln(sprintf('Weather in %s:', $input->getArgument('cityname')));
        $output->writeln(sprintf('Temperature is %s, condition is %s ', $result['current']['temp_c'],$result['current']['condition']['text'] ));
        return 0;
    }
}