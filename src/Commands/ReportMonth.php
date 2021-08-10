<?php


namespace kriskbx\gtt\Commands;

use Carbon\Carbon;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReportMonth extends BaseCommand
{
    /**
     * Configure.
     */
    protected function configure()
    {
        $this
            ->setName('report:month')
            ->addArgument('month', InputArgument::OPTIONAL, 'Date of the month, defaults to this month.')
            ->addArgument('project', InputArgument::OPTIONAL,
                'Id or project namespace. Defaults to project defined in config.')
            ->setDescription('Get metrics for a month');

        parent::configure();
    }

    /**
     * Execute.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $command = $this->getApplication()->find('report');

        $month           = $input->getArgument('month') ? Carbon::parse($input->getArgument('month')) : Carbon::now();
        $firstDayOfMonth = Carbon::parse($month->format('Y-m-') . '01 00:00:00');
        $lastDayOfMonth  = $firstDayOfMonth->copy()->addMonth();

        $output->writeln("* Querying times from '" . $firstDayOfMonth->format($this->config['dateFormat']) . "' to '" . $lastDayOfMonth->format($this->config['dateFormat']) . "'");

        $arguments = [
            'command' => 'report',
            'project' => $input->getArgument('project'),
            '--from'  => $firstDayOfMonth->format('Y-m-d H:i:s'),
            '--to'    => $lastDayOfMonth->format('Y-m-d H:i:s')
        ];

        $command->run(new ArrayInput(array_merge($arguments, $this->getDefaultArguments($input))), $output);
    }
}