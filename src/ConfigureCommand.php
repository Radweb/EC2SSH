<?php

namespace Radweb\EC2SSH;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ConfigureCommand extends Command
{
    /**
     * @var Configuration
     */
    private $configuration;

    public function __construct(Configuration $configuration, $name = null)
    {
        parent::__construct($name);
        $this->configuration = $configuration;
    }

    protected function configure()
    {
        $this->setName('config')->setDescription('Setup your AWS credentials');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $regions = ['eu-west-1', 'us-east-1'];

        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $required = function ($value) {
            if ( ! trim($value)) {
                throw new \Exception('Must not be empty');
            }

            return $value;
        };

        $validRegion = function ($value) use ($regions) {
            if ( ! in_array($value, $regions)) {
                throw new \Exception('Not a valid region');
            }

            return $value;
        };

        $question = (new Question('Access Key: '))->setValidator($required);
        $accessKey = $helper->ask($input, $output, $question);

        $question = (new Question('Secret Key: '))->setValidator($required)->setHidden(true)->setHiddenFallback(true);
        $secretKey = $helper->ask($input, $output, $question);

        $question = (new Question('Default Region: '))->setValidator($validRegion)->setAutocompleterValues($regions);
        $region = $helper->ask($input, $output, $question);

        $this->configuration->write(compact('accessKey', 'secretKey', 'region'));

        $output->writeln('Credentials Saved.');
    }
}