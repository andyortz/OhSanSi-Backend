<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = [
            //CHUQUISACA
            ['school_name' => '8 de Septiembre', 'id_province' => 1],
            ['school_name' => 'Acción Social C', 'id_province' => 2],
            ['school_name' => 'Antonio Gausset C', 'id_province' => 3],
            ['school_name' => 'Azari', 'id_province' => 4],
            ['school_name' => 'Cardenal Maurer A', 'id_province' => 5],
            ['school_name' => 'Cristina Aitken de Gutierrez B', 'id_province' => 6],
            ['school_name' => 'San Roque', 'id_province' => 7],
            ['school_name' => 'Domingo Savio A', 'id_province' => 8],
            ['school_name' => 'Flora Quiroga de Ortuzte A', 'id_province' => 9],
            ['school_name' => 'San Xavier C', 'id_province' => 10],
            ['school_name' => 'Francisco Cermeño', 'id_province' => 11],
            //COCHABAMBA
            ['school_name' => 'Obispado de Aiquile', 'id_province'=> 12],
            ['school_name' => 'Marcelo Quiroga Santa Cruz', 'id_province'=> 13],
            ['school_name' => 'Jesús María', 'id_province'=> 14],
            ['school_name' => 'Arani A', 'id_province'=> 15],
            ['school_name' => 'Arque', 'id_province'=> 16],
            ['school_name' => 'San Juan Bautista', 'id_province'=> 17],
            ['school_name' => 'Claudina Thevenet', 'id_province'=> 18],
            ['school_name' => 'Independencia', 'id_province'=> 19],
            ['school_name' => 'Capinota', 'id_province'=> 20],
            ['school_name' => 'Conivura', 'id_province'=> 21],
            ['school_name' => 'San José Obrero', 'id_province'=> 22],
            ['school_name' => 'Jorge Trigo Andia', 'id_province'=> 23],
            ['school_name' => '27 de Mayo', 'id_province'=> 24],
            ['school_name' => 'Abaroa C', 'id_province'=> 25],
            ['school_name' => 'Americano A', 'id_province'=> 26],
            ['school_name' => 'Benjamín Iriarte Rojas', 'id_province'=> 27],
            ['school_name' => 'Bernardino Bilbao Rioja', 'id_province'=> 28],
            //BENI
            ['school_name' => 'San Jose', 'id_province'=> 29],
            ['school_name' => 'Industrial 6 de Agosto', 'id_province'=> 30],
            ['school_name' => 'Nazaria Ignacia Marchi', 'id_province'=> 31],
            ['school_name' => 'Sagrado Corazón de Jesús', 'id_province'=> 32],
            ['school_name' => 'Santisima Trinidad', 'id_province'=> 33],
            ['school_name' => 'San Francisco de Asis', 'id_province'=> 34],
            ['school_name' => 'Etha Casarabe', 'id_province'=> 35],
            ['school_name' => 'Santa Cruz', 'id_province'=> 36],
            ['school_name' => 'Mons Manuel Eguiguren', 'id_province'=> 37],
            ['school_name' => 'San Vicente II', 'id_province'=> 29],
            ['school_name' => 'Casarabe', 'id_province'=> 30],
            ['school_name' => 'Ni Coma', 'id_province'=> 31],
            ['school_name' => 'Peroto', 'id_province'=> 32],

            //LA PAZ
            ['school_name' => 'Jose Santos Vargas', 'id_province'=> 38],
            ['school_name' => 'Alto Pasankeri Sur', 'id_province'=> 39],
            ['school_name' => 'Luis Espinal Camps N°1', 'id_province'=> 40],
            ['school_name' => 'Luis Espinal Camps N°2', 'id_province'=> 41],
            ['school_name' => 'Raul Salmon de la Barra', 'id_province'=> 42],
            ['school_name' => 'San Jose I', 'id_province'=> 43],
            ['school_name' => 'San Jose II', 'id_province'=> 44],
            ['school_name' => 'San Miguel de Alpacoma', 'id_province'=> 45],
            ['school_name' => 'Marcelo Quiroga Santa Cruz', 'id_province'=> 46],
            ['school_name' => 'Hugo Chavez Frias', 'id_province'=> 47],
            ['school_name' => 'Carlos Medinecelli', 'id_province'=> 48],
            ['school_name' => '4 de Julio', 'id_province'=> 49],
            ['school_name' => 'Alto Tembladerani', 'id_province'=> 50],
            ['school_name' => 'Las Nieves', 'id_province'=> 51],
            ['school_name' => 'Ignacio Calderón Fé y Alegría I', 'id_province'=> 52],
            ['school_name' => 'Ignacio Calderón Fé y Alegría II', 'id_province'=> 53],
            ['school_name' => 'Jaime Zenobio Escalante', 'id_province'=> 54],
            ['school_name' => 'República del Japón', 'id_province'=> 55],
            ['school_name' => 'Puerto Rico', 'id_province'=> 56],
            ['school_name' => 'Humberto Vasquez Machicado', 'id_province'=> 57],
            ['school_name' => 'Cristo Rey Fé y Alegría', 'id_province'=> 58],
            ['school_name' => 'San Luis Fé y Alegría', 'id_province'=> 38],
            //ORURO
            ['school_name' => 'Poopo', 'id_province'=> 59],
            ['school_name' => 'Litoral', 'id_province'=> 60],
            ['school_name' => 'Curahuara de Carangas', 'id_province'=> 61],
            ['school_name' => 'Corque', 'id_province'=> 62],
            ['school_name' => '13 de Septiembre', 'id_province'=> 63],
            ['school_name' => 'Wiñay Cacachaca', 'id_province'=> 64],
            ['school_name' => 'Fuerzas Armadas Oruro', 'id_province'=> 65],
            ['school_name' => 'Vida Abundante', 'id_province'=> 66],
            ['school_name' => 'Adolfo Ballivian', 'id_province'=> 67],
            ['school_name' => 'Oblato', 'id_province'=> 68],
            ['school_name' => 'Maria Quiroz', 'id_province'=> 69],
            ['school_name' => 'Marcos Beltran Avila', 'id_province'=> 70],
            ['school_name' => 'Julio Ramiro Condarco Morales', 'id_province'=> 71],
            ['school_name' => 'Carmen Guzman de Mier 3', 'id_province'=> 72],
            ['school_name' => 'Simon Bolivar', 'id_province'=> 73],
            ['school_name' => 'Ignacio Leon 3', 'id_province'=> 74],
            ['school_name' => 'Simon Rodriguez Carreño', 'id_province'=> 75],
            ['school_name' => 'Boliviano Alemán', 'id_province'=> 59],
            //PANDO 76-81
            ['school_name' => 'Juan Oliveira Barros', 'id_province'=> 76],
            ['school_name' => '11 de Octubre', 'id_province'=> 77],
            ['school_name' => 'El Saber', 'id_province'=> 78],
            ['school_name' => 'Mercedes Vaca de Lanza', 'id_province'=> 79],
            ['school_name' => 'El Porvenir', 'id_province'=> 80],
            ['school_name' => '4 de Septiembre', 'id_province'=> 81],
            //POTOSI 82-98
            ['school_name' => 'Acasio', 'id_province'=> 82],
            ['school_name' => 'Arampampa', 'id_province'=> 83],
            ['school_name' => 'Malvina Jaspers', 'id_province'=> 84],
            ['school_name' => 'Marcelo Quiroca', 'id_province'=> 85],
            ['school_name' => 'Betanzos', 'id_province'=> 86],
            ['school_name' => 'Otivio', 'id_province'=> 87],
            ['school_name' => 'Canton Potobamba', 'id_province'=> 88],
            ['school_name' => 'De Junio', 'id_province'=> 89],
            ['school_name' => 'Mcal Antonio Jose de Sucre', 'id_province'=> 90],
            ['school_name' => 'Caiza D', 'id_province'=> 91],
            ['school_name' => 'Carruyo', 'id_province'=> 92],
            ['school_name' => 'Chaqui', 'id_province'=> 93],
            ['school_name' => 'Chayanta', 'id_province'=> 94],
            ['school_name' => 'Colquichaca', 'id_province'=> 95],
            ['school_name' => 'Toropalca', 'id_province'=> 96],
            ['school_name' => 'Santa Rita', 'id_province'=> 97],
            ['school_name' => 'Llallagua', 'id_province'=> 98],
            //SANTA CRUZ 99 -114
            ['school_name' => 'Colegio Alemán', 'id_province'=> 99],
            ['school_name' => 'Colegio Bautista Boliviano Brasileño', 'id_province'=> 100],
            ['school_name' => 'Colegio Boliviano Americano', 'id_province'=> 101],
            ['school_name' => 'Colegio Británico Santa Cruz', 'id_province'=> 102],
            ['school_name' => 'Colegio Cambridge College', 'id_province'=> 103],
            ['school_name' => 'Colegio Cardenal Cushing', 'id_province'=> 104],
            ['school_name' => 'Colegio Centro Boliviano Japonés', 'id_province'=> 105],
            ['school_name' => 'Colegio Don Bosco', 'id_province'=> 106],
            ['school_name' => 'Colegio Eagles School', 'id_province'=> 107],
            ['school_name' => 'Colegio Espíritu Santo', 'id_province'=> 108],
            ['school_name' => 'Colegio Internacional de la Sierra', 'id_province'=> 109],
            ['school_name' => 'Colegio Isabel Saavedra', 'id_province'=> 110],
            ['school_name' => 'Colegio La Salle', 'id_province'=> 111],
            ['school_name' => 'Colegio Marista', 'id_province'=> 112],
            ['school_name' => 'Colegio Mayor San Lorenzo', 'id_province'=> 113],
            ['school_name' => 'Colegio Mayor Santo Tomás de Aquino', 'id_province'=> 114],
            //TARIJA 115-121
            ['school_name' => 'Belgrano Adultos', 'id_province'=> 115],
            ['school_name' => 'San Roque Adultos', 'id_province'=> 116],
            ['school_name' => 'Guadalquivir', 'id_province'=> 117],
            ['school_name' => 'Alcaldía Municipal', 'id_province'=> 118],
            ['school_name' => 'Nazaria Ignacia March Adultos', 'id_province'=> 119],
            ['school_name' => 'Perpetuo Socorro', 'id_province'=> 120],
            ['school_name' => 'San Antonio', 'id_province'=> 121],
            
        ];

        DB::table('school')->insert($schools);
    }
}
