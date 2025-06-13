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
            ['school_name' => '8 de Septiembre', 'id_provincia' => 1],
            ['school_name' => 'Acción Social C', 'id_provincia' => 2],
            ['school_name' => 'Antonio Gausset C', 'id_provincia' => 3],
            ['school_name' => 'Azari', 'id_provincia' => 4],
            ['school_name' => 'Cardenal Maurer A', 'id_provincia' => 5],
            ['school_name' => 'Cristina Aitken de Gutierrez B', 'id_provincia' => 6],
            ['school_name' => 'San Roque', 'id_provincia' => 7],
            ['school_name' => 'Domingo Savio A', 'id_provincia' => 8],
            ['school_name' => 'Flora Quiroga de Ortuzte A', 'id_provincia' => 9],
            ['school_name' => 'San Xavier C', 'id_provincia' => 10],
            ['school_name' => 'Francisco Cermeño', 'id_provincia' => 11],
            //COCHABAMBA
            ['school_name' => 'Obispado de Aiquile', 'id_provincia'=> 12],
            ['school_name' => 'Marcelo Quiroga Santa Cruz', 'id_provincia'=> 13],
            ['school_name' => 'Jesús María', 'id_provincia'=> 14],
            ['school_name' => 'Arani A', 'id_provincia'=> 15],
            ['school_name' => 'Arque', 'id_provincia'=> 16],
            ['school_name' => 'San Juan Bautista', 'id_provincia'=> 17],
            ['school_name' => 'Claudina Thevenet', 'id_provincia'=> 18],
            ['school_name' => 'Independencia', 'id_provincia'=> 19],
            ['school_name' => 'Capinota', 'id_provincia'=> 20],
            ['school_name' => 'Conivura', 'id_provincia'=> 21],
            ['school_name' => 'San José Obrero', 'id_provincia'=> 22],
            ['school_name' => 'Jorge Trigo Andia', 'id_provincia'=> 23],
            ['school_name' => '27 de Mayo', 'id_provincia'=> 24],
            ['school_name' => 'Abaroa C', 'id_provincia'=> 25],
            ['school_name' => 'Americano A', 'id_provincia'=> 26],
            ['school_name' => 'Benjamín Iriarte Rojas', 'id_provincia'=> 27],
            ['school_name' => 'Bernardino Bilbao Rioja', 'id_provincia'=> 28],
            //BENI
            ['school_name' => 'San Jose', 'id_provincia'=> 29],
            ['school_name' => 'Industrial 6 de Agosto', 'id_provincia'=> 30],
            ['school_name' => 'Nazaria Ignacia Marchi', 'id_provincia'=> 31],
            ['school_name' => 'Sagrado Corazón de Jesús', 'id_provincia'=> 32],
            ['school_name' => 'Santisima Trinidad', 'id_provincia'=> 33],
            ['school_name' => 'San Francisco de Asis', 'id_provincia'=> 34],
            ['school_name' => 'Etha Casarabe', 'id_provincia'=> 35],
            ['school_name' => 'Santa Cruz', 'id_provincia'=> 36],
            ['school_name' => 'Mons Manuel Eguiguren', 'id_provincia'=> 37],
            ['school_name' => 'San Vicente II', 'id_provincia'=> 29],
            ['school_name' => 'Casarabe', 'id_provincia'=> 30],
            ['school_name' => 'Ni Coma', 'id_provincia'=> 31],
            ['school_name' => 'Peroto', 'id_provincia'=> 32],

            //LA PAZ
            ['school_name' => 'Jose Santos Vargas', 'id_provincia'=> 38],
            ['school_name' => 'Alto Pasankeri Sur', 'id_provincia'=> 39],
            ['school_name' => 'Luis Espinal Camps N°1', 'id_provincia'=> 40],
            ['school_name' => 'Luis Espinal Camps N°2', 'id_provincia'=> 41],
            ['school_name' => 'Raul Salmon de la Barra', 'id_provincia'=> 42],
            ['school_name' => 'San Jose I', 'id_provincia'=> 43],
            ['school_name' => 'San Jose II', 'id_provincia'=> 44],
            ['school_name' => 'San Miguel de Alpacoma', 'id_provincia'=> 45],
            ['school_name' => 'Marcelo Quiroga Santa Cruz', 'id_provincia'=> 46],
            ['school_name' => 'Hugo Chavez Frias', 'id_provincia'=> 47],
            ['school_name' => 'Carlos Medinecelli', 'id_provincia'=> 48],
            ['school_name' => '4 de Julio', 'id_provincia'=> 49],
            ['school_name' => 'Alto Tembladerani', 'id_provincia'=> 50],
            ['school_name' => 'Las Nieves', 'id_provincia'=> 51],
            ['school_name' => 'Ignacio Calderón Fé y Alegría I', 'id_provincia'=> 52],
            ['school_name' => 'Ignacio Calderón Fé y Alegría II', 'id_provincia'=> 53],
            ['school_name' => 'Jaime Zenobio Escalante', 'id_provincia'=> 54],
            ['school_name' => 'República del Japón', 'id_provincia'=> 55],
            ['school_name' => 'Puerto Rico', 'id_provincia'=> 56],
            ['school_name' => 'Humberto Vasquez Machicado', 'id_provincia'=> 57],
            ['school_name' => 'Cristo Rey Fé y Alegría', 'id_provincia'=> 58],
            ['school_name' => 'San Luis Fé y Alegría', 'id_provincia'=> 38],
            //ORURO
            ['school_name' => 'Poopo', 'id_provincia'=> 59],
            ['school_name' => 'Litoral', 'id_provincia'=> 60],
            ['school_name' => 'Curahuara de Carangas', 'id_provincia'=> 61],
            ['school_name' => 'Corque', 'id_provincia'=> 62],
            ['school_name' => '13 de Septiembre', 'id_provincia'=> 63],
            ['school_name' => 'Wiñay Cacachaca', 'id_provincia'=> 64],
            ['school_name' => 'Fuerzas Armadas Oruro', 'id_provincia'=> 65],
            ['school_name' => 'Vida Abundante', 'id_provincia'=> 66],
            ['school_name' => 'Adolfo Ballivian', 'id_provincia'=> 67],
            ['school_name' => 'Oblato', 'id_provincia'=> 68],
            ['school_name' => 'Maria Quiroz', 'id_provincia'=> 69],
            ['school_name' => 'Marcos Beltran Avila', 'id_provincia'=> 70],
            ['school_name' => 'Julio Ramiro Condarco Morales', 'id_provincia'=> 71],
            ['school_name' => 'Carmen Guzman de Mier 3', 'id_provincia'=> 72],
            ['school_name' => 'Simon Bolivar', 'id_provincia'=> 73],
            ['school_name' => 'Ignacio Leon 3', 'id_provincia'=> 74],
            ['school_name' => 'Simon Rodriguez Carreño', 'id_provincia'=> 75],
            ['school_name' => 'Boliviano Alemán', 'id_provincia'=> 59],
            //PANDO 76-81
            ['school_name' => 'Juan Oliveira Barros', 'id_provincia'=> 76],
            ['school_name' => '11 de Octubre', 'id_provincia'=> 77],
            ['school_name' => 'El Saber', 'id_provincia'=> 78],
            ['school_name' => 'Mercedes Vaca de Lanza', 'id_provincia'=> 79],
            ['school_name' => 'El Porvenir', 'id_provincia'=> 80],
            ['school_name' => '4 de Septiembre', 'id_provincia'=> 81],
            //POTOSI 82-98
            ['school_name' => 'Acasio', 'id_provincia'=> 82],
            ['school_name' => 'Arampampa', 'id_provincia'=> 83],
            ['school_name' => 'Malvina Jaspers', 'id_provincia'=> 84],
            ['school_name' => 'Marcelo Quiroca', 'id_provincia'=> 85],
            ['school_name' => 'Betanzos', 'id_provincia'=> 86],
            ['school_name' => 'Otivio', 'id_provincia'=> 87],
            ['school_name' => 'Canton Potobamba', 'id_provincia'=> 88],
            ['school_name' => 'De Junio', 'id_provincia'=> 89],
            ['school_name' => 'Mcal Antonio Jose de Sucre', 'id_provincia'=> 90],
            ['school_name' => 'Caiza D', 'id_provincia'=> 91],
            ['school_name' => 'Carruyo', 'id_provincia'=> 92],
            ['school_name' => 'Chaqui', 'id_provincia'=> 93],
            ['school_name' => 'Chayanta', 'id_provincia'=> 94],
            ['school_name' => 'Colquichaca', 'id_provincia'=> 95],
            ['school_name' => 'Toropalca', 'id_provincia'=> 96],
            ['school_name' => 'Santa Rita', 'id_provincia'=> 97],
            ['school_name' => 'Llallagua', 'id_provincia'=> 98],
            //SANTA CRUZ 99 -114
            ['school_name' => 'Colegio Alemán', 'id_provincia'=> 99],
            ['school_name' => 'Colegio Bautista Boliviano Brasileño', 'id_provincia'=> 100],
            ['school_name' => 'Colegio Boliviano Americano', 'id_provincia'=> 101],
            ['school_name' => 'Colegio Británico Santa Cruz', 'id_provincia'=> 102],
            ['school_name' => 'Colegio Cambridge College', 'id_provincia'=> 103],
            ['school_name' => 'Colegio Cardenal Cushing', 'id_provincia'=> 104],
            ['school_name' => 'Colegio Centro Boliviano Japonés', 'id_provincia'=> 105],
            ['school_name' => 'Colegio Don Bosco', 'id_provincia'=> 106],
            ['school_name' => 'Colegio Eagles School', 'id_provincia'=> 107],
            ['school_name' => 'Colegio Espíritu Santo', 'id_provincia'=> 108],
            ['school_name' => 'Colegio Internacional de la Sierra', 'id_provincia'=> 109],
            ['school_name' => 'Colegio Isabel Saavedra', 'id_provincia'=> 110],
            ['school_name' => 'Colegio La Salle', 'id_provincia'=> 111],
            ['school_name' => 'Colegio Marista', 'id_provincia'=> 112],
            ['school_name' => 'Colegio Mayor San Lorenzo', 'id_provincia'=> 113],
            ['school_name' => 'Colegio Mayor Santo Tomás de Aquino', 'id_provincia'=> 114],
            //TARIJA 115-121
            ['school_name' => 'Belgrano Adultos', 'id_provincia'=> 115],
            ['school_name' => 'San Roque Adultos', 'id_provincia'=> 116],
            ['school_name' => 'Guadalquivir', 'id_provincia'=> 117],
            ['school_name' => 'Alcaldía Municipal', 'id_provincia'=> 118],
            ['school_name' => 'Nazaria Ignacia March Adultos', 'id_provincia'=> 119],
            ['school_name' => 'Perpetuo Socorro', 'id_provincia'=> 120],
            ['school_name' => 'San Antonio', 'id_provincia'=> 121],
            
        ];

        DB::table('schools')->insert($schools);
    }
}
