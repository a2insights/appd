<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('associados', function (Blueprint $table) {
            $table->id();
            $table->string('foto')->nullable();
            $table->string('nome');
            $table->enum('status', ['ativo', 'inativo', 'falecido'])->default('ativo');
            $table->date('data_nascimento');
            $table->string('nome_social')->nullable();
            $table->enum('sexo', ['masculino', 'feminino'])->nullable();
            $table->enum('declaracao_sexual', ['heterossexualidade', 'homossexualidade', 'bissexualidade', 'transsexualidade', 'pansexualidade', 'assexualidade', 'intergenero'])->nullable();
            $table->string('cpf')->nullable();
            $table->string('rg')->nullable();
            $table->enum('orgao_expedidor', ['abnc', 'agu', 'anac', 'caer', 'cau', 'cbm', 'cfa', 'cfb', 'cfbio', 'cfbm', 'cfc', 'cfess', 'cff', 'cffa', 'cfm', 'cfmv', 'cfn', 'cfo', 'cfp', 'cfq', 'cft', 'cfta', 'cgpi', 'cgpmaf', 'cipc', 'cnig', 'cnt', 'cntv', 'cofeci', 'cofecon', 'cofem', 'cofen', 'coffito', 'comaer', 'confe', 'confea', 'confef', 'confere', 'conre', 'conrerp', 'core', 'corecon', 'corem', 'coren', 'cra', 'cras', 'crb', 'crbio', 'crbm', 'crc', 'crea', 'creci', 'cref', 'crefito', 'cress', 'crf', 'crfa', 'crm', 'crmv', 'crn', 'cro', 'crp', 'crpre', 'crq', 'crt', 'crta', 'ctps', 'cv', 'delemig', 'detran', 'dgpc', 'dic', 'dicc', 'direx', 'dpf', 'dpmaf', 'dpt', 'dptc', 'drex', 'drt', 'eb', 'fab', 'fenaj', 'fgts', 'fipe', 'fls', 'funai', 'gejsp', 'gejspc', 'gejuspc', 'gesp', 'govgo', 'icla', 'icp', 'idamp', 'ifp', 'igp', 'iiacm', 'iicc', 'iiccecf', 'iicm', 'iigp', 'iijdm', 'iipc', 'iipm', 'iirgd', 'iirhm', 'iitb', 'iml', 'ini', 'ipf', 'itcp', 'itep', 'maer', 'mb', 'md', 'mds', 'mec', 'mex', 'mindef', 'mj', 'mm', 'mma', 'mpas', 'mpe', 'mpf', 'mpt', 'mre', 'mt', 'mte', 'mtps', 'numig', 'oab', 'omb', 'pc', 'pf', 'pgfn', 'pm', 'politec', 'prf', 'ptc', 'scc', 'scjds', 'sds', 'secc', 'seccde', 'seds', 'segup', 'sejsp', 'sejuc', 'sejusp', 'sepc', 'sepol', 'ses', 'sesc', 'sesdc', 'sesdec', 'seseg', 'sesp', 'sespap', 'sespdc', 'sespds', 'sgpc', 'sgpj', 'sim', 'sj', 'sjcdh', 'sjds', 'sjs', 'sjtc', 'sjts', 'snj', 'spmaf', 'sptc', 'srdpf', 'srf', 'srte', 'ssdc', 'ssds', 'ssi', 'ssp', 'sspcgp', 'sspdc', 'sspds', 'ssppc', 'susep', 'susepe', 'tj', 'tjaem', 'tre', 'trf', 'tse'])->nullable();
            $table->enum('orgao_expedidor_uf', ['ac', 'al', 'ap', 'am', 'ba', 'ce', 'df', 'es', 'go', 'ma', 'mt', 'ms', 'mg', 'pa', 'pb', 'pr', 'pe', 'pi', 'rj', 'rn', 'rs', 'ro', 'rr', 'sc', 'sp', 'se', 'to'])->nullable();
            $table->enum('estado_civil', ['solteiro', 'casado', 'divorciado', 'viuvo', 'separado_judicialmente']);
            $table->string('certidao_nascimento')->nullable();
            $table->enum('naturalidade_uf', ['ac', 'al', 'ap', 'am', 'ba', 'ce', 'df', 'es', 'go', 'ma', 'mt', 'ms', 'mg', 'pa', 'pb', 'pr', 'pe', 'pi', 'rj', 'rn', 'rs', 'ro', 'rr', 'sc', 'sp', 'se', 'to'])->nullable();
            $table->integer('naturalidade_municipio_ibge')->nullable();
            $table->string('mae')->nullable();
            $table->string('pai')->nullable();
            $table->enum('religiao', ['mormons', 'protestante', 'espiritismo', 'umbanda', 'budismo', 'candomble', 'judaismo', 'tradicoes_esotericas', 'islamismo', 'crencas_indigenas', 'catolico', 'ateu', 'outras'])->nullable();
            $table->set('ocupacoes', ['estudante', 'empresario', 'funcionario_publico', 'bancario', 'militar', 'autonomo', 'aposentado', 'pensionista', 'funcionario_privado', 'dono_de_casa', 'profissional_liberal', 'trabalhador'])->nullable();
            $table->enum('escolaridade', ['sem_escolaridade', 'ensino_fundamental_incompleto', 'ensino_fundamental', 'ensino_medio_incompleto', 'ensino_medio', 'ensino_superior_incompleto', 'ensino_superior', 'mestrado', 'doutorado']);
            $table->enum('raca', ['branca', 'negra', 'amarela', 'parda', 'indigena', 'ignorado']);
            $table->json('cid10');
            $table->string('crm')->nullable();
            $table->enum('causa_deficiencia', ['acidente', 'acidente_de_moto', 'acidente_com_arma_de_fogo', 'acidente_de_trabalho', 'acidente_de_transito', 'acidente_domestico', 'acidente_medico', 'acidente_motor_de_barco', 'adquirida', 'anemia_falciforme', 'aneurisma', 'arma_branca', 'arma_de_fogo', 'artrite_reumatoide', 'artrodese', 'artrodese_da_coluna', 'artrose', 'auditiva', 'avc', 'causa_sem_definicao', 'cancer_de_mama', 'cegueira', 'circulacao_de_sangue', 'congenita', 'congenita_adquirida', 'derrame', 'descarga_eletrica', 'desconhecida', 'deslocamento_da_retina', 'diabetes', 'erro_medico', 'fogos_de_artificio', 'gangrena', 'glaucoma', 'hanseniase', 'hereditaria', 'hidrocefalia', 'lesao_muscular', 'leucemia_linfoblastica', 'meningite', 'mergulho_aguas_razas', 'mhv', 'mmii', 'neurologica', 'operacao_parafusos', 'osteomielite', 'paralisia_cerebral', 'paralisia_cerebral_infantil', 'paralisia_infantil', 'pci', 'perda_audicao_bilateral', 'picada_de_cobra', 'polineuropatia_nao_especificada', 'polio', 'poliomielite', 'queda', 'retinose_pigmentar', 'reumatismo', 'sequela_paralisia', 'sequelas_poliomielite', 'traumatismo_no_nervo', 'visual']);
            $table->enum('tipo_deficiencia', ['visual', 'auditiva', 'mental', 'fisica', 'multipla', 'intelectual']);
            $table->set('aparelhos_utilizado', ['cadeira_de_rodas', 'andador', 'muleta', 'bengala', 'parafusos', 'perna_mecanica', 'bengala_canadense', 'bastao', 'muletas_auxiliares', 'braco_mecanico', 'oculos_e_protese', 'muleta_canadense', 'protese', 'protese_auricular', 'coupar', 'muletas', 'colete', 'bota_ortopedica', 'oculos', 'fistula', 'protese_ocular', 'fistula_mse', 'fistula_msd', 'fistula_esquerda', 'bolsa_de_colostomia'])->nullable();
            $table->integer('cep');
            $table->string('rua');
            $table->string('bairro');
            $table->string('numero');
            $table->string('estado');
            $table->string('cidade');
            $table->string('perimetro');
            $table->string('telefone_celular')->nullable();
            $table->string('telefone_whatsapp')->nullable();
            $table->string('telefone_fixo')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('associados');
    }
};
