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
            ['nombre_colegio' => '8 de Septiembre', 'provincia' => 1],
            ['nombre_colegio' => 'Acción Social C', 'provincia' => 2],
            ['nombre_colegio' => 'Antonio Gausset C', 'provincia' => 3],
            ['nombre_colegio' => 'Azari', 'provincia' => 4],
            ['nombre_colegio' => 'Cardenal Maurer A', 'provincia' => 5],
            ['nombre_colegio' => 'Cristina Aitken de Gutierrez B', 'provincia' => 6],
            ['nombre_colegio' => 'San Roque', 'provincia' => 7],
            ['nombre_colegio' => 'Domingo Savio A', 'provincia' => 8],
            ['nombre_colegio' => 'Flora Quiroga de Ortuzte A', 'provincia' => 9],
            ['nombre_colegio' => 'San Xavier C', 'provincia' => 10],
            ['nombre_colegio' => 'Francisco Cermeño', 'provincia' => 11],
            //COCHABAMBA
            ['nombre_colegio' => 'Obispado de Aiquile', 'provincia'=> 12],
            ['nombre_colegio' => 'Marcelo Quiroga Santa Cruz', 'provincia'=> 13],
            ['nombre_colegio' => 'Jesús María', 'provincia'=> 14],
            ['nombre_colegio' => 'Arani A', 'provincia'=> 15],
            ['nombre_colegio' => 'Arque', 'provincia'=> 16],
            ['nombre_colegio' => 'San Juan Bautista', 'provincia'=> 17],
            ['nombre_colegio' => 'Claudina Thevenet', 'provincia'=> 18],
            ['nombre_colegio' => 'Independencia', 'provincia'=> 19],
            ['nombre_colegio' => 'Capinota', 'provincia'=> 20],
            ['nombre_colegio' => 'Conivura', 'provincia'=> 21],
            ['nombre_colegio' => 'San José Obrero', 'provincia'=> 22],
            ['nombre_colegio' => 'Jorge Trigo Andia', 'provincia'=> 23],
            ['nombre_colegio' => '27 de Mayo', 'provincia'=> 24],
            ['nombre_colegio' => 'Abaroa C', 'provincia'=> 25],
            ['nombre_colegio' => 'Americano A', 'provincia'=> 26],
            ['nombre_colegio' => 'Benjamín Iriarte Rojas', 'provincia'=> 27],
            ['nombre_colegio' => 'Bernardino Bilbao Rioja', 'provincia'=> 28],
            //BENI
            ['nombre_colegio' => 'San Jose', 'provincia'=> 29],
            ['nombre_colegio' => 'Industrial 6 de Agosto', 'provincia'=> 30],
            ['nombre_colegio' => 'Nazaria Ignacia Marchi', 'provincia'=> 31],
            ['nombre_colegio' => 'Sagrado Corazón de Jesús', 'provincia'=> 32],
            ['nombre_colegio' => 'Santisima Trinidad', 'provincia'=> 33],
            ['nombre_colegio' => 'San Francisco de Asis', 'provincia'=> 34],
            ['nombre_colegio' => 'Etha Casarabe', 'provincia'=> 35],
            ['nombre_colegio' => 'Santa Cruz', 'provincia'=> 36],
            ['nombre_colegio' => 'Mons Manuel Eguiguren', 'provincia'=> 37],
            ['nombre_colegio' => 'San Vicente II', 'provincia'=> 29],
            ['nombre_colegio' => 'Casarabe', 'provincia'=> 30],
            ['nombre_colegio' => 'Ni Coma', 'provincia'=> 31],
            ['nombre_colegio' => 'Peroto', 'provincia'=> 32],

            //LA PAZ
            ['nombre_colegio' => 'Jose Santos Vargas', 'provincia'=> 38],
            ['nombre_colegio' => 'Alto Pasankeri Sur', 'provincia'=> 39],
            ['nombre_colegio' => 'Luis Espinal Camps N°1', 'provincia'=> 40],
            ['nombre_colegio' => 'Luis Espinal Camps N°2', 'provincia'=> 41],
            ['nombre_colegio' => 'Raul Salmon de la Barra', 'provincia'=> 42],
            ['nombre_colegio' => 'San Jose I', 'provincia'=> 43],
            ['nombre_colegio' => 'San Jose II', 'provincia'=> 44],
            ['nombre_colegio' => 'San Miguel de Alpacoma', 'provincia'=> 45],
            ['nombre_colegio' => 'Marcelo Quiroga Santa Cruz', 'provincia'=> 46],
            ['nombre_colegio' => 'Hugo Chavez Frias', 'provincia'=> 47],
            ['nombre_colegio' => 'Carlos Medinecelli', 'provincia'=> 48],
            ['nombre_colegio' => '4 de Julio', 'provincia'=> 49],
            ['nombre_colegio' => 'Alto Tembladerani', 'provincia'=> 50],
            ['nombre_colegio' => 'Las Nieves', 'provincia'=> 51],
            ['nombre_colegio' => 'Ignacio Calderón Fé y Alegría I', 'provincia'=> 52],
            ['nombre_colegio' => 'Ignacio Calderón Fé y Alegría II', 'provincia'=> 53],
            ['nombre_colegio' => 'Jaime Zenobio Escalante', 'provincia'=> 54],
            ['nombre_colegio' => 'República del Japón', 'provincia'=> 55],
            ['nombre_colegio' => 'Puerto Rico', 'provincia'=> 56],
            ['nombre_colegio' => 'Humberto Vasquez Machicado', 'provincia'=> 57],
            ['nombre_colegio' => 'Cristo Rey Fé y Alegría', 'provincia'=> 58],
            ['nombre_colegio' => 'San Luis Fé y Alegría', 'provincia'=> 38],
            //ORURO
            ['nombre_colegio' => 'Poopo', 'provincia'=> 59],
            ['nombre_colegio' => 'Litoral', 'provincia'=> 60],
            ['nombre_colegio' => 'Curahuara de Carangas', 'provincia'=> 61],
            ['nombre_colegio' => 'Corque', 'provincia'=> 62],
            ['nombre_colegio' => '13 de Septiembre', 'provincia'=> 63],
            ['nombre_colegio' => 'Wiñay Cacachaca', 'provincia'=> 64],
            ['nombre_colegio' => 'Fuerzas Armadas Oruro', 'provincia'=> 65],
            ['nombre_colegio' => 'Vida Abundante', 'provincia'=> 66],
            ['nombre_colegio' => 'Adolfo Ballivian', 'provincia'=> 67],
            ['nombre_colegio' => 'Oblato', 'provincia'=> 68],
            ['nombre_colegio' => 'Maria Quiroz', 'provincia'=> 69],
            ['nombre_colegio' => 'Marcos Beltran Avila', 'provincia'=> 70],
            ['nombre_colegio' => 'Julio Ramiro Condarco Morales', 'provincia'=> 71],
            ['nombre_colegio' => 'Carmen Guzman de Mier 3', 'provincia'=> 72],
            ['nombre_colegio' => 'Simon Bolivar', 'provincia'=> 73],
            ['nombre_colegio' => 'Ignacio Leon 3', 'provincia'=> 74],
            ['nombre_colegio' => 'Simon Rodriguez Carreño', 'provincia'=> 75],
            ['nombre_colegio' => 'Boliviano Alemán', 'provincia'=> 59],
            //PANDO 76-81
            ['nombre_colegio' => 'Juan Oliveira Barros', 'provincia'=> 76],
            ['nombre_colegio' => '11 de Octubre', 'provincia'=> 77],
            ['nombre_colegio' => 'El Saber', 'provincia'=> 78],
            ['nombre_colegio' => 'Mercedes Vaca de Lanza', 'provincia'=> 79],
            ['nombre_colegio' => 'El Porvenir', 'provincia'=> 80],
            ['nombre_colegio' => '4 de Septiembre', 'provincia'=> 81],
            //POTOSI 82-98
            ['nombre_colegio' => 'Acasio', 'provincia'=> 82],
            ['nombre_colegio' => 'Arampampa', 'provincia'=> 83],
            ['nombre_colegio' => 'Malvina Jaspers', 'provincia'=> 84],
            ['nombre_colegio' => 'Marcelo Quiroca', 'provincia'=> 85],
            ['nombre_colegio' => 'Betanzos', 'provincia'=> 86],
            ['nombre_colegio' => 'Otivio', 'provincia'=> 87],
            ['nombre_colegio' => 'Canton Potobamba', 'provincia'=> 88],
            ['nombre_colegio' => 'De Junio', 'provincia'=> 89],
            ['nombre_colegio' => 'Mcal Antonio Jose de Sucre', 'provincia'=> 90],
            ['nombre_colegio' => 'Caiza D', 'provincia'=> 91],
            ['nombre_colegio' => 'Carruyo', 'provincia'=> 92],
            ['nombre_colegio' => 'Chaqui', 'provincia'=> 93],
            ['nombre_colegio' => 'Chayanta', 'provincia'=> 94],
            ['nombre_colegio' => 'Colquichaca', 'provincia'=> 95],
            ['nombre_colegio' => 'Toropalca', 'provincia'=> 96],
            ['nombre_colegio' => 'Santa Rita', 'provincia'=> 97],
            ['nombre_colegio' => 'Llallagua', 'provincia'=> 98],
            //SANTA CRUZ 99 -114
            ['nombre_colegio' => 'Colegio Alemán', 'provincia'=> 99],
            ['nombre_colegio' => 'Colegio Bautista Boliviano Brasileño', 'provincia'=> 100],
            ['nombre_colegio' => 'Colegio Boliviano Americano', 'provincia'=> 101],
            ['nombre_colegio' => 'Colegio Británico Santa Cruz', 'provincia'=> 102],
            ['nombre_colegio' => 'Colegio Cambridge College', 'provincia'=> 103],
            ['nombre_colegio' => 'Colegio Cardenal Cushing', 'provincia'=> 104],
            ['nombre_colegio' => 'Colegio Centro Boliviano Japonés', 'provincia'=> 105],
            ['nombre_colegio' => 'Colegio Don Bosco', 'provincia'=> 106],
            ['nombre_colegio' => 'Colegio Eagles School', 'provincia'=> 107],
            ['nombre_colegio' => 'Colegio Espíritu Santo', 'provincia'=> 108],
            ['nombre_colegio' => 'Colegio Internacional de la Sierra', 'provincia'=> 109],
            ['nombre_colegio' => 'Colegio Isabel Saavedra', 'provincia'=> 110],
            ['nombre_colegio' => 'Colegio La Salle', 'provincia'=> 111],
            ['nombre_colegio' => 'Colegio Marista', 'provincia'=> 112],
            ['nombre_colegio' => 'Colegio Mayor San Lorenzo', 'provincia'=> 113],
            ['nombre_colegio' => 'Colegio Mayor Santo Tomás de Aquino', 'provincia'=> 114],
            //TARIJA 115-121
            ['nombre_colegio' => 'Belgrano Adultos', 'provincia'=> 115],
            ['nombre_colegio' => 'San Roque Adultos', 'provincia'=> 116],
            ['nombre_colegio' => 'Guadalquivir', 'provincia'=> 117],
            ['nombre_colegio' => 'Alcaldía Municipal', 'provincia'=> 118],
            ['nombre_colegio' => 'Nazaria Ignacia March Adultos', 'provincia'=> 119],
            ['nombre_colegio' => 'Perpetuo Socorro', 'provincia'=> 120],
            ['nombre_colegio' => 'San Antonio', 'provincia'=> 121],
            
        ];

        DB::table('colegio')->insert($colegios);
    }
}
