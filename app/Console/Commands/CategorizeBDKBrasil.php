<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\Category;
use App\Models\Certifier;

class CategorizeBDKBrasil extends Command
{
    protected $signature = 'categorize:bdk';
    protected $description = 'Asignar categoría a productos de BDK Brasil según las categorías del sitio bdk.com.br';

    /**
     * Mapeo de categorías de bdk.com.br (en portugués) a categorías existentes del sitio.
     */
    private const CATEGORY_MAP = [
        'ACHOCOLATADOS EM PÓ' => 'leche-chocolatada',
        'ACHOCOLATADOS LÍQUIDOS' => 'leche-chocolatada',
        'AÇÚCARES E ADOÇANTES' => 'mermeladas-y-dulces',
        'ALFARROBA' => 'chocolates',
        'ALIMENTOS FUNCIONAIS' => 'alimentos-y-bebidas',
        'ALIMENTOS INFANTIS' => 'alimentos-y-bebidas',
        'ARROZ' => 'arroz',
        'ATUM' => 'pescados-y-mariscos',
        'AVEIA E DERIVADOS' => 'avena',
        'AZEITES E ÓLEOS' => 'alimentos-y-bebidas',
        'AZEITONAS' => 'conservas',
        'BALAS E PIRULITOS' => 'caramelos-y-chicles',
        'BARRA DE CEREAIS E/OU FRUTAS SECAS E/OU PROTEÍNAS' => 'cereales',
        'BATATAS' => 'papas-fritas',
        'BEBIDAS À BASE DE AVEIA' => 'leches-vegetales',
        'BEBIDAS À BASE DE SOJA' => 'bebidas-de-soja',
        'BEBIDAS ALCOÓLICAS' => 'bebidas-alcoholicas',
        'BEBIDAS LÁCTEAS - COALHADAS - IOGURTES - LEITE FERMENTADO' => 'yogurt',
        'BEBIDAS NÃO ALCOÓLICAS' => 'bebidas',
        'BOLACHAS E COOKIES' => 'galletas-y-crackers',
        'BOLOS' => 'tortas-y-budines',
        'CAFÉ EXPRESSO' => 'cafe',
        'CAFÉ SOLÚVEL' => 'cafe',
        'CAFÉ TORRADO E MOÍDO' => 'cafe',
        'CAFÉ TORRADO EM GRÃOS' => 'cafe',
        'CALDAS E COBERTURAS' => 'mermeladas-y-dulces',
        'CAPPUCCINO' => 'cafe',
        'CARNES' => 'carnes-rojas',
        'CATCHUP' => 'alimentos-y-bebidas',
        'CEREAIS MATINAIS' => 'cereales',
        'CHÁS' => 'te-e-infusiones',
        'CHOCOLATES' => 'chocolates',
        'COCO E DERIVADOS' => 'frutos-secos',
        'CONDIMENTOS' => 'alimentos-y-bebidas',
        'CONFEITARIA E PANIFICAÇÃO' => 'panaderia-y-cereales',
        'CONSERVAS DIVERSAS' => 'conservas',
        'CORANTES' => 'alimentos-y-bebidas',
        'CREMES LÁCTEOS' => 'crema-lactea',
        'CREMES NÃO LÁCTEOS' => 'crema-no-lactea',
        'CULINÁRIA ÁRABE / ORIENTE MÉDIO' => 'alimentos-y-bebidas',
        'CULINÁRIA JAPONESA' => 'alimentos-y-bebidas',
        'CULINÁRIA MEXICANA' => 'alimentos-y-bebidas',
        'DOCES' => 'dulces-y-postres',
        'DOCES CREMOSOS' => 'dulces-y-postres',
        'ESSÊNCIAS' => 'harinas-y-premezclas',
        'FARINHAS, FARELOS, FÉCULAS, FLOCOS E GRÃOS' => 'harinas-y-premezclas',
        'FERMENTOS' => 'harinas-y-premezclas',
        'FIBRAS E GÉRMENS' => 'harinas-y-premezclas',
        'FRUTAS CONGELADAS' => 'frutas-enlatadas',
        'FRUTAS FRESCAS ' => 'frutas-frescas',
        'FRUTAS OLEAGINOSAS E DERIVADOS' => 'frutos-secos',
        'FRUTAS SECAS  OU DESIDRATADAS' => 'frutos-secos',
        'GELEIAS' => 'mermeladas-y-dulces',
        'GORDURAS VEGETAIS' => 'manteca-y-margarina',
        'HAMBURGUER' => 'hamburguesas',
        'HIGIENE' => 'alimentos-y-bebidas',
        'INGREDIENTES E COMPLEMENTOS PARA SORVETES E DOCES' => 'helados',
        'IOGURTES' => 'yogurt',
        'LEGUMES E VEGETAIS CONGELADOS' => 'verduras-enlatadas',
        'LEGUMES E VEGETAIS EM CONSERVA' => 'verduras-enlatadas',
        'LEGUMES, VEGETAIS E OUTROS' => 'verduras-frescas',
        'LEITE' => 'leche',
        'LEITE CONDENSADO' => 'leche',
        'LEITE EM PÓ' => 'leche',
        'LEITE FERMENTADO' => 'yogurt',
        'MAIONESES' => 'alimentos-y-bebidas',
        'MANDIOCA E DERIVADOS' => 'verduras-frescas',
        'MANTEIGAS' => 'manteca-y-margarina',
        'MARGARINAS' => 'manteca-y-margarina',
        'MASSAS' => 'fideos-y-pastas',
        'MEL E DERIVADOS' => 'mermeladas-y-dulces',
        'MOLHO INGLÊS' => 'alimentos-y-bebidas',
        'MOLHOS' => 'alimentos-y-bebidas',
        'MOLHOS DE ALHO' => 'alimentos-y-bebidas',
        'MOLHOS DE PIMENTA' => 'alimentos-y-bebidas',
        'MOLHOS DE SOJA (SHOYU)' => 'alimentos-y-bebidas',
        'MOLHOS PARA CARNE' => 'alimentos-y-bebidas',
        'MOLHOS PARA SALADA' => 'alimentos-y-bebidas',
        'MOSTARDAS' => 'alimentos-y-bebidas',
        'OVOS' => 'huevos',
        'PÃES' => 'pan',
        'PALMITOS' => 'conservas',
        'PANIFICAÇÃO' => 'pan',
        'PASTAS DOCES / PASTAS SALGADAS' => 'mermeladas-y-dulces',
        'PEIXES DIVERSOS' => 'pescados-y-mariscos',
        'PETISCOS' => 'snacks-y-copetin',
        'PETISCOS, SALGADINHOS E SNACKS' => 'snacks-y-copetin',
        'PIMENTAS' => 'alimentos-y-bebidas',
        'PIPOCAS' => 'snacks-de-maiz',
        'PÓ PARA PREPARO DE BEBIDA' => 'bebidas',
        'POLPAS DE FRUTAS' => 'jugos',
        'POLPAS DE LEGUMES E/OU VEGETAIS' => 'verduras-enlatadas',
        'PUDINS E SOBREMESAS' => 'postres-en-polvo',
        'QUEIJOS' => 'quesos',
        'SAL' => 'alimentos-y-bebidas',
        'SARDINHAS' => 'pescados-y-mariscos',
        'SEMENTES' => 'frutos-secos',
        'SOJA E DERIVADOS' => 'proteinas-vegetales',
        'SOPAS' => 'alimentos-y-bebidas',
        'SORVETES  BASE DE ÁGUA' => 'helados',
        'SORVETES BASE DE LEITE' => 'helados',
        'SUCOS DE FRUTAS / NÉCTAR DE FRUTAS / BEBIDAS DE FRUTAS' => 'jugos',
        'SUPLEMENTOS ALIMENTARES' => 'alimentos-y-bebidas',
        'TEMPEROS EM GERAL' => 'alimentos-y-bebidas',
        'TOMATE E DERIVADOS' => 'conservas',
        'TORRADAS' => 'pan',
        'TORTAS DOCES' => 'tortas-y-budines',
        'TRIGO E DERIVADOS' => 'harinas-y-premezclas',
        'VINAGRES' => 'alimentos-y-bebidas',
        'VINHOS E ESPUMANTES' => 'vinos',
        'WAFERS' => 'galletas-y-crackers',
        'XAROPES E PREPARADOS' => 'mermeladas-y-dulces',
    ];

    private $categorized = 0;
    private $notFound = 0;

    public function handle()
    {
        $this->info('=== CATEGORIZACIÓN BDK BRASIL ===');

        $certifier = Certifier::where('slug', 'bdk-brasil')->first();
        if (!$certifier) {
            $this->error('Certificadora BDK Brasil no encontrada');
            return;
        }

        $categoryIds = Category::pluck('id', 'slug');

        foreach (self::CATEGORY_MAP as $bdkCategory => $slug) {
            $categoryId = $categoryIds[$slug] ?? null;

            if (!$categoryId) {
                $this->warn("Categoría '{$slug}' no existe, omitiendo '{$bdkCategory}'");
                continue;
            }

            $names = $this->fetchCategoryProductNames($bdkCategory);
            $this->info("'{$bdkCategory}' -> '{$slug}': " . count($names) . ' productos');

            foreach ($names as $name) {
                $updated = Product::where('certifier_id', $certifier->id)
                    ->whereNull('category_id')
                    ->whereRaw('UPPER(name) = ?', [mb_strtoupper(trim($name))])
                    ->update(['category_id' => $categoryId]);

                if ($updated > 0) {
                    $this->categorized += $updated;
                } else {
                    $this->notFound++;
                }
            }

            usleep(300000);
        }

        $this->info("\n" . str_repeat('=', 60));
        $this->info('RESUMEN');
        $this->info(str_repeat('=', 60));
        $this->info("Productos categorizados: {$this->categorized}");
        $this->info("Nombres no encontrados en BD: {$this->notFound}");

        $remaining = Product::where('certifier_id', $certifier->id)->whereNull('category_id')->count();
        $this->info("Productos BDK aún sin categoría: {$remaining}");
    }

    /**
     * Obtener todos los nombres de producto de una categoría de bdk.com.br, recorriendo la paginación.
     */
    private function fetchCategoryProductNames(string $categoria): array
    {
        $names = [];
        $offset = 0;

        while (true) {
            $url = $offset === 0
                ? 'https://bdk.com.br/produtos'
                : "https://bdk.com.br/produtos/p/{$offset}";

            try {
                $response = Http::timeout(30)
                    ->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
                    ])
                    ->get($url, [
                        'produto' => '',
                        'fabricante' => '',
                        'categoria' => $categoria,
                        'tipo' => '',
                        'pesquisar' => 'Pesquisar',
                    ]);
            } catch (\Exception $e) {
                break;
            }

            if (!$response->successful()) {
                break;
            }

            $html = $response->body();

            if (!preg_match_all('/<h1>\s*<a href="[^"]+"[^>]*>([^<]+)<\/a>\s*<\/h1>/is', $html, $matches)) {
                break;
            }

            foreach ($matches[1] as $name) {
                $names[] = html_entity_decode(trim($name), ENT_QUOTES | ENT_HTML5);
            }

            if (count($matches[1]) < 10 || $offset > 500) {
                break;
            }

            $offset += 10;
            usleep(200000);
        }

        return $names;
    }
}
