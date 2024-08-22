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
            $table->enum('status', ["ativo","inativo","falecido"])->default('ativo');
            $table->date('data_nascimento');
            $table->string('nome_social')->nullable();
            $table->enum('sexo', ["masculino","feminino"]);
            $table->enum('declaracao_sexual', ["heterossexualidade","homossexualidade","bissexualidade","transsexualidade","panssexualidade","assexualidade","intergenero"])->nullable();
            $table->string('cpf')->nullable();
            $table->string('rg')->nullable();
            $table->enum('orgao_expedidor', ["abnc","cgpi_durex_dpf","cgpi","cgpmaf","cnig","cnt","coren","cra","cras","crb","crc","cre","crea","creci","crefito","crf","crm","crn","cro","crp","crpre","crq","crrc","crmv","csc"])->nullable();
            $table->enum('orgao_expedidor_uf', ["ac","al","ap","am","ba","ce","df","es","go","ma","mt","ms","mg","pa","pb","pr","pe","pi","rj","rn","rs","ro","rr","sc","sp","se","to"])->nullable();
            $table->enum('estado_civil', ["solteiro","casado","divorciado","viuvo","separado_judicialmente"]);
            $table->string('certidao_nascimento')->nullable();
            $table->string('naturalidade_ibge');
            $table->string('mae');
            $table->string('pai')->nullable();
            $table->enum('religiao', ["mormons","protestante","espiritismo","umbanda","budismo","candomble","judaismo","tradicoes_esotericas","islamismo","crencas_indigenas","catolico","ateu","outras"]);
            $table->enum('escolaridade', ["sem_escolaridade","ensino_fundamental_incompleto","ensino_fundamental","ensino_medio_incompleto","ensino_medio","ensino_superior_incompleto","ensino_superior","mestrado","doutorado"]);
            $table->enum('raca', ["branca","negra","amarela","parda","indigena","ignorado"]);
            $table->json('cid10');
            $table->string('crm')->nullable();
            $table->enum('causa_deficiencia', ["acidente_de_trabalho","artrite_reumatoide","acidente_domestico","acidente","poliomielite","congenito","polio","avc","acidente_de_transito","meningite","sequelas_poliomielite","paralisia_cerebral","hanseniase","reumatismo","pci"]);
            $table->enum('tipo_deficiencia', ["visual","auditiva","mental","fisica","multipla","intelectual"]);
            $table->enum('aparelho_utilizado', ["cadeira_de_rodas","andador","muleta","bengala","parafusos","perna_mecanica","bengala_canadense","bastao","muletas_auxiliares","braco_mecanico","oculos_e_protese","muleta_canadense","protese","protese_auricular","coupar","muletas","colete","bota_ortopedica","oculos","fistula","protese_ocular","fistula_mse","fistula_msd","fistula_esquerda","bolsa_de_colostomia"])->nullable();
            $table->integer('cep');
            $table->enum('ocupacao', ["estudante","empresario","funcionario_publico","bancario","militar","autonomo","aposentado","pensionista","funcionario_privado","dono_de_casa","profissional_liberal"])->nullable();
            $table->string('rua');
            $table->string('bairro');
            $table->string('numero');
            $table->string('estado');
            $table->string('cidade');
            $table->string('perimetro');
            $table->string('telefone_celular')->nullable();
            $table->string('telefone_whatsapbigintp')->nullable();
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
