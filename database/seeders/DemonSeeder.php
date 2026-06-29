<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Demon;
use App\Models\Fusion;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DemonSeeder extends Seeder
{
    public function run(): void
    {
        // Verifica se o arquivo JSON existe
        $jsonPath = 'demons.json'; // caminho relativo dentro de storage/app

        if (!Storage::exists($jsonPath)) {
            $this->command->warn('Arquivo demons.json não encontrado em storage/app/demons.json');
            $this->command->warn('Pule a população de demônios.');
            return;
        }

        $jsonContent = Storage::get($jsonPath);
        $data = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('Erro ao decodificar JSON: ' . json_last_error_msg());
            return;
        }

        // Limpa as tabelas antes de popular (opcional)
        // DB::table('fusions')->truncate();
        // DB::table('demons')->truncate();

        $this->command->info('Iniciando importação de demônios...');

        // A estrutura do JSON pode variar; aqui assumimos que é um array de demônios
        // com campos como 'name', 'race', 'level', 'stats' (com str, mag, etc.)
        // e 'resists' (com fire, ice, etc.)
        // Ajuste conforme a estrutura real do JSON baixado.

        $demons = [];
        $fusions = [];

        foreach ($data as $demonData) {
            // Mapeamento dos campos
            $demon = [
                'name' => $demonData['name'] ?? 'Unknown',
                'race' => $demonData['race'] ?? 'Unknown',
                'level' => $demonData['level'] ?? 0,
                'strength' => $demonData['stats']['str'] ?? 0,
                'magic' => $demonData['stats']['mag'] ?? 0,
                'vitality' => $demonData['stats']['vit'] ?? 0,
                'agility' => $demonData['stats']['agi'] ?? 0,
                'luck' => $demonData['stats']['luc'] ?? 0,
                'res_fire' => $demonData['resists']['fire'] ?? null,
                'res_ice' => $demonData['resists']['ice'] ?? null,
                'res_elec' => $demonData['resists']['elec'] ?? null,
                'res_force' => $demonData['resists']['force'] ?? null,
                'res_light' => $demonData['resists']['light'] ?? null,
                'res_dark' => $demonData['resists']['dark'] ?? null,
                'image_url' => $demonData['image'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Evita duplicatas pelo nome (assumindo que nome + raça é único)
            $existing = Demon::where('name', $demon['name'])
                            ->where('race', $demon['race'])
                            ->first();

            if (!$existing) {
                $demons[] = $demon;
            }
        }

        // Insere em lotes para performance
        if (!empty($demons)) {
            Demon::insert($demons);
            $this->command->info('Importados ' . count($demons) . ' demônios.');
        } else {
            $this->command->warn('Nenhum novo demônio para importar.');
        }

        // Agora, processar fusões (se existirem no JSON)
        // O JSON pode ter uma seção separada de fusões ou elas podem estar embutidas.
        // Vamos assumir que há uma chave 'fusions' no JSON ou que cada demônio tenha 'fusion' listando receitas.
        // Adapte conforme a estrutura real.

        if (isset($data['fusions'])) {
            $fusionData = $data['fusions'];
            $this->command->info('Processando fusões...');

            foreach ($fusionData as $fusion) {
                // Assume que $fusion tem 'ingredient_a', 'ingredient_b', 'result'
                $demonA = Demon::where('name', $fusion['ingredient_a'])->first();
                $demonB = Demon::where('name', $fusion['ingredient_b'])->first();
                $demonResult = Demon::where('name', $fusion['result'])->first();

                if ($demonA && $demonB && $demonResult) {
                    // Evita duplicatas
                    $existingFusion = Fusion::where('demon_a_id', $demonA->id)
                                            ->where('demon_b_id', $demonB->id)
                                            ->first();
                    if (!$existingFusion) {
                        Fusion::create([
                            'demon_a_id' => $demonA->id,
                            'demon_b_id' => $demonB->id,
                            'demon_result_id' => $demonResult->id,
                        ]);
                    }
                }
            }
            $this->command->info('Fusões processadas.');
        } else {
            $this->command->warn('Nenhuma fusão encontrada no JSON.');
        }
    }
}