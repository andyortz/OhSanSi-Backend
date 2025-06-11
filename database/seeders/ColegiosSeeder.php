<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColegiosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colegios = [
            //CHUQUISACA
            ['nombre_colegio' => '8 DE SEPTIEMBRE', 'id_provincia' => 1],
            ['nombre_colegio' => 'ACCION SOCIAL C', 'id_provincia' => 2],
            ['nombre_colegio' => 'ANTONIO GAUSSET C', 'id_provincia' => 3],
            ['nombre_colegio' => 'AZARI', 'id_provincia' => 4],
            ['nombre_colegio' => 'CARDENAL MAURER A', 'id_provincia' => 5],
            ['nombre_colegio' => 'CRISTINA AITKEN DE GUTIERREZ B', 'id_provincia' => 6],
            ['nombre_colegio' => 'SAN ROQUE', 'id_provincia' => 7],
            ['nombre_colegio' => 'DOMINGO SAVIO A', 'id_provincia' => 8],
            ['nombre_colegio' => 'FLORA QUIROGA DE ORTUZTE A', 'id_provincia' => 9],
            ['nombre_colegio' => 'SAN XAVIER C', 'id_provincia' => 10],
            ['nombre_colegio' => 'FRANCISCO CERMENO', 'id_provincia' => 11],
            //COCHABAMBA
            ['nombre_colegio' => 'OBISPADO DE AIQUILE', 'id_provincia'=> 12],
            ['nombre_colegio' => 'MARCELO QUIROGA SANTA CRUZ', 'id_provincia'=> 13],
            ['nombre_colegio' => 'JESUS MARIA', 'id_provincia'=> 14],
            ['nombre_colegio' => 'ARANI A', 'id_provincia'=> 15],
            ['nombre_colegio' => 'ARQUE', 'id_provincia'=> 16],
            ['nombre_colegio' => 'SAN JUAN BAUTISTA', 'id_provincia'=> 17],
            ['nombre_colegio' => 'CLAUDINA THEVENET', 'id_provincia'=> 18],
            ['nombre_colegio' => 'INDEPENDENCIA', 'id_provincia'=> 19],
            ['nombre_colegio' => 'CAPINOTA', 'id_provincia'=> 20],
            ['nombre_colegio' => 'CONIVURA', 'id_provincia'=> 21],
            ['nombre_colegio' => 'SAN JOSE OBRERO', 'id_provincia'=> 22],
            ['nombre_colegio' => 'JORGE TRIGO ANDIA', 'id_provincia'=> 23],
            ['nombre_colegio' => '27 DE MAYO', 'id_provincia'=> 24],
            ['nombre_colegio' => 'ABAROA C', 'id_provincia'=> 25],
            ['nombre_colegio' => 'AMERICANO A', 'id_provincia'=> 26],
            ['nombre_colegio' => 'BENJAMIN IRIARTE ROJAS', 'id_provincia'=> 27],
            ['nombre_colegio' => 'BERNARDINO BILBAO RIOJA', 'id_provincia'=> 28],
            //BENI
            ['nombre_colegio' => 'SAN JOSE', 'id_provincia'=> 29],
            ['nombre_colegio' => 'INDUSTRIAL 6 DE AGOSTO', 'id_provincia'=> 30],
            ['nombre_colegio' => 'NAZARIA IGNACIA MARCHI', 'id_provincia'=> 31],
            ['nombre_colegio' => 'SAGRADO CORAZON DE JESUS', 'id_provincia'=> 32],
            ['nombre_colegio' => 'SANTISIMA TRINIDAD', 'id_provincia'=> 33],
            ['nombre_colegio' => 'SAN FRANCISCO DE ASIS', 'id_provincia'=> 34],
            ['nombre_colegio' => 'ETHA CASARABE', 'id_provincia'=> 35],
            ['nombre_colegio' => 'SANTA CRUZ', 'id_provincia'=> 36],
            ['nombre_colegio' => 'MONS MANUEL EGUIGUREN', 'id_provincia'=> 37],
            ['nombre_colegio' => 'SAN VICENTE II', 'id_provincia'=> 29],
            ['nombre_colegio' => 'CASARABE', 'id_provincia'=> 30],
            ['nombre_colegio' => 'NI COMA', 'id_provincia'=> 31],
            ['nombre_colegio' => 'PEROTO', 'id_provincia'=> 32],

            //LA PAZ
            ['nombre_colegio' => 'JOSE SANTOS VARGAS', 'id_provincia'=> 38],
            ['nombre_colegio' => 'ALTO PASANKERI SUR', 'id_provincia'=> 39],
            ['nombre_colegio' => 'LUIS ESPINAL CAMPS N°1', 'id_provincia'=> 40],
            ['nombre_colegio' => 'LUIS ESPINAL CAMPS N°2', 'id_provincia'=> 41],
            ['nombre_colegio' => 'RAUL SALMON DE LA BARRA', 'id_provincia'=> 42],
            ['nombre_colegio' => 'SAN JOSE I', 'id_provincia'=> 43],
            ['nombre_colegio' => 'SAN JOSE II', 'id_provincia'=> 44],
            ['nombre_colegio' => 'SAN MIGUEL DE ALPACOMA', 'id_provincia'=> 45],
            ['nombre_colegio' => 'MARCELO QUIROGA SANTA CRUZ', 'id_provincia'=> 46],
            ['nombre_colegio' => 'HUGO CHAVEZ FRIAS', 'id_provincia'=> 47],
            ['nombre_colegio' => 'CARLOS MEDINECELLI', 'id_provincia'=> 48],
            ['nombre_colegio' => '4 DE JULIO', 'id_provincia'=> 49],
            ['nombre_colegio' => 'ALTO TEMBLADERANI', 'id_provincia'=> 50],
            ['nombre_colegio' => 'LAS NIEVES', 'id_provincia'=> 51],
            ['nombre_colegio' => 'IGNACIO CALDERON FE Y ALEGRIA I', 'id_provincia'=> 52],
            ['nombre_colegio' => 'IGNACIO CALDERON FE Y ALEGRIA II', 'id_provincia'=> 53],
            ['nombre_colegio' => 'JAIME ZENOBIO ESCALANTE', 'id_provincia'=> 54],
            ['nombre_colegio' => 'REPUBLICA DEL JAPON', 'id_provincia'=> 55],
            ['nombre_colegio' => 'PUERTO RICO', 'id_provincia'=> 56],
            ['nombre_colegio' => 'HUMBERTO VASQUEZ MACHICADO', 'id_provincia'=> 57],
            ['nombre_colegio' => 'CRISTO REY FE Y ALEGRIA', 'id_provincia'=> 58],
            ['nombre_colegio' => 'SAN LUIS FE Y ALEGRIA', 'id_provincia'=> 38],
            //ORURO
            ['nombre_colegio' => 'POOPO', 'id_provincia'=> 59],
            ['nombre_colegio' => 'LITORAL', 'id_provincia'=> 60],
            ['nombre_colegio' => 'CURAHUARA DE CARANGAS', 'id_provincia'=> 61],
            ['nombre_colegio' => 'CORQUE', 'id_provincia'=> 62],
            ['nombre_colegio' => '13 DE SEPTIEMBRE', 'id_provincia'=> 63],
            ['nombre_colegio' => 'WINAY CACACHACA', 'id_provincia'=> 64],
            ['nombre_colegio' => 'FUERZAS ARMADAS ORURO', 'id_provincia'=> 65],
            ['nombre_colegio' => 'VIDA ABUNDANTE', 'id_provincia'=> 66],
            ['nombre_colegio' => 'ADOLFO BALLIVIAN', 'id_provincia'=> 67],
            ['nombre_colegio' => 'OBLATO', 'id_provincia'=> 68],
            ['nombre_colegio' => 'MARIA QUIROZ', 'id_provincia'=> 69],
            ['nombre_colegio' => 'MARCOS BELTRAN AVILA', 'id_provincia'=> 70],
            ['nombre_colegio' => 'JULIO RAMIRO CONDARCO MORALES', 'id_provincia'=> 71],
            ['nombre_colegio' => 'CARMEN GUZMAN DE MIER 3', 'id_provincia'=> 72],
            ['nombre_colegio' => 'SIMON BOLIVAR', 'id_provincia'=> 73],
            ['nombre_colegio' => 'IGNACIO LEON 3', 'id_provincia'=> 74],
            ['nombre_colegio' => 'SIMON RODRIGUEZ CARREÑO', 'id_provincia'=> 75],
            ['nombre_colegio' => 'BOLIVIANO ALEMAN', 'id_provincia'=> 59],
            //PANDO 76-81
            ['nombre_colegio' => 'JUAN OLIVEIRA BARROS', 'id_provincia'=> 76],
            ['nombre_colegio' => '11 DE OCTUBRE', 'id_provincia'=> 77],
            ['nombre_colegio' => 'EL SABER', 'id_provincia'=> 78],
            ['nombre_colegio' => 'MERCEDES VACA DE LANZA', 'id_provincia'=> 79],
            ['nombre_colegio' => 'EL PORVENIR', 'id_provincia'=> 80],
            ['nombre_colegio' => '4 DE SEPTIEMBRE', 'id_provincia'=> 81],
            //POTOSI 82-98
            ['nombre_colegio' => 'ACASIO', 'id_provincia'=> 82],
            ['nombre_colegio' => 'ARAMPAMPA', 'id_provincia'=> 83],
            ['nombre_colegio' => 'MALVINA JASPERS', 'id_provincia'=> 84],
            ['nombre_colegio' => 'MARCELO QUIROCA', 'id_provincia'=> 85],
            ['nombre_colegio' => 'BETANZOS', 'id_provincia'=> 86],
            ['nombre_colegio' => 'OTIVIO', 'id_provincia'=> 87],
            ['nombre_colegio' => 'CANTON POTO BAMBA', 'id_provincia'=> 88],
            ['nombre_colegio' => 'DE JUNIO', 'id_provincia'=> 89],
            ['nombre_colegio' => 'TURUCHI', 'id_provincia'=> 90],
            ['nombre_colegio' => 'SAN ANTONIO', 'id_provincia'=> 91],
            ['nombre_colegio' => 'PANDURO', 'id_provincia'=> 92],
            ['nombre_colegio' => 'MAURICIO', 'id_provincia'=> 93],
            ['nombre_colegio' => 'JULIO APERO', 'id_provincia'=> 94],
            ['nombre_colegio' => 'SAN FELIPE', 'id_provincia'=> 95],
            ['nombre_colegio' => 'SANTO DOMINGO', 'id_provincia'=> 96],
            ['nombre_colegio' => '15 DE AGOSTO', 'id_provincia'=> 97],
            ['nombre_colegio' => 'JUAN DE LA CRUZ', 'id_provincia'=> 98],
        ];

        DB::table('colegio')->insert($colegios);
    }
}
