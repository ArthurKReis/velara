<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Demon;

class DemonSeeder extends Seeder
{
    public function run(): void
    {
        // Caminho absoluto para o arquivo JSON
        $jsonPath = storage_path('app/demons.json');

        if (!file_exists($jsonPath)) {
            $this->command->warn('Arquivo demons.json não encontrado em: ' . $jsonPath);
            $this->command->warn('Pule a população de demônios.');
            return;
        }

        $jsonContent = file_get_contents($jsonPath);
        $data = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('Erro ao decodificar JSON: ' . json_last_error_msg());
            return;
        }

        $this->command->info('Iniciando importação de demônios...');

        // Mapeamento de resistências
        $resistMap = [
            'weak' => 'wk',
            'resist' => 'rs',
            'null' => 'nu',
            'drain' => 'dr',
            'repel' => 'rp',
        ];

        $imported = 0;

        foreach ($data as $demonData) {
            // Pula demônios sem estatísticas, sem raça, ou da raça Persona
            if (!isset($demonData['stats']) || !isset($demonData['race']) || $demonData['race'] === 'Persona') {
                continue;
            }

            // Mapeia as estatísticas com as chaves corretas do JSON
            $stats = $demonData['stats'];
            $demon = [
                'name' => $demonData['name'] ?? 'Unknown',
                'race' => $demonData['race'] ?? 'Unknown',
                'level' => $demonData['level'] ?? 0,
                'strength' => $stats['st'] ?? 0,
                'magic' => $stats['ma'] ?? 0,
                'vitality' => $stats['vi'] ?? 0,
                'agility' => $stats['ag'] ?? 0,
                'luck' => $stats['lu'] ?? 0,
                'res_fire' => null,
                'res_ice' => null,
                'res_elec' => null,
                'res_force' => null,
                'res_light' => null,
                'res_dark' => null,
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Processa resistências usando a chave 'resistances' do JSON
            if (isset($demonData['resistances'])) {
                $resists = $demonData['resistances'];
                foreach ($resistMap as $key => $code) {
                    if (isset($resists[$key]) && is_array($resists[$key])) {
                        foreach ($resists[$key] as $element) {
                            $field = 'res_' . strtolower($element);
                            if (array_key_exists($field, $demon)) {
                                $demon[$field] = $code;
                            }
                        }
                    }
                }
            }

            // Evita duplicatas (nome + raça)
            $existing = Demon::where('name', $demon['name'])
                            ->where('race', $demon['race'])
                            ->first();

            if (!$existing) {
                Demon::create($demon);
                $imported++;
            }
        }

        $this->command->info("Importados {$imported} demônios.");
    }
}