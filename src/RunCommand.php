<?php

namespace Radweb\EC2SSH;

use Aws\Ec2\Ec2Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class RunCommand extends Command
{
    protected function configure()
    {
        $this->setName('run')->setDescription('List all EC2 instances');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
//        $credentials = file_get_contents($_SERVER['HOME'] . '/.aws/credentials');

        $client = new Ec2Client([
          'credentials' => [
            'key' => 'XXX',
            'secret' => 'XXX',
          ],
          'region' => 'eu-west-1',
          'version' => 'latest',
        ]);

        $response = $client->describeInstances();

        $instances = [];

        foreach ($response['Reservations'] as $reservation) {
            foreach ($reservation['Instances'] as $instance) {
                $key = $instance['KeyName'];
                $id = $instance['InstanceId'];
                $dns = $instance['PublicDnsName'];
                $zone = $instance['Placement']['AvailabilityZone'];
                $type = $instance['InstanceType'];
                $name = '';
                $role = '';
                $system = '';

                foreach ($instance['Tags'] as $tag) {
                    if ($tag['Key'] === 'System') {
                        $system = $tag['Value'];
                    }
                    if ($tag['Key'] === 'Name') {
                        $name = $tag['Value'];
                    }
                    if ($tag['Key'] === 'Role') {
                        $role = $tag['Value'];
                    }
                }

                $instances[$role . ' ' . $id] = compact('key', 'id', 'dns', 'zone', 'type', 'name', 'role', 'system');
            }
        }

        ksort($instances);
        $instances = array_values($instances);

        $table = new Table($output);

        $table->setHeaders(['', 'Role', 'ID', 'Size', 'DNS']);

        $lastRole = null;

        foreach ($instances as $i => $instance) {
            if ($instance['role'] !== $lastRole && $lastRole !== null) {
                $table->addRow(new TableSeparator());
            }

            $table->addRow([
              $i + 1,
              '<options=bold>' . $instance['role'] . '</>',
              $instance['id'],
              $instance['type'],
              $instance['dns']
            ]);

            $lastRole = $instance['role'];
        }

        $table->render();

        $question = new Question('Which box to SSH to? ');
        $choice = $this->getHelper('question')->ask($input, $output, $question);

        $instance = $instances[$choice - 1];
        $name = $instance['role'] . ' (' . $instance['id'] . ')';

        $output->writeln('');
        $output->writeln('<info>Connecting to ' . $name . '...</info>');
    }
}