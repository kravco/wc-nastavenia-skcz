=== Nastavenia SK pre WooCommerce ===
Contributors: webikon, kravco
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=HJMDR4AEQNDME
Tags: checkout, company, vat, slovakia
Requires at least: 4.4
Tested up to: 4.8.2
WC tested up to: 3.2.5
Requires PHP: 5.5
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Tento plugin si kladie za cieľ uľahčiť život e-shopom na Slovensku. Snaží sa to dosiahnuť tým, že poskytuje funkcionalitu, ktorú potrebuje takmer každý slovenský e-shop.

== Description ==

Plugin je stále vo vývoji, rozhodne nie je dokončený a je aktuálne v štádiu beta (tzn. všetko by malo fungovať, možno až na pár prehliadnutých chýb). Budem veľmi vďačný za akúkoľvek spätnú väzbu, pripomienky i návrhy bna vylepšenie. Majte na pamäti, že cieľom pluginu je poskytovať funkcionalitu, ktorú potrebuje takmer každý e-shop, nie takú, ktorá sa zíde len špecifickej skupine.

V tomto momente má plugin len jedinú funkcionalitu: Umožniť nákup zákazníkov na firmu, spolu so zadaním firemných údajov (IČO, DIČ, IČ DPH). Postup vyzerá tak, že štandardne sú políčka pre nákup na firmu schované a zobrazia sa až vtedy, ak si zákazník zvolí nákup na firmu. Kvôli ďalšej možnosti rozšírenia je v pokladni vymenené poradie výberu krajiny a políčok pre firmu, keďže v medzinárodnom e-shope je logické najprv vybrať krajinu a až následne zadať údaje o firme – tie sú totiž špecifické pre každú krajinu.

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
2. Pokladňa pri nákupe na firmu – zobrazia sa dodatočné políčka.

== Changelog ==

= 1.2.0
* Pridanie spatnej kompatibility pre pluginy SuperFaktúra a Webikon Fakturácia -- plugin rozpozná IČO, DIČ a IČ DPH vytvorené aj týmito pluginmi

= 1.1.1
* Opravené zobrazovanie českého IČO a DIČ vo fakturačnej adrese

= 1.1.0
* Pridanie riešenia problému slovenské DIČ vs české DIČ; plugin pri zmene krajiny automaticky upraví popisky pri políčkach tak, aby nemiatli zákazníkov zo zahraničia: pre zákazníkov zo slovenska zobrazí IČ DPH, IČO i DIČ, z Česka len DIČ a IČO a pre ostatných len medzinárodné "VAT ID"

= 1.0.1 =
* Oprava chýb

= 1.0 =
* Vydanie prvej verzie pluginu

== Upgrade Notice ==

Žiadne upozornenia k aktualizácii na novú verziu.
