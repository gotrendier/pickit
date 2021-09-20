#!/usr/bin/php
<?php

require __DIR__ . '/../../vendor/autoload.php';

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Process\Process;

/**
 * Class CodeQualityTool
 *
 * This hook will attempt to fix common code standard issues in background/without being aggressive to user
 * It won't cry unless detects errors and isn't able to fix any single one (OR they are syntax errors)
 */
class CodeQualityTool extends Application
{
    private OutputInterface $output;
    private InputInterface $input;

    public function __construct()
    {
        define("APP_ROOT", __DIR__ . '/../../');
        parent::__construct('Code Quality Tool', '0.0.1');
    }

    public function doRun(InputInterface $input, OutputInterface $output): void
    {
        $this->input = $input;
        $this->output = $output;

        $this->updateHook();

        $output->writeln('<fg=white;options=bold;bg=green>Code Quality Tool</fg=white;options=bold;bg=green>');
        $output->writeln('<info>Fetching files</info>');
        $files = $this->getCommittedFiles();

        $output->writeln('<info>Running PHPLint</info>');
        if (!$this->phpLint($files)) {
            throw new Exception('There are some PHP syntax errors!');
        }

        $output->writeln('<info>Checking code style</info>');
        if (!$this->codeStyle($files)) {
            throw new Exception(sprintf('There are coding standards violations!'));
        }

        $output->writeln('<info>Checking code style with PHPCS</info>');
        if (!$this->codeStylePsr($files)) {
            throw new Exception(sprintf('There are PHPCS coding standards violations!'));
        }

        $output->writeln('<info>Checking code mess with PHPMD</info>');
        if (!$this->phPmd($files)) {
            throw new Exception(sprintf('There are PHPMD violations!'));
        }

        // make sure linter changes are sent on current commit
        foreach ($files as $file) {
            exec("cd " . APP_ROOT . " && git add $file");
        }

        $output->writeln('<info>Good job dude!</info>');
    }

    private function updateHook(): void
    {
        copy(APP_ROOT . "pre-commit.php", APP_ROOT . ".git/hooks/pre-commit");
        chmod(APP_ROOT . ".git/hooks/pre-commit", 0775);
    }

    private function getCommittedFiles(): array
    {
        $output = [];
        $files = [];
        $exclude = [];

        exec("git diff --diff-filter=d --name-only HEAD", $output);

        foreach ($output as $file) {
            if (in_array($file, $exclude)) {
                continue;
            }
            if (substr($file, -4) == '.php') {
                $files[] = APP_ROOT . $file;
            }
        }

        return $files;
    }

    private function phpLint($files): bool
    {
        foreach ($files as $file) {
            $process = new Process(['php', '-l', $file]);
            $process->run();

            if (!$process->isSuccessful() || strpos($process->getOutput(), "No syntax errors detected in") === false) {
                $this->output->writeln($file);
                $this->output->writeln(sprintf('<error>%s</error>', trim($process->getOutput())));

                return false;
            }
        }

        return true;
    }

    private function codeStyle(array $files): bool
    {
        foreach ($files as $file) {
            $process = new Process(['php', APP_ROOT . 'vendor/friendsofphp/php-cs-fixer/php-cs-fixer', '--dry-run', '--using-cache=no', '--verbose', 'fix', $file, '--rules=@Symfony,@PSR2,@PhpCsFixer']);
            $process->run();
            $output = trim($process->getOutput());

            if (strpos($output, 'Checked all files') === false) {
                if (empty($output)) {
                    $output = $process->getErrorOutput();
                }

                $this->output->writeln(sprintf('<error>%s - %s</error>', $file, $output));

                return false;
            }
        }
        return true;
    }

    private function codeStylePsr(array $files): bool
    {
        foreach ($files as $file) {
            $process = new Process(['php', APP_ROOT . 'vendor/squizlabs/php_codesniffer/bin/phpcbf', '--standard=PSR12', $file]);
            $process->run();

            if (!$process->isSuccessful() && strpos($process->getOutput(), "A TOTAL OF 0 ERROR") !== false) {
                $this->output->writeln(sprintf('<error>%s</error>', trim($process->getOutput())));

                return false;
            }
        }

        return true;
    }

    private function phPmd(array $files): bool
    {
        foreach ($files as $file) {
            $process = new Process(['php', APP_ROOT . 'vendor/phpmd/phpmd/src/bin/phpmd', $file, 'text', 'controversial']);
            $process->run();

            if (!$process->isSuccessful()) {
                $this->output->writeln($file);
                $this->output->writeln(sprintf('<error>%s</error>', trim($process->getErrorOutput())));
                $this->output->writeln(sprintf('<info>%s</info>', trim($process->getOutput())));
                return false;
            }
        }

        return true;
    }
}

$console = new CodeQualityTool();
$console->run();
