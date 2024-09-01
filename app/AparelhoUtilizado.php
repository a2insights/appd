<?php

namespace App;

use Filament\Support\Contracts\HasLabel;

enum AparelhoUtilizado: string implements HasLabel
{
    case CADEIRA_DE_RODAS = 'cadeira_de_rodas';
    case ANDADOR = 'andador';
    case MULETA = 'muleta';
    case BENGALA = 'bengala';
    case PARAFUSOS = 'parafusos';
    case PERNA_MECANICA = 'perna_mecanica';
    case BENGALA_CANADENSE = 'bengala_canadense';
    case BASTAO = 'bastao';
    case MULETAS_AUXILIARES = 'muletas_auxiliares';
    case BRACO_MECANICO = 'braco_mecanico';
    case OCULOS_E_PROTESE = 'oculos_e_protese';
    case MULETA_CANADENSE = 'muleta_canadense';
    case PROTESE = 'protese';
    case PROTESE_AURICULAR = 'protese_auricular';
    case COUPAR = 'coupar';
    case MULETAS = 'muletas';
    case COLETE = 'colete';
    case BOTA_ORTOPEDICA = 'bota_ortopedica';
    case OCULOS = 'oculos';
    case FISTULA = 'fistula';
    case PROTESE_OCULAR = 'protese_ocular';
    case FISTULA_MSE = 'fistula_mse';
    case FISTULA_MSD = 'fistula_msd';
    case FISTULA_ESQUERDA = 'fistula_esquerda';
    case BOLSA_DE_COLOSTOMIA = 'bolsa_de_colostomia';

    public function getLabel(): string
    {
        return match ($this) {
            self::CADEIRA_DE_RODAS => 'Cadeira de Rodas',
            self::ANDADOR => 'Andador',
            self::MULETA => 'Muleta',
            self::BENGALA => 'Bengala',
            self::PARAFUSOS => 'Parafusos',
            self::PERNA_MECANICA => 'Perna Mecânica',
            self::BENGALA_CANADENSE => 'Bengala Canadense',
            self::BASTAO => 'Bastão',
            self::MULETAS_AUXILIARES => 'Muletas Auxiliares',
            self::BRACO_MECANICO => 'Braço Mecânico',
            self::OCULOS_E_PROTESE => 'Óculos e Prótese',
            self::MULETA_CANADENSE => 'Muleta Canadense',
            self::PROTESE => 'Prótese',
            self::PROTESE_AURICULAR => 'Prótese Auricular',
            self::COUPAR => 'Coupar',
            self::MULETAS => 'Muletas',
            self::COLETE => 'Colete',
            self::BOTA_ORTOPEDICA => 'Bota Ortopédica',
            self::OCULOS => 'Óculos',
            self::FISTULA => 'Fístula',
            self::PROTESE_OCULAR => 'Prótese Ocular',
            self::FISTULA_MSE => 'Fístula MSE',
            self::FISTULA_MSD => 'Fístula MSD',
            self::FISTULA_ESQUERDA => 'Fístula Esquerda',
            self::BOLSA_DE_COLOSTOMIA => 'Bolsa de Colostomia',
        };
    }
}
