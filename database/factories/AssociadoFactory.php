<?php

namespace Database\Factories;

use App\Models\Associado;
use App\Models\Cid10;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AssociadoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Associado::class;


    /**
     *  Lista de municípios do IBGE
     *
     * @var Collection|null
     */
    private static $municipios = null;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        if (! self::$municipios) {
            self::$municipios = $this->getMunicipios();
        }

        $naturalidadeMunicipio = $this->selectRandomMunicipio(self::$municipios);

        return [
            //  'foto' => 'avatars/'.$this->faker->image(storage_path('app/public/avatars'), 640, 480, null, false),
            'nome' => $this->faker->name(),
            'status' => $this->faker->randomElement(['ativo', 'inativo', 'falecido']),
            'data_nascimento' => $this->faker->date(),
            'nome_social' => $this->faker->word(),
            'sexo' => $this->faker->randomElement(['masculino', 'feminino']),
            'declaracao_sexual' => $this->faker->randomElement(['heterossexualidade', 'homossexualidade', 'bissexualidade', 'transsexualidade', 'pansexualidade', 'assexualidade', 'intergenero']),
            'cpf' => $this->faker->numberBetween(10000000000, 99999999999),
            'rg' => $this->faker->numberBetween(100000000, 999999999),
            'orgao_expedidor' => $this->faker->randomElement(['abnc', 'agu', 'anac', 'caer', 'cau', 'cbm', 'cfa', 'cfb', 'cfbio', 'cfbm', 'cfc', 'cfess', 'cff', 'cffa', 'cfm', 'cfmv', 'cfn', 'cfo', 'cfp', 'cfq', 'cft', 'cfta', 'cgpi', 'cgpmaf', 'cipc', 'cnig', 'cnt', 'cntv', 'cofeci', 'cofecon', 'cofem', 'cofen', 'coffito', 'comaer', 'confe', 'confea', 'confef', 'confere', 'conre', 'conrerp', 'core', 'corecon', 'corem', 'coren', 'cra', 'cras', 'crb', 'crbio', 'crbm', 'crc', 'crea', 'creci', 'cref', 'crefito', 'cress', 'crf', 'crfa', 'crm', 'crmv', 'crn', 'cro', 'crp', 'crpre', 'crq', 'crt', 'crta', 'ctps', 'cv', 'delemig', 'detran', 'dgpc', 'dic', 'dicc', 'direx', 'dpf', 'dpmaf', 'dpt', 'dptc', 'drex', 'drt', 'eb', 'fab', 'fenaj', 'fgts', 'fipe', 'fls', 'funai', 'gejsp', 'gejspc', 'gejuspc', 'gesp', 'govgo', 'icla', 'icp', 'idamp', 'ifp', 'igp', 'iiacm', 'iicc', 'iiccecf', 'iicm', 'iigp', 'iijdm', 'iipc', 'iipm', 'iirgd', 'iirhm', 'iitb', 'iml', 'ini', 'ipf', 'itcp', 'itep', 'maer', 'mb', 'md', 'mds', 'mec', 'mex', 'mindef', 'mj', 'mm', 'mma', 'mpas', 'mpe', 'mpf', 'mpt', 'mre', 'mt', 'mte', 'mtps', 'numig', 'oab', 'omb', 'pc', 'pf', 'pgfn', 'pm', 'politec', 'prf', 'ptc', 'scc', 'scjds', 'sds', 'secc', 'seccde', 'seds', 'segup', 'sejsp', 'sejuc', 'sejusp', 'sepc', 'sepol', 'ses', 'sesc', 'sesdc', 'sesdec', 'seseg', 'sesp', 'sespap', 'sespdc', 'sespds', 'sgpc', 'sgpj', 'sim', 'sj', 'sjcdh', 'sjds', 'sjs', 'sjtc', 'sjts', 'snj', 'spmaf', 'sptc', 'srdpf', 'srf', 'srte', 'ssdc', 'ssds', 'ssi', 'ssp', 'sspcgp', 'sspdc', 'sspds', 'ssppc', 'susep', 'susepe', 'tj', 'tjaem', 'tre', 'trf', 'tse']),
            'orgao_expedidor_uf' => $this->faker->randomElement(['ac', 'al', 'ap', 'am', 'ba', 'ce', 'df', 'es', 'go', 'ma', 'mt', 'ms', 'mg', 'pa', 'pb', 'pr', 'pe', 'pi', 'rj', 'rn', 'rs', 'ro', 'rr', 'sc', 'sp', 'se', 'to']),
            'estado_civil' => $this->faker->randomElement(['solteiro', 'casado', 'divorciado', 'viuvo', 'separado_judicialmente']),
            'certidao_nascimento' => $this->faker->word(),
            'naturalidade_municipio_ibge' => $this->faker->numberBetween(100000000, 999999999),
            'naturalidade_uf' => Str::lower($naturalidadeMunicipio['microrregiao']['mesorregiao']['UF']['sigla']),
            'naturalidade_municipio_ibge' => $naturalidadeMunicipio['id'],
            'mae' => $this->faker->name(),
            'pai' => $this->faker->name(),
            'religiao' => $this->faker->randomElement(['mormons', 'protestante', 'espiritismo', 'umbanda', 'budismo', 'candomble', 'judaismo', 'tradicoes_esotericas', 'islamismo', 'crencas_indigenas', 'catolico', 'ateu', 'outras']),
            'ocupacoes' => $this->faker->randomElements(['estudante', 'empresario', 'funcionario_publico', 'bancario', 'militar', 'autonomo', 'aposentado', 'pensionista', 'funcionario_privado', 'dono_de_casa', 'profissional_liberal']),
            'escolaridade' => $this->faker->randomElement(['sem_escolaridade', 'ensino_fundamental_incompleto', 'ensino_fundamental', 'ensino_medio_incompleto', 'ensino_medio', 'ensino_superior_incompleto', 'ensino_superior', 'mestrado', 'doutorado']),
            'raca' => $this->faker->randomElement(['branca', 'negra', 'amarela', 'parda', 'indigena', 'ignorado']),
            'cid10' => Cid10::inRandomOrder()->take(3)->pluck('id')->toArray(),
            'crm' => $this->faker->numberBetween(100000000, 999999999),
            'causa_deficiencia' => $this->faker->randomElement(['acidente_de_trabalho', 'artrite_reumatoide', 'acidente_domestico', 'acidente', 'poliomielite', 'congenito', 'polio', 'avc', 'acidente_de_transito', 'meningite', 'sequelas_poliomielite', 'paralisia_cerebral', 'hanseniase', 'reumatismo', 'pci']),
            'tipo_deficiencia' => $this->faker->randomElement(['visual', 'auditiva', 'mental', 'fisica', 'multipla', 'intelectual']),
            'aparelhos_utilizado' => $this->faker->randomElement(['cadeira_de_rodas', 'andador', 'muleta', 'bengala', 'parafusos', 'perna_mecanica', 'bengala_canadense', 'bastao', 'muletas_auxiliares', 'braco_mecanico', 'oculos_e_protese', 'muleta_canadense', 'protese', 'protese_auricular', 'coupar', 'muletas', 'colete', 'bota_ortopedica', 'oculos', 'fistula', 'protese_ocular', 'fistula_mse', 'fistula_msd', 'fistula_esquerda', 'bolsa_de_colostomia']),
            'cep' => $this->faker->numberBetween(100000000, 999999999),
            'rua' => $this->faker->streetName(),
            'bairro' => $this->faker->word(),
            'numero' => $this->faker->numberBetween(100000000, 999999999),
            'estado' => $this->faker->word(),
            'cidade' => $this->faker->city(),
            'perimetro' => $this->faker->address(),
            'telefone_celular' => $this->faker->e164PhoneNumber(),
            'telefone_whatsapp' => $this->faker->e164PhoneNumber(),
            'telefone_fixo' => $this->faker->e164PhoneNumber(),
            'email' => $this->faker->safeEmail(),
        ];
    }

    public function selectRandomMunicipio(Collection $municipios)
    {
        // Definindo as probabilidades por estado (UF)
        $probabilidadesEstado = [
            'PA' => 0.80,
            'MA' => 0.05,
            'TO' => 0.03,
            'AM' => 0.02,
            'RO' => 0.01,
            // Adicione outras UFs com suas respectivas probabilidades até completar 100%
            // Se algum estado não for especificado, a probabilidade restante será distribuída
        ];

        // Extrair as siglas dos estados (UF) de cada município
        $ufs = $municipios->map(function ($municipio) {
            return $municipio['microrregiao']['mesorregiao']['UF']['sigla'];
        });

        // Normalizar as probabilidades para cobrir todos os estados
        $totalProb = collect($probabilidadesEstado)->sum();
        $numEstados = $ufs->unique()->count();
        $estadoRestanteProb = (1.0 - $totalProb) / ($numEstados - count($probabilidadesEstado));

        // Distribuir probabilidades para UFs não especificadas
        $probabilidadesEstado = $ufs->unique()->mapWithKeys(function ($uf) use ($probabilidadesEstado, $estadoRestanteProb) {
            return [$uf => $probabilidadesEstado[$uf] ?? $estadoRestanteProb];
        });

        // Criar a lista de pesos com base nas probabilidades
        $pesos = $municipios->map(function ($municipio) use ($probabilidadesEstado) {
            $uf = $municipio['microrregiao']['mesorregiao']['UF']['sigla'];

            return $probabilidadesEstado[$uf];
        });

        // Escolher um município aleatório baseado nos pesos
        $municipioSelecionado = $municipios->zip($pesos)
            ->random(1, function ($item) {
                return $item[1];
            })->first()[0];

        return $municipioSelecionado;
    }

    private function getMunicipios(): Collection
    {
        // https://servicodados.ibge.gov.br/api/v1/localidades/municipios
        return cache()->rememberForever('municipios', function () {
            $response = Http::get('https://servicodados.ibge.gov.br/api/v1/localidades/municipios');

            return $response->collect();
        });
    }
}
