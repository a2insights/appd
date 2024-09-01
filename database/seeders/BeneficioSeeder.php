<?php

namespace Database\Seeders;

use App\Models\Beneficio;
use Illuminate\Database\Seeder;

class BeneficioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $beneficios = [
            ['nome' => 'Aposentadoria por Idade', 'descricao' => 'A partir dos 65 anos para os homens e 60 anos para as mulheres, os trabalhadores urbanos podem solicitar o beneficio da aposentadoria. Já os trabalhadores rurais podem solicitar o beneficio mais cedo. Os homens aos 60 anos e  as mulheres aos 55.'],
            ['nome' => 'Aposentadoria por Invalidez', 'descricao' => 'Benefício recebido pelos trabalhadores que forem considerados incapacitados de trabalhar, no entanto de 2 em 2 anos o benefício e revisto e pode ser suspenso caso o trabalhador recupere sua capacidade de trabalho.'],
            ['nome' => 'Aposentadoria por Tempo de Contribuição', 'descricao' => 'Homens que contribuíram para a previdência durante 35 anos e mulheres que contribuíram por um período de 30 tem o direito de se aposentar.'],
            ['nome' => 'Aposentadoria Especial', 'descricao' => 'Concedida ao trabalhador que trabalhou por alguns anos em empregos que ofereciam condições prejudiciais a integridade física e a saúde do individuo em geral.'],
            ['nome' => 'Auxilio Doença', 'descricao' => 'Os vencimentos referentes aos 15 primeiros dias de licença medicam são pagos pelo empregador, acima desse período o  segurado passa a  receber pelo INSS.'],
            ['nome' => 'Auxilio Acidente', 'descricao' => 'Benefício recebido quando o trabalhador sofre um acidente que tem como conseqüência uma redução  permanentemente  na capacidade de trabalho.'],
            ['nome' => 'Pensão por Morte', 'descricao' => 'Benefício recebido pelos dependentes do segurado.Cônjuge, filhos menores de 21 anos ou dependentes inválidos de qualquer idade.'],
            ['nome' => 'Auxilio Reclusão', 'descricao' => 'Benefício pago aos dependentes de um segurado durante o período em que permanecer preso.'],
            ['nome' => 'Salário Família', 'descricao' => 'Consiste de um valor recebido  mensalmente para auxiliar no sustento dos filhos.'],
            ['nome' => 'Minha Casa Melhor', 'descricao' => 'Programa para compra de móveis para famílias de baixa renda.'],
            ['nome' => 'FIES', 'descricao' => 'Programa de financiamento estudantil para estudantes da educação superior em instituições particulares de ensino.'],
            ['nome' => 'Safra Garantida', 'descricao' => 'Garantindo renda mínima para a sobrevivência de agricultores de áreas atingidas por seca ou enchentes.'],
            ['nome' => 'Bolsa Família', 'descricao' => 'Serviço que paga um determinado valor para famílias de baixa renda.'],
            ['nome' => 'PETI', 'descricao' => 'Resgate da cidadania e inclusão social de crianças e adolescentes.'],
            ['nome' => 'Farmácia Popular', 'descricao' => 'Medicamentos com preços mais acessíveis'],
            ['nome' => 'Bolsa Verde', 'descricao' => 'Programa de apoio à conservação ambiental.'],
            ['nome' => 'PRONASCI', 'descricao' => 'Inclusão social e combate à criminalidade e à violência.'],
            ['nome' => 'Programa de Auxilio Emergencial Financeiro', 'descricao' => 'Um apoio às famílias atingidas por desastres ou que moram em regiões críticas.'],
            ['nome' => 'De volta para casa', 'descricao' => 'O apoio a quem precisa se reintegrar à sociedade após um longo período de internação hospitalar'],
            ['nome' => 'Chapéu de palha', 'descricao' => 'O programa que combate os efeitos do desemprego em Pernambuco'],
            ['nome' => 'Bolsa Atleta', 'descricao' => 'O apoio que o atleta precisa para se tornar um grande campeão. Atleta de base: R$370,00 e atleta pódio: até R$15.000,00.'],
            ['nome' => 'PROUNI', 'descricao' => 'Programa Universidade para Todos que oferece bolsa de estudos na educação superior a alunos que atendam ao critério do programa.'],
            ['nome' => 'Pronatec', 'descricao' => 'Programa para formação e recuperação de rabalhadores através da educação técnica e profissionalizante.'],
            ['nome' => 'BPC', 'descricao' => 'Benefício de Prestação Continuada'],
        ];

        Beneficio::insert($beneficios);
    }
}
