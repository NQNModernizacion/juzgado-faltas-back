<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AppInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    private $projectName;

    private $projectUrl;

    /**
     * Execute the console command.
     */
    public function handle()
    {

        if (! File::exists(base_path('.env')) && File::exists(base_path('.env.example'))) {
            File::copy(base_path('.env.example'), base_path('.env'));
            $this->info('.env creado desde .env.example');
        }


        if (env('APP_CREATED')) {
            $this->warn('El proyecto ya fue creado');

            return Command::SUCCESS;
        }

        $path = base_path('.env');
        $enviroment = 'local';
        if (file_exists('/var/www/.replica') && !file_exists('/var/www/.production')) {
            $enviroment = 'replica';
        } elseif (!file_exists('/var/www/.replica') && file_exists('/var/www/.production')) {
            $enviroment = 'production';
        }

        // 5) Generar APP_KEY (no depende de DB)
        $this->callSilent('key:generate', ['--force' => true]);


        if ($enviroment == 'replica') {
            /* Obtenemos el nombre del proyecto por al nombre de rootPath */
            $this->projectName = $this->getProjectName();
            $this->setEnv($path, $this->projectName, 'APP_NAME');
            //seteo de enviroment en local
            $this->setEnv($path, 'replica', 'APP_ENV');
            //seteo del debug en true
            $this->setEnv($path, 'true', 'APP_DEBUG');

            $this->projectUrl = "https://webservicereplica.muninqn.gov.ar/{$this->projectName}/";
            $this->setEnv($path, $this->projectUrl, 'APP_URL');

            $db = str_replace('-', '', $this->projectName);
            //$this->setEnv($path, mb_strtolower($this->projectName), 'DB_CONNECTION');
            $this->setEnv($path, $db, 'DB_DATABASE');
            $this->setEnv($path, '128.53.80.131', 'DB_HOST');
            $this->setEnv($path, "user{$db}", 'DB_USERNAME');
            $this->setEnv($path, 'user' . ucfirst($db) . '.2020', 'DB_PASSWORD');

            $this->setEnv($path, 'https://webservicereplica.muninqn.gov.ar/admin/', 'BASE_ADMIN_URL');

            $this->setEnv($path, "/mnt/serverdata/projects_files/{$this->projectName}/", 'STORAGE_PATH');
            $this->setEnv($path, "/mnt/serverdata/webRenaper/", 'RENAPER_PATH');

            $this->setEnv($path, 'true', 'APP_CREATED');

            $secret = Str::random(60);
            $this->setEnv($path, $secret, 'SECRET');

            //$this->showInfo();

            return Command::SUCCESS;
        }
        if ($enviroment == 'production') {
            $this->projectName = $this->getProjectName();
            $this->setEnv($path, $this->projectName, 'APP_NAME');
            //seteo del enviroment en production
            $this->setEnv($path, 'production', 'APP_ENV');
            //seteo del debug en false
            $this->setEnv($path, 'false', 'APP_DEBUG');
            $this->projectUrl = "https://webservice.muninqn.gov.ar/{$this->projectName}/";
            $this->setEnv($path, $this->projectUrl, 'APP_URL');

            $hash = Hash::make(env('APP_KEY'));
            $pass = mb_substr($hash, -13);
            $random_position = rand(3, 11);
            $pass_array = mb_str_split($pass);
            array_splice($pass_array, $random_position, 0, '.');
            $pass = implode($pass_array);

            $db = str_replace('-', '', $this->projectName);
            //$this->setEnv($path, mb_strtolower($this->projectName), 'DB_CONNECTION');
            $this->setEnv($path, $db, 'DB_DATABASE');
            $this->setEnv($path, '128.53.1.31', 'DB_HOST');
            $this->setEnv($path, "user{$db}", 'DB_USERNAME');
            $this->setEnv($path, $pass, 'DB_PASSWORD');

            $this->setEnv($path, 'https://webservice.muninqn.gov.ar/admin/', 'BASE_ADMIN_URL');

            $this->setEnv($path, "/mnt/serverdata/projects_files/{$this->projectName}/", 'STORAGE_PATH');
            $this->setEnv($path, "/mnt/serverdata/webRenaper/", 'RENAPER_PATH');

            $this->setEnv($path, 'true', 'APP_CREATED');

            $secret = Str::random(60);
            $this->setEnv($path, $secret, 'SECRET');

            return Command::SUCCESS;
        }

        /* Obtenemos el nombre del proyecto por al nombre de rootPath */
        $this->projectName = $this->getProjectName();
        $this->setEnv($path, $this->projectName, 'APP_NAME');
        //seteo de enviroment en local
        $this->setEnv($path, 'local', 'APP_ENV');
        //seteo del debug en true
        $this->setEnv($path, 'true', 'APP_DEBUG');

        $this->projectUrl = 'http://' . $this->projectName . '.test/';
        $this->setEnv($path, $this->projectUrl, 'APP_URL');

        $db = str_replace('-', '', $this->projectName);
        //$this->setEnv($path, mb_strtolower($this->projectName), 'DB_CONNECTION');
        $this->setEnv($path, $db, 'DB_DATABASE');
        $this->setEnv($path, 'localhost', 'DB_HOST');
        $this->setEnv($path, 'root', 'DB_USERNAME');
        $this->setEnv($path, '', 'DB_PASSWORD');

        $this->setEnv($path, 'https://webservicereplica.muninqn.gov.ar/admin/', 'BASE_ADMIN_URL');

        $this->setEnv($path, '', 'STORAGE_PATH');
        $this->setEnv($path, '', 'RENAPER_PATH');

        $this->setEnv($path, 'true', 'APP_CREATED');

        $secret = Str::random(60);
        $this->setEnv($path, $secret, 'SECRET');

        //Ampliar tiempo de sesion
        $this->setEnv($path, 3600, 'SESSION_LIFETIME');


        return self::SUCCESS;
    }

    private function setEnv($path, $value, $key)
    {
        file_put_contents($path, str_replace(
            "{$key}=" . env($key),
            "{$key}=" . $value,
            file_get_contents($path)
        ));
    }

    private function getProjectName()
    {
        if (mb_strtoupper(mb_substr(PHP_OS, 0, 3)) === 'WIN') {
            $projectName = explode('\\', base_path());
        } else {
            $projectName = explode('/', base_path());
        }

        return end($projectName);
    }
}
