<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = [
            //CHUQUISACA
            ['school_name' => '8 de Septiembre', 'province_id' => 1],
            ['school_name' => 'Acción Social C', 'province_id' => 2],
            ['school_name' => 'Antonio Gausset C', 'province_id' => 3],
            ['school_name' => 'Azari', 'province_id' => 4],
            ['school_name' => 'Cardenal Maurer A', 'province_id' => 5],
            ['school_name' => 'Cristina Aitken de Gutierrez B', 'province_id' => 6],
            ['school_name' => 'San Roque', 'province_id' => 7],
            ['school_name' => 'Domingo Savio A', 'province_id' => 8],
            ['school_name' => 'Flora Quiroga de Ortuzte A', 'province_id' => 9],
            ['school_name' => 'San Xavier C', 'province_id' => 10],
            ['school_name' => 'Francisco Cermeño', 'province_id' => 11],
            //COCHABAMBA
            ['school_name' => 'Obispado de Aiquile', 'province_id'=> 12],
            ['school_name' => 'Marcelo Quiroga Santa Cruz', 'province_id'=> 13],
            ['school_name' => 'Jesús María', 'province_id'=> 14],
            ['school_name' => 'Arani A', 'province_id'=> 15],
            ['school_name' => 'Arque', 'province_id'=> 16],
            ['school_name' => 'San Juan Bautista', 'province_id'=> 17],
            ['school_name' => 'Claudina Thevenet', 'province_id'=> 18],
            ['school_name' => 'Independencia', 'province_id'=> 19],
            ['school_name' => 'Capinota', 'province_id'=> 20],
            ['school_name' => 'Conivura', 'province_id'=> 21],
            ['school_name' => 'San José Obrero', 'province_id'=> 22],
            ['school_name' => 'Jorge Trigo Andia', 'province_id'=> 23],
            ['school_name' => '27 de Mayo', 'province_id'=> 24],
            ['school_name' => 'Abaroa C', 'province_id'=> 25],
            ['school_name' => 'Americano A', 'province_id'=> 26],
            ['school_name' => 'Benjamín Iriarte Rojas', 'province_id'=> 27],
            ['school_name' => 'Bernardino Bilbao Rioja', 'province_id'=> 28],
            //BENI
            ['school_name' => 'San Jose', 'province_id'=> 29],
            ['school_name' => 'Industrial 6 de Agosto', 'province_id'=> 30],
            ['school_name' => 'Nazaria Ignacia Marchi', 'province_id'=> 31],
            ['school_name' => 'Sagrado Corazón de Jesús', 'province_id'=> 32],
            ['school_name' => 'Santisima Trinidad', 'province_id'=> 33],
            ['school_name' => 'San Francisco de Asis', 'province_id'=> 34],
            ['school_name' => 'Etha Casarabe', 'province_id'=> 35],
            ['school_name' => 'Santa Cruz', 'province_id'=> 36],
            ['school_name' => 'Mons Manuel Eguiguren', 'province_id'=> 37],
            ['school_name' => 'San Vicente II', 'province_id'=> 29],
            ['school_name' => 'Casarabe', 'province_id'=> 30],
            ['school_name' => 'Ni Coma', 'province_id'=> 31],
            ['school_name' => 'Peroto', 'province_id'=> 32],

            //LA PAZ
            ['school_name' => 'Jose Santos Vargas', 'province_id'=> 38],
            ['school_name' => 'Alto Pasankeri Sur', 'province_id'=> 39],
            ['school_name' => 'Luis Espinal Camps N°1', 'province_id'=> 40],
            ['school_name' => 'Luis Espinal Camps N°2', 'province_id'=> 41],
            ['school_name' => 'Raul Salmon de la Barra', 'province_id'=> 42],
            ['school_name' => 'San Jose I', 'province_id'=> 43],
            ['school_name' => 'San Jose II', 'province_id'=> 44],
            ['school_name' => 'San Miguel de Alpacoma', 'province_id'=> 45],
            ['school_name' => 'Marcelo Quiroga Santa Cruz', 'province_id'=> 46],
            ['school_name' => 'Hugo Chavez Frias', 'province_id'=> 47],
            ['school_name' => 'Carlos Medinecelli', 'province_id'=> 48],
            ['school_name' => '4 de Julio', 'province_id'=> 49],
            ['school_name' => 'Alto Tembladerani', 'province_id'=> 50],
            ['school_name' => 'Las Nieves', 'province_id'=> 51],
            ['school_name' => 'Ignacio Calderón Fé y Alegría I', 'province_id'=> 52],
            ['school_name' => 'Ignacio Calderón Fé y Alegría II', 'province_id'=> 53],
            ['school_name' => 'Jaime Zenobio Escalante', 'province_id'=> 54],
            ['school_name' => 'República del Japón', 'province_id'=> 55],
            ['school_name' => 'Puerto Rico', 'province_id'=> 56],
            ['school_name' => 'Humberto Vasquez Machicado', 'province_id'=> 57],
            ['school_name' => 'Cristo Rey Fé y Alegría', 'province_id'=> 58],
            ['school_name' => 'San Luis Fé y Alegría', 'province_id'=> 38],
            //ORURO
            ['school_name' => 'Poopo', 'province_id'=> 59],
            ['school_name' => 'Litoral', 'province_id'=> 60],
            ['school_name' => 'Curahuara de Carangas', 'province_id'=> 61],
            ['school_name' => 'Corque', 'province_id'=> 62],
            ['school_name' => '13 de Septiembre', 'province_id'=> 63],
            ['school_name' => 'Wiñay Cacachaca', 'province_id'=> 64],
            ['school_name' => 'Fuerzas Armadas Oruro', 'province_id'=> 65],
            ['school_name' => 'Vida Abundante', 'province_id'=> 66],
            ['school_name' => 'Adolfo Ballivian', 'province_id'=> 67],
            ['school_name' => 'Oblato', 'province_id'=> 68],
            ['school_name' => 'Maria Quiroz', 'province_id'=> 69],
            ['school_name' => 'Marcos Beltran Avila', 'province_id'=> 70],
            ['school_name' => 'Julio Ramiro Condarco Morales', 'province_id'=> 71],
            ['school_name' => 'Carmen Guzman de Mier 3', 'province_id'=> 72],
            ['school_name' => 'Simon Bolivar', 'province_id'=> 73],
            ['school_name' => 'Ignacio Leon 3', 'province_id'=> 74],
            ['school_name' => 'Simon Rodriguez Carreño', 'province_id'=> 75],
            ['school_name' => 'Boliviano Alemán', 'province_id'=> 59],
            //PANDO 76-81
            ['school_name' => 'Juan Oliveira Barros', 'province_id'=> 76],
            ['school_name' => '11 de Octubre', 'province_id'=> 77],
            ['school_name' => 'El Saber', 'province_id'=> 78],
            ['school_name' => 'Mercedes Vaca de Lanza', 'province_id'=> 79],
            ['school_name' => 'El Porvenir', 'province_id'=> 80],
            ['school_name' => '4 de Septiembre', 'province_id'=> 81],
            //POTOSI 82-98
            ['school_name' => 'Acasio', 'province_id'=> 82],
            ['school_name' => 'Arampampa', 'province_id'=> 83],
            ['school_name' => 'Malvina Jaspers', 'province_id'=> 84],
            ['school_name' => 'Marcelo Quiroca', 'province_id'=> 85],
            ['school_name' => 'Betanzos', 'province_id'=> 86],
            ['school_name' => 'Otivio', 'province_id'=> 87],
            ['school_name' => 'Canton Potobamba', 'province_id'=> 88],
            ['school_name' => 'De Junio', 'province_id'=> 89],
            ['school_name' => 'Mcal Antonio Jose de Sucre', 'province_id'=> 90],
            ['school_name' => 'Caiza D', 'province_id'=> 91],
            ['school_name' => 'Carruyo', 'province_id'=> 92],
            ['school_name' => 'Chaqui', 'province_id'=> 93],
            ['school_name' => 'Chayanta', 'province_id'=> 94],
            ['school_name' => 'Colquichaca', 'province_id'=> 95],
            ['school_name' => 'Toropalca', 'province_id'=> 96],
            ['school_name' => 'Santa Rita', 'province_id'=> 97],
            ['school_name' => 'Llallagua', 'province_id'=> 98],
            //SANTA CRUZ 99 -114
            ['school_name' => 'Colegio Alemán', 'province_id'=> 99],
            ['school_name' => 'Colegio Bautista Boliviano Brasileño', 'province_id'=> 100],
            ['school_name' => 'Colegio Boliviano Americano', 'province_id'=> 101],
            ['school_name' => 'Colegio Británico Santa Cruz', 'province_id'=> 102],
            ['school_name' => 'Colegio Cambridge College', 'province_id'=> 103],
            ['school_name' => 'Colegio Cardenal Cushing', 'province_id'=> 104],
            ['school_name' => 'Colegio Centro Boliviano Japonés', 'province_id'=> 105],
            ['school_name' => 'Colegio Don Bosco', 'province_id'=> 106],
            ['school_name' => 'Colegio Eagles School', 'province_id'=> 107],
            ['school_name' => 'Colegio Espíritu Santo', 'province_id'=> 108],
            ['school_name' => 'Colegio Internacional de la Sierra', 'province_id'=> 109],
            ['school_name' => 'Colegio Isabel Saavedra', 'province_id'=> 110],
            ['school_name' => 'Colegio La Salle', 'province_id'=> 111],
            ['school_name' => 'Colegio Marista', 'province_id'=> 112],
            ['school_name' => 'Colegio Mayor San Lorenzo', 'province_id'=> 113],
            ['school_name' => 'Colegio Mayor Santo Tomás de Aquino', 'province_id'=> 114],
            //TARIJA 115-121
            ['school_name' => 'Belgrano Adultos', 'province_id'=> 115],
            ['school_name' => 'San Roque Adultos', 'province_id'=> 116],
            ['school_name' => 'Guadalquivir', 'province_id'=> 117],
            ['school_name' => 'Alcaldía Municipal', 'province_id'=> 118],
            ['school_name' => 'Nazaria Ignacia March Adultos', 'province_id'=> 119],
            ['school_name' => 'Perpetuo Socorro', 'province_id'=> 120],
            ['school_name' => 'San Antonio', 'province_id'=> 121],
            
        ];

        DB::table('school')->insert($schools);
    }
}
