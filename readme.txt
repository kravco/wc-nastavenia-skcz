=== Nastavenia SK/CZ pre WooCommerce ===
Contributors: webikon, kravco
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=HJMDR4AEQNDME
Tags: checkout, company, vat, slovakia, czech-republic, european-union
Requires at least: 4.8
Tested up to: 4.9.6
Requires PHP: 5.5
Stable tag: trunk
License: GPL2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Tento plugin si kladie za cieľ uľahčiť život e-shopom na Slovensku a v Česku. Snaží sa to dosiahnuť tým, že poskytuje funkcionalitu, ktorú potrebuje takmer každý slovenský/český e-shop.

== Description ==

Plugin je stále vo vývoji, rozhodne nie je dokončený a je aktuálne v štádiu beta (tzn. všetko by malo fungovať, možno až na pár prehliadnutých chýb). Budem veľmi vďačný za akúkoľvek spätnú väzbu, pripomienky i návrhy na vylepšenie – prosím, píšte do fóra k tomuto pluginu. Majte na pamäti, že cieľom pluginu je poskytovať funkcionalitu, ktorú potrebuje takmer každý e-shop, nie takú, ktorá sa zíde len špecifickej skupine.

V tomto momente má plugin len jedinú funkcionalitu: Umožniť nákup zákazníkov na firmu, spolu so zadaním firemných údajov (IČO, DIČ, IČ DPH). Postup vyzerá tak, že štandardne sú políčka pre nákup na firmu schované a zobrazia sa až vtedy, ak si zákazník zvolí nákup na firmu. Kvôli ďalšej možnosti rozšírenia je v pokladni vymenené poradie výberu krajiny a políčok pre firmu, keďže v medzinárodnom e-shope je logické najprv vybrať krajinu a až následne zadať údaje o firme – tie sú totiž špecifické pre každú krajinu. Problém to robí najmä pri nákupe zo Slovenska do Česka a naopak, keďže v oboch krajinách poznáme pole DIČ, avšak v každej krajine znamená niečo iné (na Slovensku je to interné identifikačné číslo daňového úradu, v Česku je to názov pre celoeurópske registračné číslo pre DPH – toto číslo sa na Slovensku nazýva IČ DPH). Plugin tento problém rieši tak, že zákazníkom e-shopu zobrazí polia vždy s názvom, ktorý sa používa v krajine zvolenej v pokladni. Naopak, v administrácii sa používa názov polí podľa nastaveného jazyka, je teda naprieč administráciou jednotný (aj pre objednávky z rôznych krajín).

Okrem zadania údajov je možné tieto údaje upravovať v administrácii objednávky, v profile používateľa, po prihlásení v účte zákazníka a takisto získať tieto údaje pre použtie v iných pluginoch (typicky fakturačných). Tieto údaje sa tiež generujú do sformátovanej fakturačnej adresy.

== Installation ==

1. Nahrajte celý adresár `wc-nastavenia-skcz` do adresára `/wp-content/plugins/`
1. Aktivujte plugin cez menu 'Pluginy' v administrácii WordPress
1. V prípade, že používate fakturačný plugin, overte si, či je s týmto pluginom kompatibilný

== Frequently Asked Questions ==

= Používam fakturačný plugin X, po nainštalovaní vášho pluginu mám problém Y =

Je pravdepodobné, že pluginy nie sú kompatibilné. Skúste zistiť, v čom spočíva problém Y a podľa toho napísať nám alebo autorovi fakturačného pluginu X.

== Screenshots ==

1. Pokladňa pri nákupe fyzickej osoby.
1. Pokladňa pri nákupe na firmu SK – zobrazia sa dodatočné políčka.
1. Pokladňa pri nákupe na firmu CZ – políčka majú správne názvy.
1. Stránka s informáciami o objednávke a poďakovaním.
1. Zobrazenie fakturačnej adresy na stránke môj účet
1. Úprava polí na stránke môj účet
1. Zobrazenie objednávky v administrácii
1. Úprava polí v administrácii objednávky
1. Úprava polí v profile používateľa

== Changelog ==

= 2.0.2 =
* Opravenie chyby v javascripte

= 2.0.1 =
* Opravené deklarovanie podpory WordPress verzií

= 2.0 =
* Opravenie mätúceho zobrazovania slovenského a českého IČ DPH/DIČ (pri zobrazení adresy pri nákupe /frontend/ sa zobrazuje vždy názov poľa tak ako sa volá v zvolenej krajine, pri zobrazení v administrácii /backend/ sa použije názov políčka v jazyku, ktorý má nastavený aktuálny používateľ)
* Pridaná podpora pre všetky krajiny používajúce európsky systém DPH (krajiny okrem Slovenska a Česka zatiaľ nemajú prispôsobený lokálny názov, zobrazí sa pre ne všeobecný popis VAT ID)
* Pridané filtre priamo pri ukladaní a získavaní meta hodnôt
* Doplnenie ďalších screenshotov

= 1.2.0 =
* Pridanie spatnej kompatibility pre pluginy SuperFaktúra a Webikon Fakturácia -- plugin rozpozná IČO, DIČ a IČ DPH vytvorené aj týmito pluginmi
* Pridanie filtra pri získavaní informácií o type nákupu a políčkach IČO, DIČ a IČ DPH

= 1.1.1 =
* Opravené zobrazovanie českého IČO a DIČ vo fakturačnej adrese

= 1.1.0 =
* Pridanie riešenia problému slovenské DIČ vs české DIČ; plugin pri zmene krajiny automaticky upraví popisky pri políčkach tak, aby nemiatli zákazníkov zo zahraničia: pre zákazníkov zo slovenska zobrazí IČ DPH, IČO i DIČ, z Česka len DIČ a IČO a pre ostatných len medzinárodné "VAT ID"

= 1.0.1 =
* Oprava chýb

= 1.0 =
* Vydanie prvej verzie pluginu

== Upgrade Notice ==

WooCommerce verzia 3.4.0 zaviedla nekompatibilitu s naším pluginom, ktorá znamená, že nie je možné upraviť políčka v administrácii objednávky. Iná funkcionalita by mala byť bez problémov funkčná. Úprava, ktorá opäť umožní kompatibilitu by mala byť vydaná vo verzii 3.4.3, následne po overení funkčnosti vydáme plne kompatibilnú verziu.
