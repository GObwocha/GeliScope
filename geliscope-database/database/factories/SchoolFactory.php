<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\School>
 */
class SchoolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $counties = [
            'Nairobi' => ['Westlands', 'Dagoretti', 'Langata', 'Kasarani', 'Starehe', 'Embakasi', 'Roysambu', 'Makadara', 'Kibra', 'Kamukunji'],
            'Kiambu' => ['Thika Town', 'Ruiru', 'Juja', 'Kiambu Town', 'Limuru', 'Githunguri', 'Kabete', 'Gatundu North', 'Gatundu South', 'Lari'],
            'Mombasa' => ['Nyali', 'Mvita', 'Kisauni', 'Likoni', 'Changamwe', 'Jomvu'],
            'Kisumu' => ['Kisumu Central', 'Kisumu East', 'Kisumu West', 'Nyando', 'Seme'],
            'Nakuru' => ['Nakuru Town East', 'Nakuru Town West', 'Naivasha', 'Molo', 'Gilgil', 'Subukia', 'Kuresoi North', 'Kuresoi South', 'Njoro', 'Rongai'],
            'Baringo' => ['Baringo North', 'Baringo Central', 'Baringo South', 'Tiaty'],
            'Bomet' => ['Bomet Central', 'Bomet East', 'Chepalungu', 'Sotik', 'Konoin'],
            'Bungoma' => ['Bungoma East', 'Bungoma North', 'Bungoma South', 'Bungoma West', 'Kimilili', 'Sirisia', 'Tongaren', 'Webuye East', 'Webuye West'],
            'Busia' => ['Busia', 'Bunyala', 'Butula', 'Funyula', 'Nambale', 'Teso North', 'Teso South'],
            'Elgeyo-Marakwet' => ['Keiyo North', 'Keiyo South', 'Marakwet East', 'Marakwet West'],
            'Embu' => ['Manyatta', 'Mbeere North', 'Mbeere South', 'Runyenjes', 'Embu West'],
            'Garissa' => ['Garissa Township', 'Dadaab', 'Fafi', 'Balambala', 'Lagdera', 'Ijara', 'Garissa North', 'Garissa South'],
            'Homa Bay' => ['Homa Bay', 'Rachuonyo North', 'Rachuonyo South', 'Suba North', 'Suba South', 'Ndhiwa', 'Rangwe', 'Kabondo Kasipul', 'Mbita'],
            'Isiolo' => ['Isiolo North', 'Isiolo South'],
            'Kajiado' => ['Kajiado Central', 'Kajiado North', 'Kajiado East', 'Kajiado West', 'Kajiado South'],
            'Kakamega' => ['Butere', 'Ikolomani', 'Kakamega Central', 'Kakamega East', 'Kakamega North', 'Kakamega South', 'Lugari', 'Likuyani', 'Mumias East', 'Mumias West', 'Matungu', 'Shinyalu'],
            'Kericho' => ['Ainamoi', 'Bureti', 'Cherangany', 'Kipkelion East', 'Kipkelion West', 'Sigowet Soin', 'Konoin'],
            'Kilifi' => ['Kilifi North', 'Kilifi South', 'Ganze', 'Malindi', 'Magarini', 'Rabai', 'Kaloleni'],
            'Kirinyaga' => ['Kirinyaga Central', 'Kirinyaga East', 'Kirinyaga West', 'Mwea East', 'Mwea West'],
            'Kisii' => ['Bonchari', 'Kitutu Chache North', 'Kitutu Chache South', 'Nyaribari Chache', 'Nyaribari Masaba', 'Bobasi', 'Bomachoge Borabu', 'Gucha Miruka'],
            'Kitui' => ['Kitui East', 'Kitui Rural', 'Kitui South', 'Kitui West', 'Mwingi Central', 'Mwingi North', 'Mwingi West'],
            'Kwale' => ['Msambweni', 'Matuga', 'Kinango', 'Lunga Lunga'],
            'Laikipia' => ['Laikipia East', 'Laikipia North', 'Laikipia West', 'Nyahururu', 'Nanyuki'],
            'Lamu' => ['Lamu East', 'Lamu West', 'Faza', 'Witu'],
            'Machakos' => ['Machakos Town', 'Mavoko', 'Mwala', 'Yatta', 'Kangundo', 'Masinga', 'Matungulu', 'Kathiani', 'Kaiti'],
            'Mandera' => ['Mandera East', 'Mandera North', 'Mandera South', 'Mandera West', 'Banissa', 'Lafey'],
            'Marsabit' => ['North Horr', 'Saku', 'Laisamis', 'Moyale', 'Bubisa'],
            'Meru' => ['Imenti North', 'Imenti Central', 'Imenti South', 'Buuri', 'Tigania East', 'Tigania West', 'North Imenti'],
            'Migori' => ['Suna East', 'Suna West', 'Uriri', 'Rongo', 'Awendo', 'Nyatike', 'Kuria East', 'Kuria West'],
            'Murang\'a' => ['Mathioya', 'Kiharu', 'Kigumo', 'Kangema', 'Gatanga', 'Maragua', 'Kandara', 'Mukurweini'],
            'Nandi' => ['Nandi Hills', 'Chesumei', 'Mosop', 'Emgwen', 'Tindiret', 'Aldai', 'Nandi East', 'Nandi South'],
            'Narok' => ['Narok East', 'Narok North', 'Narok South', 'Narok West', 'Emurua Dikirr', 'Trans Mara East', 'Trans Mara West', 'Kilgoris'],
            'Nyamira' => ['North Mugirango', 'West Mugirango', 'Borabu', 'Kitutu Chache North', 'Kitutu Chache South', 'Nyamira', 'Manga'],
            'Nyandarua' => ['Nyandarua North', 'Nyandarua South', 'Ol Kalou', 'Kipipiri', 'Kinangop', 'Njabini'],
            'Nyeri' => ['Tetu', 'Kieni', 'Mathira', 'Othaya', 'Mukurweini', 'Nyeri Town'],
            'Samburu' => ['Samburu East', 'Samburu North', 'Samburu West'],
            'Siaya' => ['Alego Usonga', 'Bondo', 'Gem', 'Rarieda', 'Ugunja', 'Ugenya', 'Siaya'],
            'Taita-Taveta' => ['Taita', 'Taveta', 'Mwatate', 'Voi', 'Wundanyi'],
            'Tana River' => ['Garsen', 'Bura', 'Galole', 'Hola', 'Tana Delta'],
            'Tharaka-Nithi' => ['Tharaka', 'Maara', 'Chuka', 'Igambang\'ombe'],
            'Trans Nzoia' => ['Kwanza', 'Endebess', 'Saboti', 'Kiminini', 'Cherangany'],
            'Turkana' => ['Turkana Central', 'Turkana South', 'Turkana North', 'Turkana West', 'Turkana East', 'Loima'],
            'Uasin Gishu' => ['Eldoret East', 'Eldoret North', 'Eldoret South', 'Kesses', 'Moiben', 'Turbo'],
            'Vihiga' => ['Emuhaya', 'Hamisi', 'Luanda', 'Sabatia', 'Vihiga'],
            'Wajir' => ['Wajir East', 'Wajir North', 'Wajir South', 'Wajir West', 'Eldas', 'Tarbaj', 'Habaswein'],
            'West Pokot' => ['Kapenguria', 'Kacheliba', 'Sigor', 'Pokot South', 'Pokot Central', 'Pokot North'],
        ];

        $county = fake()->randomElement(array_keys($counties));
        $subCounty = fake()->randomElement($counties[$county]);

        return [
            'name' => fake()->company() . ' Academy',
            'county' => $county,
            'sub_county' => $subCounty,
            'school_type' => fake()->randomElement(['primary', 'secondary', 'tertiary']),
        ];
    }
}
