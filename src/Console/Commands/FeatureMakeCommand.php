<?php

namespace Novadaemon\Larafeat\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Novadaemon\Larafeat\Generators\FeatureGenerator;
use Novadaemon\Larafeat\Helpers\Str;

class FeatureMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:feature {name : The feature\'s name.} {--p|pest : Create a Pest test for the feature}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Feature';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $title = $this->parseName($this->argument('name'));
            $pest = $this->option('pest');

            $generator = new FeatureGenerator();
            $feature = $generator->generate($title, $pest);

            $this->info(
                'Feature class '.$feature->title.' created successfully.'.
                "\n".
                "\n".
                'Find it at <comment>'.$feature->relativePath.'</comment>'."\n"
            );
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

   /**
    * Parse the feature name.
    *  remove the Feature.php suffix if found
    *  we're adding it ourselves.
    *
    * @param  string  $name
    * @return string
    */
   protected function parseName($name)
   {
       return Str::feature($name);
   }
}
