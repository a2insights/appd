<?php

namespace App;

use Filament\Support\Contracts\HasLabel;

enum CausaDeficiencia: string implements HasLabel
{
    case ACIDENTE = 'acidente';
    case ACIDENTE_DE_MOTO = 'acidente_de_moto';
    case ACIDENTE_COM_ARMA_DE_FOGO = 'acidente_com_arma_de_fogo';
    case ACIDENTE_DE_TRABALHO = 'acidente_de_trabalho';
    case ACIDENTE_DE_TRANSITO = 'acidente_de_transito';
    case ACIDENTE_DOMESTICO = 'acidente_domestico';
    case ACIDENTE_MEDICO = 'acidente_medico';
    case ACIDENTE_MOTOR_DE_BARCO = 'acidente_motor_de_barco';
    case ADQUIRIDA = 'adquirida';
    case ANEMIA_FALCIFORME = 'anemia_falciforme';
    case ANEURISMA = 'aneurisma';
    case ARMA_BRANCA = 'arma_branca';
    case ARMA_DE_FOGO = 'arma_de_fogo';
    case ARTRITE_REUMATOIDE = 'artrite_reumatoide';
    case ARTRODESE = 'artrodese';
    case ARTRODESE_DA_COLUNA = 'artrodese_da_coluna';
    case ARTROSE = 'artrose';
    case AUDITIVA = 'auditiva';
    case AVC = 'avc';
    case CAUSA_SEM_DEFINICAO = 'causa_sem_definicao';
    case CANCER_DE_MAMA = 'cancer_de_mama';
    case CEGUEIRA = 'cegueira';
    case CIRCULACAO_DE_SANGUE = 'circulacao_de_sangue';
    case CONGENITA = 'congenita';
    case CONGENITA_ADQUIRIDA = 'congenita_adquirida';
    case DERRAME = 'derrame';
    case DESCARGA_ELETRICA = 'descarga_eletrica';
    case DESCONHECIDA = 'desconhecida';
    case DESLOCAMENTO_DA_RETINA = 'deslocamento_da_retina';
    case DIABETES = 'diabetes';
    case ERRO_MEDICO = 'erro_medico';
    case FOGOS_DE_ARTIFICIO = 'fogos_de_artificio';
    case GANGRENA = 'gangrena';
    case GLAUCOMA = 'glaucoma';
    case HANSENIASE = 'hanseniase';
    case HEREDITARIA = 'hereditaria';
    case HIDROCEFALIA = 'hidrocefalia';
    case LESAO_MUSCULAR = 'lesao_muscular';
    case LEUCEMIA_LINFOBLASTICA = 'leucemia_linfoblastica';
    case MENINGITE = 'meningite';
    case MERGULHO_AGUAS_RAZAS = 'mergulho_aguas_razas';
    case MHV = 'mhv';
    case MMII = 'mmii';
    case NEUROLOGICA = 'neurologica';
    case OPERACAO_PARAFUSOS = 'operacao_parafusos';
    case OSTEOMIELITE = 'osteomielite';
    case PARALISIA_CEREBRAL = 'paralisia_cerebral';
    case PARALISIA_CEREBRAL_INFANTIL = 'paralisia_cerebral_infantil';
    case PARALISIA_INFANTIL = 'paralisia_infantil';
    case PCI = 'pci';
    case PERDA_AUDICAO_BILATERAL = 'perda_audicao_bilateral';
    case PICADA_DE_COBRA = 'picada_de_cobra';
    case POLINEUROPATIA_NAO_ESPECIFICADA = 'polineuropatia_nao_especificada';
    case POLIO = 'polio';
    case POLIOMIELITE = 'poliomielite';
    case QUEDA = 'queda';
    case RETINOSE_PIGMENTAR = 'retinose_pigmentar';
    case REUMATISMO = 'reumatismo';
    case SEQUELA_PARALISIA = 'sequela_paralisia';
    case SEQUELAS_POLIOMIELITE = 'sequelas_poliomielite';
    case TRAUMATISMO_NO_NERVO = 'traumatismo_no_nervo';
    case VISUAL = 'visual';

    public function getLabel(): string
    {
        return match ($this) {
            self::ACIDENTE => 'Acidente',
            self::ACIDENTE_DE_MOTO => 'Acidente de Moto',
            self::ACIDENTE_COM_ARMA_DE_FOGO => 'Acidente com Arma de Fogo',
            self::ACIDENTE_DE_TRABALHO => 'Acidente de Trabalho',
            self::ACIDENTE_DE_TRANSITO => 'Acidente de Trânsito',
            self::ACIDENTE_DOMESTICO => 'Acidente Doméstico',
            self::ACIDENTE_MEDICO => 'Acidente Médico',
            self::ACIDENTE_MOTOR_DE_BARCO => 'Acidente Motor de Barco',
            self::ADQUIRIDA => 'Adquirida',
            self::ANEMIA_FALCIFORME => 'Adquirida Anemia Falciforme',
            self::ANEURISMA => 'Aneurisma',
            self::ARMA_BRANCA => 'Arma Branca',
            self::ARMA_DE_FOGO => 'Arma de Fogo',
            self::ARTRITE_REUMATOIDE => 'Artrite Reumatoide',
            self::ARTRODESE => 'Artrodese',
            self::ARTRODESE_DA_COLUNA => 'Artrodese da Coluna',
            self::ARTROSE => 'Artrose',
            self::AUDITIVA => 'Auditiva',
            self::AVC => 'AVC',
            self::CAUSA_SEM_DEFINICAO => 'Causa sem Definição',
            self::CANCER_DE_MAMA => 'Câncer de Mama',
            self::CEGUEIRA => 'Cegueira',
            self::CIRCULACAO_DE_SANGUE => 'Circulação de Sangue',
            self::CONGENITA => 'Congênita',
            self::CONGENITA_ADQUIRIDA => 'Congênita Adquirida',
            self::DERRAME => 'Derrame',
            self::DESCARGA_ELETRICA => 'Descarga Elétrica',
            self::DESCONHECIDA => 'Desconhecida',
            self::DESLOCAMENTO_DA_RETINA => 'Deslocamento da Retina',
            self::DIABETES => 'Diabetes',
            self::ERRO_MEDICO => 'Erro Médico',
            self::FOGOS_DE_ARTIFICIO => 'Fogos de Artifício',
            self::GANGRENA => 'Gangrena',
            self::GLAUCOMA => 'Glaucoma',
            self::HANSENIASE => 'Hanseníase',
            self::HEREDITARIA => 'Hereditária',
            self::HIDROCEFALIA => 'Hidrocefalia',
            self::LESAO_MUSCULAR => 'Lesão Muscular',
            self::LEUCEMIA_LINFOBLASTICA => 'Leucemia Linfoblástica',
            self::MENINGITE => 'Meningite',
            self::MERGULHO_AGUAS_RAZAS => 'Mergulho em Águas Rasas',
            self::MHV => 'MHV',
            self::MMII => 'MMII',
            self::NEUROLOGICA => 'Neurológica',
            self::OPERACAO_PARAFUSOS => 'Operação com Parafusos',
            self::OSTEOMIELITE => 'Osteomielite',
            self::PARALISIA_CEREBRAL => 'Paralisia Cerebral',
            self::PARALISIA_CEREBRAL_INFANTIL => 'Paralisia Cerebral Infantil',
            self::PARALISIA_INFANTIL => 'Paralisia Infantil',
            self::PCI => 'PCI',
            self::PERDA_AUDICAO_BILATERAL => 'Perda de Audição Bilateral',
            self::PICADA_DE_COBRA => 'Picada de Cobra',
            self::POLINEUROPATIA_NAO_ESPECIFICADA => 'Polineuropatia Não Especificada',
            self::POLIO => 'Polio',
            self::POLIOMIELITE => 'Poliomielite',
            self::QUEDA => 'Queda',
            self::RETINOSE_PIGMENTAR => 'Retinose Pigmentar',
            self::REUMATISMO => 'Reumatismo',
            self::SEQUELA_PARALISIA => 'Sequela de Paralisia',
            self::SEQUELAS_POLIOMIELITE => 'Sequelas de Poliomielite',
            self::TRAUMATISMO_NO_NERVO => 'Traumatismo no Nervo',
            self::VISUAL => 'Visual',
        };
    }
}
