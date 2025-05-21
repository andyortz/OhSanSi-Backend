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
            ['nombre_colegio' => '8 de Septiembre', 'id_provincia' => 1],
            ['nombre_colegio' => 'Acción Social C', 'id_provincia' => 2],
            ['nombre_colegio' => 'Antonio Gausset C', 'id_provincia' => 3],
            ['nombre_colegio' => 'Azari', 'id_provincia' => 4],
            ['nombre_colegio' => 'Cardenal Maurer A', 'id_provincia' => 5],
            ['nombre_colegio' => 'Cristina Aitken de Gutierrez B', 'id_provincia' => 6],
            ['nombre_colegio' => 'San Roque', 'id_provincia' => 7],
            ['nombre_colegio' => 'Domingo Savio A', 'id_provincia' => 8],
            ['nombre_colegio' => 'Flora Quiroga de Ortuzte A', 'id_provincia' => 9],
            ['nombre_colegio' => 'San Xavier C', 'id_provincia' => 10],
            ['nombre_colegio' => 'Francisco Cermeño', 'id_provincia' => 11],
            //COCHABAMBA
            ['nombre_colegio' => 'Obispado de Aiquile', 'id_provincia'=> 12],
            ['nombre_colegio' => 'Marcelo Quiroga Santa Cruz', 'id_provincia'=> 13],
            ['nombre_colegio' => 'Jesús María', 'id_provincia'=> 14],
            ['nombre_colegio' => 'Arani A', 'id_provincia'=> 15],
            ['nombre_colegio' => 'Arque', 'id_provincia'=> 16],
            ['nombre_colegio' => 'San Juan Bautista', 'id_provincia'=> 17],
            ['nombre_colegio' => 'Claudina Thevenet', 'id_provincia'=> 18],
            ['nombre_colegio' => 'Independencia', 'id_provincia'=> 19],
            ['nombre_colegio' => 'Capinota', 'id_provincia'=> 20],
            ['nombre_colegio' => 'Conivura', 'id_provincia'=> 21],
            ['nombre_colegio' => 'San José Obrero', 'id_provincia'=> 22],
            ['nombre_colegio' => 'Jorge Trigo Andia', 'id_provincia'=> 23],
            ['nombre_colegio' => '27 de Mayo', 'id_provincia'=> 24],
            ['nombre_colegio' => 'Abaroa C', 'id_provincia'=> 25],
            ['nombre_colegio' => 'Americano A', 'id_provincia'=> 26],
            ['nombre_colegio' => 'Benjamín Iriarte Rojas', 'id_provincia'=> 27],
            ['nombre_colegio' => 'Bernardino Bilbao Rioja', 'id_provincia'=> 28],
            //BENI
            ['nombre_colegio' => 'San Jose', 'id_provincia'=> 29],
            ['nombre_colegio' => 'Industrial 6 de Agosto', 'id_provincia'=> 30],
            ['nombre_colegio' => 'Nazaria Ignacia Marchi', 'id_provincia'=> 31],
            ['nombre_colegio' => 'Sagrado Corazón de Jesús', 'id_provincia'=> 32],
            ['nombre_colegio' => 'Santisima Trinidad', 'id_provincia'=> 33],
            ['nombre_colegio' => 'San Francisco de Asis', 'id_provincia'=> 34],
            ['nombre_colegio' => 'Etha Casarabe', 'id_provincia'=> 35],
            ['nombre_colegio' => 'Santa Cruz', 'id_provincia'=> 36],
            ['nombre_colegio' => 'Mons Manuel Eguiguren', 'id_provincia'=> 37],
            ['nombre_colegio' => 'San Vicente II', 'id_provincia'=> 29],
            ['nombre_colegio' => 'Casarabe', 'id_provincia'=> 30],
            ['nombre_colegio' => 'Ni Coma', 'id_provincia'=> 31],
            ['nombre_colegio' => 'Peroto', 'id_provincia'=> 32],

            //LA PAZ
            ['nombre_colegio' => 'Jose Santos Vargas', 'id_provincia'=> 38],
            ['nombre_colegio' => 'Alto Pasankeri Sur', 'id_provincia'=> 39],
            ['nombre_colegio' => 'Luis Espinal Camps N°1', 'id_provincia'=> 40],
            ['nombre_colegio' => 'Luis Espinal Camps N°2', 'id_provincia'=> 41],
            ['nombre_colegio' => 'Raul Salmon de la Barra', 'id_provincia'=> 42],
            ['nombre_colegio' => 'San Jose I', 'id_provincia'=> 43],
            ['nombre_colegio' => 'San Jose II', 'id_provincia'=> 44],
            ['nombre_colegio' => 'San Miguel de Alpacoma', 'id_provincia'=> 45],
            ['nombre_colegio' => 'Marcelo Quiroga Santa Cruz', 'id_provincia'=> 46],
            ['nombre_colegio' => 'Hugo Chavez Frias', 'id_provincia'=> 47],
            ['nombre_colegio' => 'Carlos Medinecelli', 'id_provincia'=> 48],
            ['nombre_colegio' => '4 de Julio', 'id_provincia'=> 49],
            ['nombre_colegio' => 'Alto Tembladerani', 'id_provincia'=> 50],
            ['nombre_colegio' => 'Las Nieves', 'id_provincia'=> 51],
            ['nombre_colegio' => 'Ignacio Calderón Fé y Alegría I', 'id_provincia'=> 52],
            ['nombre_colegio' => 'Ignacio Calderón Fé y Alegría II', 'id_provincia'=> 53],
            ['nombre_colegio' => 'Jaime Zenobio Escalante', 'id_provincia'=> 54],
            ['nombre_colegio' => 'República del Japón', 'id_provincia'=> 55],
            ['nombre_colegio' => 'Puerto Rico', 'id_provincia'=> 56],
            ['nombre_colegio' => 'Humberto Vasquez Machicado', 'id_provincia'=> 57],
            ['nombre_colegio' => 'Cristo Rey Fé y Alegría', 'id_provincia'=> 58],
            ['nombre_colegio' => 'San Luis Fé y Alegría', 'id_provincia'=> 38],
            //ORURO
            ['nombre_colegio' => 'Poopo', 'id_provincia'=> 59],
            ['nombre_colegio' => 'Litoral', 'id_provincia'=> 60],
            ['nombre_colegio' => 'Curahuara de Carangas', 'id_provincia'=> 61],
            ['nombre_colegio' => 'Corque', 'id_provincia'=> 62],
            ['nombre_colegio' => '13 de Septiembre', 'id_provincia'=> 63],
            ['nombre_colegio' => 'Wiñay Cacachaca', 'id_provincia'=> 64],
            ['nombre_colegio' => 'Fuerzas Armadas Oruro', 'id_provincia'=> 65],
            ['nombre_colegio' => 'Vida Abundante', 'id_provincia'=> 66],
            ['nombre_colegio' => 'Adolfo Ballivian', 'id_provincia'=> 67],
            ['nombre_colegio' => 'Oblato', 'id_provincia'=> 68],
            ['nombre_colegio' => 'Maria Quiroz', 'id_provincia'=> 69],
            ['nombre_colegio' => 'Marcos Beltran Avila', 'id_provincia'=> 70],
            ['nombre_colegio' => 'Julio Ramiro Condarco Morales', 'id_provincia'=> 71],
            ['nombre_colegio' => 'Carmen Guzman de Mier 3', 'id_provincia'=> 72],
            ['nombre_colegio' => 'Simon Bolivar', 'id_provincia'=> 73],
            ['nombre_colegio' => 'Ignacio Leon 3', 'id_provincia'=> 74],
            ['nombre_colegio' => 'Simon Rodriguez Carreño', 'id_provincia'=> 75],
            ['nombre_colegio' => 'Boliviano Alemán', 'id_provincia'=> 59],
            //PANDO 76-81
            ['nombre_colegio' => 'Juan Oliveira Barros', 'id_provincia'=> 76],
            ['nombre_colegio' => '11 de Octubre', 'id_provincia'=> 77],
            ['nombre_colegio' => 'El Saber', 'id_provincia'=> 78],
            ['nombre_colegio' => 'Mercedes Vaca de Lanza', 'id_provincia'=> 79],
            ['nombre_colegio' => 'El Porvenir', 'id_provincia'=> 80],
            ['nombre_colegio' => '4 de Septiembre', 'id_provincia'=> 81],
            //POTOSI 82-98
            ['nombre_colegio' => 'Acasio', 'id_provincia'=> 82],
            ['nombre_colegio' => 'Arampampa', 'id_provincia'=> 83],
            ['nombre_colegio' => 'Malvina Jaspers', 'id_provincia'=> 84],
            ['nombre_colegio' => 'Marcelo Quiroca', 'id_provincia'=> 85],
            ['nombre_colegio' => 'Betanzos', 'id_provincia'=> 86],
            ['nombre_colegio' => 'Otivio', 'id_provincia'=> 87],
            ['nombre_colegio' => 'Canton Potobamba', 'id_provincia'=> 88],
            ['nombre_colegio' => 'De Junio', 'id_provincia'=> 89],
            ['nombre_colegio' => 'Mcal Antonio Jose de Sucre', 'id_provincia'=> 90],
            ['nombre_colegio' => 'Caiza D', 'id_provincia'=> 91],
            ['nombre_colegio' => 'Carruyo', 'id_provincia'=> 92],
            ['nombre_colegio' => 'Chaqui', 'id_provincia'=> 93],
            ['nombre_colegio' => 'Chayanta', 'id_provincia'=> 94],
            ['nombre_colegio' => 'Colquichaca', 'id_provincia'=> 95],
            ['nombre_colegio' => 'Toropalca', 'id_provincia'=> 96],
            ['nombre_colegio' => 'Santa Rita', 'id_provincia'=> 97],
            ['nombre_colegio' => 'Llallagua', 'id_provincia'=> 98],
            //SANTA CRUZ 99 -114
            ['nombre_colegio' => 'Colegio Alemán', 'id_provincia'=> 99],
            ['nombre_colegio' => 'Colegio Bautista Boliviano Brasileño', 'id_provincia'=> 100],
            ['nombre_colegio' => 'Colegio Boliviano Americano', 'id_provincia'=> 101],
            ['nombre_colegio' => 'Colegio Británico Santa Cruz', 'id_provincia'=> 102],
            ['nombre_colegio' => 'Colegio Cambridge College', 'id_provincia'=> 103],
            ['nombre_colegio' => 'Colegio Cardenal Cushing', 'id_provincia'=> 104],
            ['nombre_colegio' => 'Colegio Centro Boliviano Japonés', 'id_provincia'=> 105],
            ['nombre_colegio' => 'Colegio Don Bosco', 'id_provincia'=> 106],
            ['nombre_colegio' => 'Colegio Eagles School', 'id_provincia'=> 107],
            ['nombre_colegio' => 'Colegio Espíritu Santo', 'id_provincia'=> 108],
            ['nombre_colegio' => 'Colegio Internacional de la Sierra', 'id_provincia'=> 109],
            ['nombre_colegio' => 'Colegio Isabel Saavedra', 'id_provincia'=> 110],
            ['nombre_colegio' => 'Colegio La Salle', 'id_provincia'=> 111],
            ['nombre_colegio' => 'Colegio Marista', 'id_provincia'=> 112],
            ['nombre_colegio' => 'Colegio Mayor San Lorenzo', 'id_provincia'=> 113],
            ['nombre_colegio' => 'Colegio Mayor Santo Tomás de Aquino', 'id_provincia'=> 114],
            //TARIJA 115-121
            ['nombre_colegio' => 'Belgrano Adultos', 'id_provincia'=> 115],
            ['nombre_colegio' => 'San Roque Adultos', 'id_provincia'=> 116],
            ['nombre_colegio' => 'Guadalquivir', 'id_provincia'=> 117],
            ['nombre_colegio' => 'Alcaldía Municipal', 'id_provincia'=> 118],
            ['nombre_colegio' => 'Nazaria Ignacia March Adultos', 'id_provincia'=> 119],
            ['nombre_colegio' => 'Perpetuo Socorro', 'id_provincia'=> 120],
            ['nombre_colegio' => 'San Antonio', 'id_provincia'=> 121],
            
        ];

        DB::table('colegio')->insert($colegios);
    }
}
