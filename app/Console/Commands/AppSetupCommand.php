<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PDOException;

class AppSetupCommand extends Command
{
    /**
     * O nome e a assinatura do comando.
     *
     * @var string
     */
    protected $signature = 'app:setup';

    /**
     * A descrição do comando.
     *
     * @var string
     */
    protected $description = 'Configura a aplicação criando o banco de dados e executando migrações e seeders';

    /**
     * Executa o comando.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Iniciando configuração da aplicação...');
        $this->newLine();

        // Passo 1: Verificar e criar o banco de dados
        if (!$this->createDatabase()) {
            return Command::FAILURE;
        }

        $this->newLine();

        // Passo 2: Executar migrações
        if (!$this->runMigrations()) {
            return Command::FAILURE;
        }

        $this->newLine();

        // Passo 3: Executar seeders
        if (!$this->runSeeders()) {
            return Command::FAILURE;
        }

        $this->newLine();
        $this->info('Configuração concluída com sucesso!');
        $this->info('A aplicação está pronta para uso.');

        return Command::SUCCESS;
    }

    /**
     * Cria o banco de dados se ele não existir.
     *
     * @return bool
     */
    protected function createDatabase(): bool
    {
        $databaseName = config('database.connections.mysql.database');
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        $this->info('Verificando banco de dados...');

        try {
            // Conecta sem selecionar um banco específico
            $connection = new \PDO(
                "mysql:host={$host};port={$port}",
                $username,
                $password
            );

            // Verifica se o banco já existe
            $stmt = $connection->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$databaseName}'");
            $databaseExists = $stmt->fetch() !== false;

            if ($databaseExists) {
                $this->info("O banco de dados '{$databaseName}' já existe. Pulando criação.");
                return true;
            }

            // Cria o banco de dados
            $this->info("Criando banco de dados '{$databaseName}'...");
            $connection->exec("CREATE DATABASE IF NOT EXISTS `{$databaseName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->info("Banco de dados '{$databaseName}' criado com sucesso.");

            return true;
        } catch (PDOException $e) {
            $this->error('Erro ao conectar ao MySQL: ' . $e->getMessage());
            $this->error('Verifique as credenciais no arquivo .env e tente novamente.');
            return false;
        }
    }

    /**
     * Executa as migrações.
     *
     * @return bool
     */
    protected function runMigrations(): bool
    {
        $this->info('Executando migrações...');

        try {
            // Força a reconexão com o banco recém-criado
            config(['database.connections.mysql.database' => config('database.connections.mysql.database')]);

            $this->call('migrate', [
                '--force' => true,
            ]);

            $this->info('Migrações executadas com sucesso.');
            return true;
        } catch (\Exception $e) {
            $this->error('Erro ao executar migrações: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Executa os seeders.
     *
     * @return bool
     */
    protected function runSeeders(): bool
    {
        $this->info('Executando seeders...');

        try {
            // Verifica se a tabela users já existe e está vazia
            if (Schema::hasTable('users')) {
                $usersCount = DB::table('users')->count();

                if ($usersCount > 0) {
                    $this->info('Seeders não executados: a tabela users já contém dados.');
                    $this->info('Se desejar recarregar os seeders, execute: php artisan db:seed --class=DatabaseSeeder');
                    return true;
                }
            }

            $this->call('db:seed', [
                '--force' => true,
            ]);

            $this->info('Seeders executados com sucesso.');
            return true;
        } catch (\Exception $e) {
            $this->error('Erro ao executar seeders: ' . $e->getMessage());
            $this->info('Os seeders podem ser executados posteriormente com: php artisan db:seed');
            return true; // Não falha por causa dos seeders, pois podemos executá-los depois
        }
    }
}