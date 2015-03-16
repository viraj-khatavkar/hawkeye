<?php namespace Viraj\Hawkeye;

use Illuminate\Console\Command;

class MigrationCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'hawkeye:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a migration for storing uploaded files meta data.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $filesTable = 'hawkeye_table';


        $this->laravel->view->addNamespace('hawkeye', substr(__DIR__, 0, -8).'views');

        $this->line('');

        $message = "A migration that creates file table will be created in app/database/migrations directory";

        $this->comment($message);
        $this->line('');

        if ($this->confirm("Proceed with the migration creation? [Yes|no]")) {
            $this->line('');

            $this->info("Creating migration...");
            if ($this->createMigration($filesTable)) {
                $this->info("Migration successfully created!");
            } else {
                $this->error(
                    "Couldn't create migration.\n Check the write permissions".
                    " within the app/database/migrations directory."
                );
            }

            $this->line('');

        }
    }

    /**
     * Creates migration file according to configuration options.
     *
     * @param string $filesTable
     * @return bool
     */
    protected function createMigration($filesTable)
    {
        $migrationFile = base_path("/database/migrations")."/".date('Y_m_d_His')."_hawkeye_setup_tables.php";

        $data = compact('filesTable');

        $output = $this->laravel->view->make('hawkeye::generators.migration')->with($data)->render();

        if (!file_exists($migrationFile) && $fs = fopen($migrationFile, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            return true;
        }

        return false;
    }
}