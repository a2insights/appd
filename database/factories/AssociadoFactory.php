<?php

namespace Database\Factories;

use App\Models\Associado;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssociadoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Associado::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'foto' => $this->faker->word(),
            'nome' => $this->faker->word(),
            'status' => $this->faker->randomElement(['ativo', 'inativo', 'falecido']),
            'data_nascimento' => $this->faker->date(),
            'nome_social' => $this->faker->word(),
            'sexo' => $this->faker->randomElement(['masculino', 'feminino']),
            'declaracao_sexual' => $this->faker->randomElement(['heterossexualidade', 'homossexualidade', 'bissexualidade', 'transsexualidade', 'panssexualidade', 'assexualidade', 'intergenero']),
            'cpf' => $this->faker->word(),
            'rg' => $this->faker->word(),
            'orgao_expedidor' => $this->faker->randomElement(['abnc', 'cgpi_durex_dpf', 'cgpi', 'cgpmaf', 'cnig', 'cnt', 'coren', 'cra', 'cras', 'crb', 'crc', 'cre', 'crea', 'creci', 'crefito', 'crf', 'crm', 'crn', 'cro', 'crp', 'crpre', 'crq', 'crrc', 'crmv', 'csc']),
            'orgao_expedidor_uf' => $this->faker->randomElement(['ac', 'al', 'ap', 'am', 'ba', 'ce', 'df', 'es', 'go', 'ma', 'mt', 'ms', 'mg', 'pa', 'pb', 'pr', 'pe', 'pi', 'rj', 'rn', 'rs', 'ro', 'rr', 'sc', 'sp', 'se', 'to']),
            'estado_civil' => $this->faker->randomElement(['solteiro', 'casado', 'divorciado', 'viuvo', 'separado_judicialmente']),
            'certidao_nascimento' => $this->faker->word(),
            'naturalidade_ibge' => $this->faker->word(),
            'naturalidade_uf' => $this->faker->randomElement(['ac', 'al', 'ap', 'am', 'ba', 'ce', 'df', 'es', 'go', 'ma', 'mt', 'ms', 'mg', 'pa', 'pb', 'pr', 'pe', 'pi', 'rj', 'rn', 'rs', 'ro', 'rr', 'sc', 'sp', 'se', 'to']),
            'naturalidade_municipio_ibge' => $this->faker->randomNumber(),
            'mae' => $this->faker->word(),
            'pai' => $this->faker->word(),
            'religiao' => $this->faker->randomElement(['mormons', 'protestante', 'espiritismo', 'umbanda', 'budismo', 'candomble', 'judaismo', 'tradicoes_esotericas', 'islamismo', 'crencas_indigenas', 'catolico', 'ateu', 'outras']),
            'ocupacao' => $this->faker->randomElement(['estudante', 'empresario', 'funcionario_publico', 'bancario', 'militar', 'autonomo', 'aposentado', 'pensionista', 'funcionario_privado', 'dono_de_casa', 'profissional_liberal']),
            'escolaridade' => $this->faker->randomElement(['sem_escolaridade', 'ensino_fundamental_incompleto', 'ensino_fundamental', 'ensino_medio_incompleto', 'ensino_medio', 'ensino_superior_incompleto', 'ensino_superior', 'mestrado', 'doutorado']),
            'raca' => $this->faker->randomElement(['branca', 'negra', 'amarela', 'parda', 'indigena', 'ignorado']),
            'cid10' => '{}',
            'crm' => $this->faker->word(),
            'causa_deficiencia' => $this->faker->randomElement(['acidente_de_trabalho', 'artrite_reumatoide', 'acidente_domestico', 'acidente', 'poliomielite', 'congenito', 'polio', 'avc', 'acidente_de_transito', 'meningite', 'sequelas_poliomielite', 'paralisia_cerebral', 'hanseniase', 'reumatismo', 'pci']),
            'tipo_deficiencia' => $this->faker->randomElement(['visual', 'auditiva', 'mental', 'fisica', 'multipla', 'intelectual']),
            'aparelho_utilizado' => $this->faker->randomElement(['cadeira_de_rodas', 'andador', 'muleta', 'bengala', 'parafusos', 'perna_mecanica', 'bengala_canadense', 'bastao', 'muletas_auxiliares', 'braco_mecanico', 'oculos_e_protese', 'muleta_canadense', 'protese', 'protese_auricular', 'coupar', 'muletas', 'colete', 'bota_ortopedica', 'oculos', 'fistula', 'protese_ocular', 'fistula_mse', 'fistula_msd', 'fistula_esquerda', 'bolsa_de_colostomia']),
            'cep' => $this->faker->numberBetween(-10000, 10000),
            'rua' => $this->faker->word(),
            'bairro' => $this->faker->word(),
            'numero' => $this->faker->word(),
            'estado' => $this->faker->word(),
            'cidade' => $this->faker->word(),
            'perimetro' => $this->faker->word(),
            'telefone_celular' => $this->faker->word(),
            'telefone_whatsapp' => $this->faker->word(),
            'telefone_fixo' => $this->faker->word(),
            'email' => $this->faker->safeEmail(),
        ];
    }
}
