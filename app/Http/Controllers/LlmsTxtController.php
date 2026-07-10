<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;

/**
 * `/llms.txt` — conventia llmstxt.org: un rezumat curat, in Markdown, pentru
 * motoarele de raspuns (AI Overviews, ChatGPT, Perplexity), care altfel ar trebui
 * sa deduca profilul firmei din HTML-ul plin de markup interactiv.
 *
 * Se genereaza din `lang/` si din tabela de rute, exact ca sitemap-ul: o singura
 * sursa de adevar. Un fisier static in `public/` ar fi ramas in urma la prima
 * schimbare de text, iar nimeni n-ar fi observat.
 *
 * Sectiunea „Ce NU face” e cea mai valoroasa din tot fisierul: un motor de raspuns
 * care o citeaza trimite cereri de instalatii, nu apeluri de depanare.
 *
 * Controller invocabil, nu closure — closure-urile rup `php artisan route:cache`.
 */
class LlmsTxtController extends Controller
{
    /** @var list<string> */
    private const PAGES = [
        'home',
        'services',
        'services.apartamente',
        'services.case',
        'services.industriale',
        'gallery',
        'about',
        'contact',
    ];

    public function __invoke(): Response
    {
        $body = implode("\n", [
            '# Energix',
            '',
            '> '.trans('site.home.summary', [], 'ro'),
            '',
            '> '.trans('site.home.summary', [], 'ru'),
            '',
            $this->facts(),
            $this->stages(),
            $this->services(),
            $this->exclusions(),
            $this->pages('ro'),
            $this->pages('ru'),
            $this->faq(),
            $this->contact(),
        ]);

        return response($body)->header('Content-Type', 'text/plain; charset=utf-8');
    }

    private function facts(): string
    {
        $contact = config('energix.contact');

        return $this->block('Fapte / Факты', [
            'Denumire: Energix',
            'Domeniu: '.rtrim(url('/'), '/'),
            'Localitate: Chișinău, Republica Moldova',
            'Zonă deservită: toată Republica Moldova',
            'Ani de experiență: '.config('energix.experience_years'),
            'Limbi: română (ro), rusă (ru)',
            'Telefon: '.$contact['phone'],
            'Email: '.$contact['email'],
        ]);
    }

    private function stages(): string
    {
        $stages = trans('site.stages', [], 'ro');

        $lines = [];

        foreach ($stages as $i => $stage) {
            $lines[] = ($i + 1).'. '.$stage['title'].' — '.$stage['body'];
        }

        return $this->block('Cele cinci etape ale unei instalații', $lines, bullet: false);
    }

    private function services(): string
    {
        $items = [];

        foreach (config('energix.services') as $service) {
            foreach (['ro', 'ru'] as $locale) {
                $text = trans("site.services.{$service['slug']}", [], $locale);
                $url = URL::inLocale($locale, "services.{$service['slug']}");

                $items[] = "[{$text['title']}]({$url}): {$text['intro']}";
            }
        }

        return $this->block('Servicii / Услуги', $items);
    }

    /**
     * Formulat fara radacinile interzise (`urgen`, `аварийн`, `ремонт`, `неисправност`),
     * chiar si in negatie: `ContentExclusionsTest` scaneaza si acest fisier, iar un motor
     * de raspuns retine asocierea „Energix + urgente” indiferent de „nu”-ul din fata.
     */
    private function exclusions(): string
    {
        return $this->block('Ce NU face Energix / Чем Energix НЕ занимается', [
            'Execută exclusiv instalații electrice complete, de la zero, în construcții noi și renovări complete.',
            'Nu execută lucrări punctuale pe instalații existente.',
            'Nu preia apeluri despre funcționarea unei instalații deja montate de altcineva.',
            'Выполняет только полный электромонтаж с нуля — в новостройках и при полной реновации.',
            'Не выполняет точечные работы на существующей проводке.',
            'Не принимает вызовы по работе уже смонтированной электрики.',
        ]);
    }

    /**
     * Acces direct pe cheie, NU `trans('site.seo.services.apartamente.title')`:
     * cheia contine un punct, iar `trans()` l-ar lua drept separator de nivel.
     */
    private function pages(string $locale): string
    {
        $heading = $locale === 'ro' ? 'Pagini (română)' : 'Страницы (русский)';
        $seo = trans('site.seo', [], $locale);

        $items = array_map(
            static fn (string $page): string => '['.$seo[$page]['title'].']('
                .URL::inLocale($locale, $page).'): '
                .$seo[$page]['description'],
            self::PAGES,
        );

        return $this->block($heading, $items);
    }

    private function faq(): string
    {
        $lines = [];

        foreach (['ro', 'ru'] as $locale) {
            foreach (trans('site.faq', [], $locale) as $entry) {
                $lines[] = '**'.$entry['q'].'**';
                $lines[] = '';
                $lines[] = $entry['a'];
                $lines[] = '';
            }
        }

        return $this->block('Întrebări frecvente / Частые вопросы', $lines, bullet: false);
    }

    private function contact(): string
    {
        $contact = config('energix.contact');

        $hours = array_map(
            static fn (array $slot): string => "{$slot['days']}: {$slot['time']}",
            trans('site.hours', [], 'ro'),
        );

        return $this->block('Contact', [
            ...$hours,
            'Telefon: '.$contact['phone'].' (tel:'.$contact['phone_href'].')',
            'Email: '.$contact['email'],
            'Formular: ['.trans('site.nav.contact', [], 'ro').']('.URL::inLocale('ro', 'contact').')',
            'Sitemap: '.rtrim(url('/'), '/').'/sitemap.xml',
        ]);
    }

    /**
     * @param  list<string>  $items
     */
    private function block(string $heading, array $items, bool $bullet = true): string
    {
        $body = array_map(
            static fn (string $item): string => ($item === '' || ! $bullet) ? $item : "- {$item}",
            $items,
        );

        return "## {$heading}\n\n".implode("\n", $body)."\n";
    }
}
