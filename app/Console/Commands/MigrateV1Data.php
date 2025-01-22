<?php

namespace App\Console\Commands;

use App\Models\Associado;
use App\Models\Beneficio;
use App\Models\Carteirinha;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MigrateV1Data extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appd:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate data from the v1 version of the app to the v2 version.';

    /**
     * @var Collection
     */
    protected $beneficios = [];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // codigo,foto,status,nome,nome_social,
        // email,celular,whatsapp,fixo,tipo_de_deficiencia,causa_da_deficiencia
        // crm,cids,beneficios,aparelhos_utilizado,ocupacoes,data_de_nascimento
        // sexo,declaracao_sexual,orgao_expedidor,orgao_expedidor_estado_id,estado_civil_nome
        // religião,escolaridade,raca,certidao_de_nascimento,naturalidade,mae,pai,data_cadastro
        // cpf,rg,estado,cidade,bairro,cep,rua,numero,perimetro

        $this->beneficios = Beneficio::all();

        $associadosCsv = array_map('str_getcsv', file(base_path('app/Console/Commands/associados.csv')));
        $carteirinhasCsv = array_map('str_getcsv', file(base_path('app/Console/Commands/carteirinhas.csv')));
        $arquivosCsv = array_map('str_getcsv', file(base_path('app/Console/Commands/arquivos.csv')));

        unset($associadosCsv[0]);
        unset($carteirinhasCsv[0]);
        unset($arquivosCsv[0]);

        $associados = collect($associadosCsv)->map(function ($row) {
            $row = array_map(function ($value) {
                return $value === 'NULL' ? null : $value;
            }, $row);

            return [
                'codigo' => $row[0],
                'foto' => $row[1],
                'status' => $this->mapStatus($row[2]), // required
                'nome' => Str::upper($row[3]), // required
                'nome_social' => Str::upper($row[4]),
                'email' => $row[5],
                'telefone_celular' => preg_replace('/\D/', '', $row[6]),
                'telefone_whatsapp' => preg_replace('/\D/', '', $row[7]),
                'telefone_fixo' => preg_replace('/\D/', '', $row[8]),
                'tipo_deficiencia' => $this->mapTipoDeficiencia($row[9]),  // required
                'causa_deficiencia' => $this->mapCausaDeficiencia($row[10]), // required
                'crm' => $row[11],
                'cid10' => $this->mapCids($row[12]), // required
                'beneficios' => $row[13],
                'aparelhos_utilizado' => $this->mapAparelhosUtilizado($row[14]),
                'ocupacoes' => $this->mapOcupacoes($row[15]),
                'data_nascimento' => $row[16], // required
                'sexo' => ['Masculino' => 'masculino', 'Feminino' => 'feminino', '' => null][$row[17]],
                'declaracao_sexual' => $this->mapDeclaracaoSexual($row[18]),
                'orgao_expedidor' => $this->mapOrgaoExpedidor($row[19]),
                'orgao_expedidor_uf' => $this->mapOrgaoExpedidorUf($row[20]),
                'estado_civil' => $this->mapEstadoCivil($row[21]), // required
                'religiao' => $this->mapReligiao($row[22]),
                'escolaridade' => $this->mapEscolaridade($row[23]),
                'raca' => $this->mapRaca($row[24]),
                'certidao_nascimento' => $row[25],
                // 'naturalidade_uf' => ?,
                // 'naturalidade_municipio_ibge' => ?,
                'mae' => Str::upper($row[27]),
                'pai' => Str::upper($row[28]),
                'created_at' => $row[29] ?? @$row[39],
                'updated_at' => @$row[39],
                'cpf' => preg_replace('/\D/', '', $row[30]),
                'rg' => $row[31],
                'estado' => $row[32],
                'cidade' => $row[33],
                'bairro' => $row[34],
                'cep' => preg_replace('/\D/', '', $row[35]),
                'rua' => $row[36],
                'numero' => $row[37],
                'perimetro' => $row[38],
            ];
        })
            ->where('created_at', '!=', null)
            ->where('tipo_deficiencia', '!=', null)
            ->where('cep', '!=', null)
            ->where('cep', '!=', '')
            ->values()
            ->all();

        $carteirinhas = collect($carteirinhasCsv)->map(function ($row) {
            return [
                'associado_id' => $row[1],
                'status' => $row[3],
                'data_emissao' => $row[5],
                'data_vencimento' => $row[4],
                'created_at' => $row[6],
                'updated_at' => $row[7],
            ];
        });

        $arquivos = collect($arquivosCsv)->map(function ($row) {
            return [
                'associado_id' => $row[0],
                'path' => $row[3],
                'model_type' => 'associado',
                'uuid' => Str::uuid(),
                'collection_name' => 'arquivos',
                'name' => $row[1],
                'file_name' => @$row[2] . '.' . explode('/', $row[4])[1],
                'mime_type' => $row[4],
                'disk' => 's3',
                'conversions_disk' => 's3',
                'size' => $row[3],
                'manipulations' => [],
                'created_at' => $row[5],
                'updated_at' => $row[6],
            ];
        });

        $n = 0;
        $startAfter = 0;

        $associados = array_slice($associados, $startAfter);

        foreach ($associados as $a) {
            $n++;
            $conting = $n + $startAfter;
            $this->info("Migrating associado {$conting} of " . count($associados));
            $associado = new Associado;
            $data = $a;
            unset($data['codigo']);
            unset($data['beneficios']);
            $associado = $associado->forceFill($data);
            $associado->save();

            $beneficios = explode(',', $associado['beneficios']);
            $beneficios = $this->beneficios->whereIn('nome', $beneficios)->pluck('id')->toArray();

            $associado->beneficios()->attach($beneficios);

            /// continue;
            //////////////

            $carteirinhasToCreate = $carteirinhas->whereIn('associado_id', $a['codigo'])->all();

            foreach ($carteirinhasToCreate as $c) {
                $c['foto'] = $associado->foto;
                $c['associado_id'] = $associado->id;
                dump($c);

                if (! Carbon::parse($c['data_vencimento'])->gt(Carbon::now())) {
                    $c['status'] = 'vencida';
                }

                $filename = basename($associado->foto);
                $targetPath = 'carteirinhas/' . uniqid() . '_' . $filename;

                Storage::disk(config('filament.default_filesystem_disk'))
                    ->copy($associado->foto, $targetPath);

                $c['foto'] = $targetPath;

                $carteirinha = Carteirinha::create($c);

                $carteirinha->forceFill([
                    'created_at' => $c['created_at'],
                    'updated_at' => $c['updated_at'],
                ]);
                $carteirinha->updateQuietly([
                    'created_at' => $c['created_at'],
                    'updated_at' => $c['updated_at'],
                ]);
            }

            $arquivosToCreate = $arquivos->whereIn('associado_id', $a['codigo'])->all();

            foreach ($arquivosToCreate as $a) {
                $file = Storage::disk(config('filament.default_filesystem_disk'))->get($a['path']);

                $media = $associado->addMediaFromStream($file)
                    ->usingFileName($a['file_name'])
                    ->toMediaCollection('associados_arquivos', config('filament.default_filesystem_disk'));

                $media->updateQuietly([
                    'created_at' => $a['created_at'],
                    'updated_at' => $a['updated_at'],
                ]);
            }
        }
    }

    private function mapStatus($status)
    {
        $map = [
            'Ativo' => 'ativo',
            'Falecido' => 'falecido',
            'Inativo' => 'inativo',
            'NULL' => null,
        ];

        return $map[$status] ?? 'inativo';
    }

    private function mapTipoDeficiencia($deficiencia)
    {
        $map = [
            'Visual' => 'visual',
            'Auditiva' => 'auditiva',
            'Mental' => 'mental',
            'Física' => 'fisica',
            'Múltipla' => 'multipla',
            'Intelectual' => 'intelectual',
            'Intectual' => 'intelectual',
            'NULL' => null,
        ];

        return $map[$deficiencia] ?? null;
    }

    private function mapCausaDeficiencia($causa)
    {
        $map = [
            '25/06/2011' => 'desconhecida', // Invalid or irrelevant data point, ignoring
            'Aadquerida' => 'adquirida',
            'Acidental' => 'acidente', // Updated from 'acidental' to 'acidente'
            'Acidente' => 'acidente',
            'Acidente com arma' => 'acidente_com_arma_de_fogo', // Updated from 'acidente com arma' to 'acidente_com_arma_de_fogo'
            'Acidente de Moto' => 'acidente_de_moto',
            'Acidente de Trabalho' => 'acidente_de_trabalho',
            'Acidente de trabalho' => 'acidente_de_trabalho',
            'Acidente de transito' => 'acidente_de_transito',
            'Acidente domestico' => 'acidente_domestico',
            'Acidente Medico' => 'acidente_medico',
            'Acidente Motor de Barco' => 'acidente_motor_de_barco',
            'Acidente Transito' => 'acidente_de_transito',
            'AcidenteDomestico' => 'acidente_domestico',
            'Addquirida' => 'adquirida',
            'Adequerido' => 'adquirida',
            'Adiquirida' => 'adquirida',
            'Adiquirido' => 'adquirida',
            'Adqerida' => 'adquirida',
            'Adqquerida' => 'adquirida',
            'Adqueida' => 'adquirida',
            'Adqueido' => 'adquirida',
            'Adqueria' => 'adquirida',
            'Adquerida' => 'adquirida',
            'Adqueridah54.4' => 'adquirida',
            'Adqueridam' => 'adquirida',
            'Adqueridas' => 'adquirida',
            'Adquerido' => 'adquirida',
            'Adquerioda' => 'adquirida',
            'Adquerira' => 'adquirida',
            'aDQUERISA' => 'adquirida',
            'Adquieida' => 'adquirida',
            'Adquiredo' => 'adquirida',
            'Adquirida' => 'adquirida',
            'Adquirida Anemia Falciforme' => 'anemia_falciforme',
            'Adquirida Arma de Fogo' => 'arma_de_fogo', // Updated from 'adquirida_arma_de_fogo' to 'arma_de_fogo'
            'Adquiridas' => 'adquirida',
            'Adquirida' => 'adquirida',
            'Adquiririda' => 'adquirida',
            'Adquiurida' => 'adquirida',
            'Adqurida' => 'adquirida',
            'Adquririda' => 'adquirida',
            'Aduirida' => 'adquirida',
            'Aduqerida' => 'adquirida',
            'Aduqirido' => 'adquirida',
            'Afquerida' => 'adquirida',
            'Aneurisma' => 'aneurisma',
            'Aqdquirida' => 'adquirida',
            'Aqduerida' => 'adquirida',
            'Aquirida' => 'adquirida',
            'Arma Branca' => 'arma_branca',
            'Arma de Fogo' => 'arma_de_fogo',
            'Artrite Reumatóide' => 'artrite_reumatoide',
            'Artrodese' => 'artrodese',
            'Artrodese da Coluna' => 'artrodese_da_coluna',
            'Artrose' => 'artrose',
            'Asdquirida' => 'adquirida',
            'Auditiva' => 'auditiva',
            'AVC' => 'avc',
            'Cancer de Mama' => 'cancer_de_mama',
            'Cegueira' => 'cegueira',
            'Circulacao de sangue' => 'circulacao_de_sangue',
            'Cngenita' => 'congenita',
            'Co0ngenita' => 'congenita',
            'Cogenita' => 'congenita',
            'Comgenita' => 'congenita',
            'Conenita+Adquirida' => 'congenita_adquirida',
            'Congêdnita' => 'congenita',
            'Congeita' => 'congenita',
            'Congeniata' => 'congenita',
            'Congeniota' => 'congenita',
            'Congenira' => 'congenita',
            'Congenit' => 'congenita', // Changed 'congenito' to 'congenita'
            'Congenita' => 'congenita',
            'Congenita+Adquirida' => 'congenita_adquirida',
            'CongenitaAdquerida' => 'congenita_adquirida',
            'Congenitaq' => 'congenita',
            'Congenitas' => 'congenita',
            'Congênito' => 'congenita', // Changed 'congenito' to 'congenita'
            'Congenta' => 'congenita',
            'Congernita' => 'congenita',
            'Congita' => 'congenita',
            'Congnita' => 'congenita',
            'Conjenita' => 'congenita',
            'Cxongenita' => 'congenita',
            'Derrame' => 'derrame',
            'Descarga Elétrica' => 'descarga_eletrica',
            'Desconhecida' => 'desconhecida',
            'Deslocamento da Retina' => 'deslocamento_da_retina',
            'Diabetes' => 'diabetes',
            'dquirida' => 'adquirida',
            'Erro Medico' => 'erro_medico',
            'F90+H53.9+R26' => 'desconhecida', // Invalid code
            'Fogos de Artificio' => 'fogos_de_artificio',
            'Gangrena' => 'gangrena',
            'Glaucoma' => 'glaucoma',
            'Gongenita' => 'congenita',
            'H90.3' => 'causa_sem_definicao', // Invalid code
            'Hanseniase' => 'hanseniase',
            'Hereditaria' => 'hereditaria',
            'Hidrocefalia' => 'hidrocefalia',
            'Lesao Muscular' => 'lesao_muscular',
            'Leucemia Linfoblastica' => 'leucemia_linfoblastica',
            'Meningite' => 'meningite',
            'Mergulho em águas razas' => 'mergulho_aguas_razas',
            'MHV' => 'mhv',
            'MMII' => 'mmii',
            'Neurologica' => 'neurologica',
            'ongenita' => 'congenita',
            'Operação parafusos' => 'operacao_parafusos',
            'Osteomielite' => 'osteomielite',
            'Paralisia Cerebral' => 'paralisia_cerebral',
            'Paralisia Cerebral Infantil' => 'paralisia_cerebral_infantil',
            'Paralisia Infantil' => 'paralisia_infantil',
            'PC' => 'pci',
            'PCI' => 'pci',
            'Perda de Audição Bilateral' => 'perda_audicao_bilateral',
            'Picada de cobra' => 'picada_de_cobra',
            'Polineuropatia não especificada' => 'polineuropatia_nao_especificada',
            'Polio' => 'polio',
            'Poliomelite' => 'poliomielite',
            'Poliomielite' => 'poliomielite',
            'Queda' => 'queda',
            'Retinose Pigmentar' => 'retinose_pigmentar',
            'Reumatismo' => 'reumatismo',
            'Sequela Paralisia' => 'sequela_paralisia',
            'Sequelas de Poliomielite' => 'sequelas_poliomielite',
            'Sequelas Poliomielite' => 'sequelas_poliomielite',
            'Tiro' => 'arma_de_fogo',
            'Traumatismo no Nervo' => 'traumatismo_no_nervo',
            'Visual' => 'visual',
            'NULL' => null,
            '' => null,
        ];

        return $map[$causa] ?? 'causa_sem_definicao';
    }

    private function mapCids($cids)
    {
        $cids = explode(',', $cids);

        return $cids;
    }

    private function mapAparelhosUtilizado($aparelhos)
    {
        $map = [
            'Cadeira de rodas' => 'cadeira_de_rodas',
            'Andador' => 'andador',
            'Muleta' => 'muleta',
            'Bengala' => 'bengala',
            'Parafusos' => 'parafusos',
            'Perna Mecânica' => 'perna_mecanica',
            'Bengala Canadense' => 'bengala_canadense',
            'Bastão' => 'bastao',
            'Muletas Auxiliares' => 'muletas_auxiliares',
            'Braço Mecânico' => 'braco_mecanico',
            'Óculos e Prótese' => 'oculos_e_protese',
            'Muleta Canadense' => 'muleta_canadense',
            'Prótese' => 'protese',
            'Prótese Auricular' => 'protese_auricular',
            'Coupar' => 'coupar',
            'Muletas' => 'muletas',
            'Colete' => 'colete',
            'Bota Ortopédica' => 'bota_ortopedica',
            'Óculos' => 'oculos',
            'Fístula' => 'fistula',
            'Prótese Ocular' => 'protese_ocular',
            'Fístula MSE' => 'fistula_mse',
            'Fístula MSD' => 'fistula_msd',
            'Fístula Esquerda' => 'fistula_esquerda',
            'Bolsa de Colostomia' => 'bolsa_de_colostomia',
        ];

        if (empty($aparelhos)) {
            return null;
        }

        if (is_string($aparelhos)) {
            $aparelhos = explode(',', $aparelhos);
        }

        return collect($aparelhos)
            ->map(function ($aparelho) use ($map) {
                return $map[trim($aparelho)] ?? null;
            })
            ->filter()
            ->all();
    }

    private function mapOcupacoes($ocupacoes)
    {
        $map = [
            'Trabalhador' => 'trabalhador',
            'Estudante' => 'estudante',
        ];

        if (empty($ocupacoes)) {
            return [];
        }

        if (is_string($ocupacoes)) {
            $ocupacoes = explode(',', $ocupacoes);
        }

        if (in_array('Cadeira de Rodas', $ocupacoes)) {
            dd($ocupacoes);
        }

        return collect($ocupacoes)
            ->map(function ($ocupacao) use ($map) {
                return $map[$ocupacao] ?? null;
            })
            ->filter()
            ->all(',');
    }

    private function mapDeclaracaoSexual($declaracao)
    {
        $map = [
            'Heterossexualidade' => 'heterossexualidade',
            'Homossexualidade' => 'homossexualidade',
            'Bissexualidade' => 'bissexualidade',
            'Transsexualidade' => 'transsexualidade',
            'Pansexualidade' => 'pansexualidade',
            'Assexualidade' => 'assexualidade',
            'Intergênero' => 'intergenero',
            'NULL' => null,
        ];

        return $map[$declaracao] ?? null;
    }

    private function mapOrgaoExpedidor($orgao)
    {
        $map = [
            'Academia Brasileira de Neurocirurgia' => 'abnc',
            'Carteira de Estrangeiro' => 'pf',
            'Carteira de Trabalho e Previdência Social' => 'ctps',
            'Conselho Regional de Administração' => 'cra',
            'Conselho Regional de Assistentes Sociais' => 'cress',
            'Conselho Regional de Contabilidade' => 'crc',
            'Conselho Regional de Enfermagem' => 'coren',
            'Conselho Regional de Engenharia Arquitetura e Agronomia' => 'crea',
            'Conselho Regional de Psicologia' => 'crp',
            'Departamento de Polícia Técnica Geral' => 'dpt',
            'Ministério da Aeronáutica' => 'maer',
            'Ministério da Marinha' => 'mm',
            'Ministério do Exército' => 'mex',
            'Ordem dos Advogados do Brasil' => 'oab',
            'Policia Civil do Estado de Minas Gerais' => 'pc',
            'Polícia Federal' => 'dpf',
            'Polícia Militar' => 'pm',
            'Secretaria da Justiça e Segurança' => 'sj',
            'Secretaria de Estado de Justiça e Segurança Pública Mato Grosso' => 'sj',
            'Secretaria de Segurança Pública' => 'ssp',
            'NULL' => null,
            '' => null,
        ];

        return $map[$orgao] ?? null;
    }

    private function mapOrgaoExpedidorUf($uf)
    {
        if (! (int) $uf) {
            return null;
        }

        $map = [
            11 => 'RO',
            12 => 'AC',
            13 => 'AM',
            14 => 'RR',
            15 => 'PA',
            16 => 'AP',
            17 => 'TO',
            21 => 'MA',
            22 => 'PI',
            23 => 'CE',
            24 => 'RN',
            25 => 'PB',
            26 => 'PE',
            27 => 'AL',
            28 => 'SE',
            29 => 'BA',
            31 => 'MG',
            32 => 'ES',
            33 => 'RJ',
            35 => 'SP',
            41 => 'PR',
            42 => 'SC',
            43 => 'RS',
            50 => 'MS',
            51 => 'MT',
            52 => 'GO',
            53 => 'DF',
            'NULL' => null,
            '' => null,
            ' ' => null,
        ];

        return Str::lower(@$map[$uf]) ?? null;
    }

    private function mapEstadoCivil($estadoCivil)
    {
        $map = [
            'Solteiro (a)' => 'solteiro',
            'Casado (a)' => 'casado',
            'Divorciado (a)' => 'divorciado',
            'Viúvo (a)' => 'viuvo',
            'Separado Judicialmente' => 'separado_judicialmente',
            'NULL' => 'solteiro',
            '' => 'solteiro',
        ];

        return $map[$estadoCivil] ?? 'solteiro';
    }

    private function mapReligiao($religiao)
    {
        $map = [
            'Mórmons' => 'mormons',
            'Protestante' => 'protestante',
            'Espiritismo' => 'espiritismo',
            'Umbanda' => 'umbanda',
            'Budismo' => 'budismo',
            'Candomblé' => 'candomble',
            'Judaísmo' => 'judaismo',
            'Tradições Esotéricas' => 'tradicoes_esotericas',
            'Islamismo' => 'islamismo',
            'Crenças Indígenas' => 'crencas_indigenas',
            'Católico' => 'catolico',
            'Ateu' => 'ateu',
            'Outras' => 'outras',
            'NULL' => null,
        ];

        return $map[$religiao] ?? null;
    }

    private function mapEscolaridade($escolaridade)
    {
        $map = [
            'Sem escolaridade' => 'sem_escolaridade',
            'Ensino Fundamental Incompleto' => 'ensino_fundamental_incompleto',
            'Ensino Fundamental' => 'ensino_fundamental',
            'Ensino Médio Incompleto' => 'ensino_medio_incompleto',
            'Ensino Médio' => 'ensino_medio',
            'Ensino Superior Incompleto' => 'ensino_superior_incompleto',
            'Ensino Superior' => 'ensino_superior',
            'Mestrado' => 'mestrado',
            'Doutorado' => 'doutorado',
            'NULL' => null,
            '' => null,
        ];

        return $map[$escolaridade] ?? 'ensino_fundamental_incompleto';
    }

    private function mapRaca($raca)
    {
        $map = [
            'Branca' => 'branca',
            'Negra' => 'negra',
            'Amarela' => 'amarela',
            'Parda' => 'parda',
            'Indígena' => 'indigena',
            'Ignorado' => 'ignorado',
            'NULL' => 'ignorado',
        ];

        return $map[$raca] ?? 'ignorado';
    }
}
